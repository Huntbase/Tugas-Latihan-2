<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockTransfer extends Model
{
    use HasFactory;

    protected $fillable = [
        'transfer_code',
        'barang_id',
        'from_warehouse_id',
        'to_warehouse_id',
        'quantity',
        'status',
        'requested_by',
        'approved_by',
        'approved_at',
        'notes',
        'rejection_reason',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
        'quantity'    => 'integer',
    ];

    // ----- Relationships -----

    // produk PK is barang_id
    public function barang()
    {
        return $this->belongsTo(Produk::class, 'barang_id', 'barang_id');
    }

    // warehouses PK is warehouse_id
    public function fromWarehouse()
    {
        return $this->belongsTo(Warehouse::class, 'from_warehouse_id', 'warehouse_id');
    }

    public function toWarehouse()
    {
        return $this->belongsTo(Warehouse::class, 'to_warehouse_id', 'warehouse_id');
    }

    // users PK is user_id
    public function requester()
    {
        return $this->belongsTo(User::class, 'requested_by', 'user_id');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by', 'user_id');
    }

    // ----- Scopes -----

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    // ----- Helpers -----

    public static function generateTransferCode(): string
    {
        $today = now()->format('Ymd');
        $count = self::whereDate('created_at', now()->toDateString())->count() + 1;

        return 'TRF-' . $today . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);
    }
}
