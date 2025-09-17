<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Auditable;

class Transfer extends Model
{
    use Auditable;

    protected $primaryKey = 'transfer_id';

    protected $fillable = [
        'dari_warehouse_id',
        'ke_warehouse_id',
        'user_id',
        'status',
        'keterangan',
        'approved_at',
        'in_transit_at',
        'completed_at',
        'rejected_at',
    ];

    protected $dates = [
        'approved_at',
        'in_transit_at',
        'completed_at',
        'rejected_at',
    ];

    // Relasi ke gudang asal
    public function fromWarehouse()
    {
        return $this->belongsTo(Warehouse::class, 'dari_warehouse_id', 'warehouse_id');
    }

    // Relasi ke gudang tujuan
    public function toWarehouse()
    {
        return $this->belongsTo(Warehouse::class, 'ke_warehouse_id', 'warehouse_id');
    }

    // Relasi ke user yang request transfer
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function items()
    {
        return $this->hasMany(TransferItem::class, 'transfer_id', 'transfer_id');
    }

    // ✅ Status helper
    public function isPending()
    {
        return $this->status === 'pending';
    }
    public function isApproved()
    {
        return $this->status === 'approved';
    }
    public function isTransit()
    {
        return $this->status === 'in_transit';
    }
    public function isCompleted()
    {
        return $this->status === 'completed';
    }
}
