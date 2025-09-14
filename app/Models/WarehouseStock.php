<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Auditable;

class WarehouseStock extends Model
{
    use Auditable;

    protected $table = 'warehouses_stocks';
    protected $primaryKey = 'ware_stock_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'warehouse_id',
        'barang_id',
        'stock_quantity',
    ];

    // Relasi ke gudang
    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id', 'warehouse_id');
    }

    // Relasi ke produk
    public function product()
    {
        return $this->belongsTo(Produk::class, 'barang_id', 'barang_id');
    }
}
