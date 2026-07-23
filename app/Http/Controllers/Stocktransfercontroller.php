<?php

namespace App\Http\Controllers;

use App\Helpers\AuditLogger;
use App\Models\Produk;
use App\Models\StockTransfer;
use App\Models\WarehouseStock;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class StockTransferController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $transfers = StockTransfer::with(['barang', 'fromWarehouse', 'toWarehouse', 'requester', 'approver', 'shipper', 'receiver'])
            ->when($user->role_id !== 1, function ($query) use ($user) {
                // Non-Admin hanya lihat transfer yang menyentuh gudang yang dia urus
                $warehouseIds = $user->warehouseAssignments()->pluck('warehouse_id');
                $query->where(function ($q) use ($warehouseIds) {
                    $q->whereIn('from_warehouse_id', $warehouseIds)
                        ->orWhereIn('to_warehouse_id', $warehouseIds);
                });
            })
            ->latest()
            ->paginate(15);

        return view('pages.stock_transfers.showstocktransfers', compact('transfers'));
    }

    public function create()
    {
        $barangList = Produk::orderBy('nama_barang')->get();

        $user = Auth::user();

        // "Dari" warehouse: Staff/Supervisor cuma boleh pilih gudang yang dia
        // ditugaskan. Admin boleh pilih semua gudang.
        $fromWarehouses = $user->role_id === 1
            ? Warehouse::orderBy('name')->get()
            : $user->warehouses()->orderBy('name')->get();

        // "Ke" warehouse: selalu semua gudang yang ada, karena tujuan
        // transfer bisa ke gudang manapun, bukan cuma yang dia tugaskan.
        $toWarehouses = Warehouse::orderBy('name')->get();

        return view('pages.stock_transfers.addStockTransfers', compact('barangList', 'fromWarehouses', 'toWarehouses'));
    }

    /**
     * Buat transfer sebagai draft. Belum masuk antrian approval.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'barang_id'         => 'required|exists:produk,barang_id',
            'from_warehouse_id' => 'required|exists:warehouses,warehouse_id',
            'to_warehouse_id'   => 'required|exists:warehouses,warehouse_id|different:from_warehouse_id',
            'quantity'          => 'required|integer|min:1',
            'notes'             => 'nullable|string|max:1000',
        ]);

        $sourceStock = WarehouseStock::where('barang_id', $validated['barang_id'])
            ->where('warehouse_id', $validated['from_warehouse_id'])
            ->first();

        if (!$sourceStock || $sourceStock->stock_quantity < $validated['quantity']) {
            throw ValidationException::withMessages([
                'quantity' => 'Stok tidak cukup di gudang asal. Tersedia: ' . ($sourceStock->stock_quantity ?? 0),
            ]);
        }

        $transfer = StockTransfer::create([
            'transfer_code'     => StockTransfer::generateTransferCode(),
            'barang_id'         => $validated['barang_id'],
            'from_warehouse_id' => $validated['from_warehouse_id'],
            'to_warehouse_id'   => $validated['to_warehouse_id'],
            'quantity'          => $validated['quantity'],
            'status'            => 'draft',
            'requested_by'      => Auth::id(),
            'notes'             => $validated['notes'] ?? null,
        ]);

        AuditLogger::log('created', [
            'transfer_code'  => $transfer->transfer_code,
            'barang'         => $transfer->barang->nama_barang,
            'from_warehouse' => $transfer->fromWarehouse->name,
            'to_warehouse'   => $transfer->toWarehouse->name,
            'quantity'       => $transfer->quantity,
            'status'         => $transfer->status,
        ]);

        return redirect()->route('stock-transfers.index')
            ->with('success', "Draft {$transfer->transfer_code} dibuat. Silakan submit untuk minta approval.");
    }

    /**
     * draft -> menunggu_approval
     */
    public function submit(StockTransfer $stockTransfer)
    {
        $this->authorize('submit', $stockTransfer);

        $stockTransfer->update(['status' => 'menunggu_approval']);

        AuditLogger::log('updated', [
            'transfer_code' => $stockTransfer->transfer_code,
            'status_before' => 'draft',
            'status_after'  => 'menunggu_approval',
        ]);

        return back()->with('success', "Transfer {$stockTransfer->transfer_code} diajukan untuk approval.");
    }

    /**
     * menunggu_approval -> disetujui
     */
    public function approve(StockTransfer $stockTransfer)
    {
        $this->authorize('approve', $stockTransfer);

        $stockTransfer->update([
            'status'      => 'disetujui',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

        AuditLogger::log('updated', [
            'transfer_code' => $stockTransfer->transfer_code,
            'status_before' => 'menunggu_approval',
            'status_after'  => 'disetujui',
        ]);

        return back()->with('success', "Transfer {$stockTransfer->transfer_code} disetujui.");
    }

    /**
     * menunggu_approval -> ditolak
     */
    public function reject(Request $request, StockTransfer $stockTransfer)
    {
        $this->authorize('reject', $stockTransfer);

        $validated = $request->validate([
            'rejection_reason' => 'required|string|max:1000',
        ]);

        $stockTransfer->update([
            'status'           => 'ditolak',
            'approved_by'      => Auth::id(),
            'approved_at'      => now(),
            'rejection_reason' => $validated['rejection_reason'],
        ]);

        AuditLogger::log('updated', [
            'transfer_code'    => $stockTransfer->transfer_code,
            'status_before'    => 'menunggu_approval',
            'status_after'     => 'ditolak',
            'rejection_reason' => $validated['rejection_reason'],
        ]);

        return back()->with('success', 'Transfer ditolak.');
    }

    /**
     * disetujui -> dikirim
     * Stok fisik keluar dari gudang asal di sini.
     */
    public function ship(StockTransfer $stockTransfer)
    {
        $this->authorize('ship', $stockTransfer);

        DB::transaction(function () use ($stockTransfer) {
            $sourceStock = WarehouseStock::where('barang_id', $stockTransfer->barang_id)
                ->where('warehouse_id', $stockTransfer->from_warehouse_id)
                ->lockForUpdate()
                ->first();

            if (!$sourceStock || $sourceStock->stock_quantity < $stockTransfer->quantity) {
                throw ValidationException::withMessages([
                    'quantity' => 'Stok gudang asal sudah tidak cukup untuk mengirim transfer ini.',
                ]);
            }

            $oldQty = $sourceStock->stock_quantity;
            $sourceStock->decrement('stock_quantity', $stockTransfer->quantity);

            $stockTransfer->update([
                'status'      => 'dikirim',
                'shipped_by'  => Auth::id(),
                'shipped_at'  => now(),
            ]);

            AuditLogger::log('updated', [
                'transfer_code'      => $stockTransfer->transfer_code,
                'status_before'      => 'disetujui',
                'status_after'       => 'dikirim',
                'from_warehouse'     => $stockTransfer->fromWarehouse->name,
                'stock_qty_before'   => $oldQty,
                'stock_qty_after'    => $sourceStock->stock_quantity,
            ]);
        });

        return back()->with('success', "Transfer {$stockTransfer->transfer_code} dikirim. Stok gudang asal berkurang.");
    }

    /**
     * dikirim -> diterima
     * Stok fisik masuk ke gudang tujuan di sini.
     */
    public function receive(StockTransfer $stockTransfer)
    {
        $this->authorize('receive', $stockTransfer);

        DB::transaction(function () use ($stockTransfer) {
            $destStock = WarehouseStock::firstOrCreate(
                [
                    'barang_id'    => $stockTransfer->barang_id,
                    'warehouse_id' => $stockTransfer->to_warehouse_id,
                ],
                ['stock_quantity' => 0]
            );

            $oldQty = $destStock->stock_quantity;
            $destStock->increment('stock_quantity', $stockTransfer->quantity);

            $stockTransfer->update([
                'status'      => 'diterima',
                'received_by' => Auth::id(),
                'received_at' => now(),
            ]);

            AuditLogger::log('updated', [
                'transfer_code'    => $stockTransfer->transfer_code,
                'status_before'    => 'dikirim',
                'status_after'     => 'diterima',
                'to_warehouse'     => $stockTransfer->toWarehouse->name,
                'stock_qty_before' => $oldQty,
                'stock_qty_after'  => $destStock->stock_quantity,
            ]);
        });

        return back()->with('success', "Transfer {$stockTransfer->transfer_code} diterima. Stok gudang tujuan bertambah.");
    }

    /**
     * dikirim -> ditolak (barang sudah dikirim tapi ditolak saat sampai,
     * misal rusak/salah jumlah). Stok yang sudah keluar dari gudang asal
     * dikembalikan lagi karena barangnya balik / tidak jadi masuk sistem.
     */
    public function rejectDelivery(Request $request, StockTransfer $stockTransfer)
    {
        $this->authorize('rejectDelivery', $stockTransfer);

        $validated = $request->validate([
            'rejection_reason' => 'required|string|max:1000',
        ]);

        DB::transaction(function () use ($stockTransfer, $validated) {
            $sourceStock = WarehouseStock::where('barang_id', $stockTransfer->barang_id)
                ->where('warehouse_id', $stockTransfer->from_warehouse_id)
                ->lockForUpdate()
                ->first();

            if ($sourceStock) {
                $sourceStock->increment('stock_quantity', $stockTransfer->quantity);
            }

            $stockTransfer->update([
                'status'           => 'ditolak',
                'received_by'      => Auth::id(),
                'received_at'      => now(),
                'rejection_reason' => $validated['rejection_reason'],
            ]);

            AuditLogger::log('updated', [
                'transfer_code'    => $stockTransfer->transfer_code,
                'status_before'    => 'dikirim',
                'status_after'     => 'ditolak',
                'rejection_reason' => $validated['rejection_reason'],
                'note'             => 'Stok dikembalikan ke gudang asal',
            ]);
        });

        return back()->with('success', 'Pengiriman ditolak, stok dikembalikan ke gudang asal.');
    }
}
