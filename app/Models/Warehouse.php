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

    // Relasi ke transfer keluar
    public function transfersFrom()
    {
        return $this->hasMany(Transfer::class, 'dari_warehouse_id', 'warehouse_id');
    }

    // Relasi ke transfer masuk
    public function transfersTo()
    {
        return $this->hasMany(Transfer::class, 'ke_warehouse_id', 'warehouse_id');
    }
}
