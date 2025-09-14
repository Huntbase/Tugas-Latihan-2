<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Auditable;

class Transfer extends Model
{
    use Auditable;

    protected $table = 'transfers';
    protected $primaryKey = 'transfers_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'dari_warehouse_id',
        'ke_warehouse_id',
        'user_id',
        'status',
        'approved_by',
        'tanggal_diapproved',
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
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    // Relasi ke barang yang dipindahkan
    public function items()
    {
        return $this->hasMany(BarangDipindah::class, 'transfer_id', 'transfers_id');
    }
}
