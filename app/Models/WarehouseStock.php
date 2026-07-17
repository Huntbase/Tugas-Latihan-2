<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WarehouseStock extends Model
{
    use HasFactory;

    protected $table = 'warehouse_stocks';
    protected $primaryKey = 'ware_stock_id';

    protected $fillable = [
        'warehouse_id',
        'barang_id',
        'stock_quantity',
    ];

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id', 'warehouse_id');
    }

    public function barang()
    {
        return $this->belongsTo(Produk::class, 'barang_id', 'barang_id');
    }

    // Alias for existing code in the app that already calls ->produk() / ->with('produk')
    public function produk()
    {
        return $this->belongsTo(Produk::class, 'barang_id', 'barang_id');
    }
}
