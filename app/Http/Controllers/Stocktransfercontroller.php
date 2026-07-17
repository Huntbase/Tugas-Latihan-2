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
        $transfers = StockTransfer::with(['barang', 'fromWarehouse', 'toWarehouse', 'requester', 'approver'])
            ->latest()
            ->paginate(15);

        return view('pages.stock_transfers.showstocktransfers', compact('transfers'));
    }

    public function create()
    {
        $barangList = Produk::orderBy('nama_barang')->get();
        $warehouses = Warehouse::orderBy('name')->get();

        return view('pages.stock_transfers.addStockTransfers', compact('barangList', 'warehouses'));
    }

    /**
     * Step 1: Request a transfer. No stock is moved yet - status = pending.
     * We only validate that enough stock CURRENTLY exists at request time.
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
                'quantity' => 'Not enough stock in the source warehouse. Available: ' . ($sourceStock->stock_quantity ?? 0),
            ]);
        }

        $transfer = DB::transaction(function () use ($validated) {
            $transfer = StockTransfer::create([
                'transfer_code'     => StockTransfer::generateTransferCode(),
                'barang_id'         => $validated['barang_id'],
                'from_warehouse_id' => $validated['from_warehouse_id'],
                'to_warehouse_id'   => $validated['to_warehouse_id'],
                'quantity'          => $validated['quantity'],
                'status'            => 'pending',
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

            return $transfer;
        });

        return redirect()->route('stock-transfers.index')
            ->with('success', "Transfer {$transfer->transfer_code} requested. Awaiting approval.");
    }

    /**
     * Step 2: Approve & receive. This is where stock actually moves.
     * Re-checks stock availability at approval time (it may have changed
     * since the request was made) and does everything in one transaction.
     */
    public function approve(StockTransfer $stockTransfer)
    {
        if ($stockTransfer->status !== 'pending') {
            return back()->with('error', 'This transfer has already been processed.');
        }

        DB::transaction(function () use ($stockTransfer) {
            $sourceStock = WarehouseStock::where('barang_id', $stockTransfer->barang_id)
                ->where('warehouse_id', $stockTransfer->from_warehouse_id)
                ->lockForUpdate()
                ->first();

            if (!$sourceStock || $sourceStock->stock_quantity < $stockTransfer->quantity) {
                throw ValidationException::withMessages([
                    'quantity' => 'Not enough stock remaining in source warehouse to complete this transfer.',
                ]);
            }

            $oldSourceQty = $sourceStock->stock_quantity;

            // Deduct from source
            $sourceStock->decrement('stock_quantity', $stockTransfer->quantity);

            // Add to destination (create row if it doesn't exist yet)
            $destStock = WarehouseStock::firstOrCreate(
                [
                    'barang_id'    => $stockTransfer->barang_id,
                    'warehouse_id' => $stockTransfer->to_warehouse_id,
                ],
                ['stock_quantity' => 0]
            );
            $oldDestQty = $destStock->stock_quantity;
            $destStock->increment('stock_quantity', $stockTransfer->quantity);

            $stockTransfer->update([
                'status'      => 'completed',
                'approved_by' => Auth::id(),
                'approved_at' => now(),
            ]);

            AuditLogger::log('updated', [
                'transfer_code'             => $stockTransfer->transfer_code,
                'status_before'             => 'pending',
                'status_after'              => 'completed',
                'from_warehouse'            => $stockTransfer->fromWarehouse->name,
                'from_warehouse_qty_before' => $oldSourceQty,
                'from_warehouse_qty_after'  => $sourceStock->stock_quantity,
                'to_warehouse'              => $stockTransfer->toWarehouse->name,
                'to_warehouse_qty_before'   => $oldDestQty,
                'to_warehouse_qty_after'    => $destStock->stock_quantity,
            ]);
        });

        return back()->with('success', "Transfer {$stockTransfer->transfer_code} completed. Stock updated.");
    }

    /**
     * Reject a pending transfer. No stock has moved, so nothing to reverse.
     */
    public function reject(Request $request, StockTransfer $stockTransfer)
    {
        if ($stockTransfer->status !== 'pending') {
            return back()->with('error', 'This transfer has already been processed.');
        }

        $validated = $request->validate([
            'rejection_reason' => 'required|string|max:1000',
        ]);

        $stockTransfer->update([
            'status'           => 'rejected',
            'approved_by'      => Auth::id(),
            'approved_at'      => now(),
            'rejection_reason' => $validated['rejection_reason'],
        ]);

        AuditLogger::log('updated', [
            'transfer_code'    => $stockTransfer->transfer_code,
            'status_before'    => 'pending',
            'status_after'     => 'rejected',
            'rejection_reason' => $validated['rejection_reason'],
        ]);

        return back()->with('success', 'Transfer rejected.');
    }

    /**
     * Requester cancels their own pending transfer.
     */
    public function cancel(StockTransfer $stockTransfer)
    {
        if ($stockTransfer->status !== 'pending') {
            return back()->with('error', 'Only pending transfers can be cancelled.');
        }

        if ($stockTransfer->requested_by !== Auth::id()) {
            abort(403);
        }

        $stockTransfer->update(['status' => 'cancelled']);

        AuditLogger::log('updated', [
            'transfer_code' => $stockTransfer->transfer_code,
            'status_before' => 'pending',
            'status_after'  => 'cancelled',
        ]);

        return back()->with('success', 'Transfer cancelled.');
    }
}
