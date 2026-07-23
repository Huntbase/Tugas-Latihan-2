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
        'shipped_by',
        'received_by',
        'approved_at',
        'shipped_at',
        'received_at',
        'notes',
        'rejection_reason',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
        'shipped_at'  => 'datetime',
        'received_at' => 'datetime',
        'quantity'    => 'integer',
    ];

    // ----- Relationships -----

    public function barang()
    {
        return $this->belongsTo(Produk::class, 'barang_id', 'barang_id');
    }

    public function fromWarehouse()
    {
        return $this->belongsTo(Warehouse::class, 'from_warehouse_id', 'warehouse_id');
    }

    public function toWarehouse()
    {
        return $this->belongsTo(Warehouse::class, 'to_warehouse_id', 'warehouse_id');
    }

    public function requester()
    {
        return $this->belongsTo(User::class, 'requested_by', 'user_id');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by', 'user_id');
    }

    public function shipper()
    {
        return $this->belongsTo(User::class, 'shipped_by', 'user_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'received_by', 'user_id');
    }

    // ----- Scopes (mengikuti state machine baru) -----

    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    public function scopeMenungguApproval($query)
    {
        return $query->where('status', 'menunggu_approval');
    }

    public function scopeDisetujui($query)
    {
        return $query->where('status', 'disetujui');
    }

    public function scopeDikirim($query)
    {
        return $query->where('status', 'dikirim');
    }

    public function scopeDiterima($query)
    {
        return $query->where('status', 'diterima');
    }

    public function scopeDitolak($query)
    {
        return $query->where('status', 'ditolak');
    }

    // ----- Helpers -----

    public static function generateTransferCode(): string
    {
        $today = now()->format('Ymd');
        $count = self::whereDate('created_at', now()->toDateString())->count() + 1;

        return 'TRF-' . $today . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);
    }
}
