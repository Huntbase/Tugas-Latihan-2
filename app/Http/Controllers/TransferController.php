<?php

namespace App\Http\Controllers;

use App\Models\Transfer;
use App\Models\WarehouseStock;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class TransferController extends Controller
{
    public function approve($id)
    {
        $transfer = Transfer::findOrFail($id);

        if ($transfer->status !== 'pending') {
            return back()->with('error', 'Transfer tidak bisa di-approve karena statusnya bukan pending!');
        }
        $transfer->status = 'approved';
        $transfer->approved_at = now();
        $transfer->save();

        return back()->with('pesan', 'Transfer sudah disetujui!');
    }

    public function setInTransit($id)
    {
        $transfer = Transfer::findOrFail($id);
        $transfer->status = 'in_transit';
        $transfer->in_transit_at = now();
        $transfer->save();

        return back()->with('pesan', 'Transfer sedang dalam perjalanan!');
    }

    public function complete($id)
    {
        DB::transaction(function () use ($id) {
            $transfer = Transfer::with('items')->findOrFail($id);

            foreach ($transfer->items as $item) {
                // Kurangi stok di gudang asal
                WarehouseStock::where('warehouse_id', $transfer->dari_warehouse_id)
                    ->where('barang_id', $item->barang_id)
                    ->decrement('stock_quantity', $item->quantity);

                // Tambah stok ke gudang tujuan
                WarehouseStock::updateOrCreate(
                    ['warehouse_id' => $transfer->ke_warehouse_id, 'barang_id' => $item->barang_id],
                    ['stock_quantity' => DB::raw('stock_quantity + ' . $item->quantity)]
                );
            }

            $transfer->status = 'completed';
            $transfer->completed_at = now();
            $transfer->save();
        });

        return back()->with('pesan', 'Transfer berhasil diselesaikan!');
    }

    public function reject($id)
    {
        $transfer = Transfer::findOrFail($id);

        if ($transfer->status !== 'pending') {
            return back()->with('error', 'Transfer tidak bisa ditolak karena statusnya bukan pending!');
        }

        $transfer->status = 'rejected';
        $transfer->rejected_at = now();
        $transfer->save();

        return back()->with('pesan', 'Transfer ditolak!');
    }
}
