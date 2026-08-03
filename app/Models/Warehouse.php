<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Auditable;

class Warehouse extends Model
{
    use Auditable;

    protected $table = 'warehouses';
    protected $primaryKey = 'warehouse_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = ['name', 'location', 'description'];

    // Relasi ke stok barang di gudang
    public function stocks()
    {
        return $this->hasMany(WarehouseStock::class, 'warehouse_id', 'warehouse_id');
    }

    // Relasi ke stock transfer yang berasal dari gudang ini
    public function transfersFrom()
    {
        return $this->hasMany(StockTransfer::class, 'from_warehouse_id', 'warehouse_id');
    }

    // Relasi ke stock transfer yang menuju ke gudang ini
    public function transfersTo()
    {
        return $this->hasMany(StockTransfer::class, 'to_warehouse_id', 'warehouse_id');
    }

    public function assignedUsers()
    {
        return $this->belongsToMany(
            User::class,
            'user_warehouse_assignments',
            'warehouse_id',
            'user_id',
            'warehouse_id',
            'user_id'
        );
    }
}
