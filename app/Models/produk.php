<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Auditable;

class Produk extends Model
{
    use Auditable;

    // inisialisasi table produk
    protected $table = 'produk';

    // inisialisasi primary key di dalam table
    protected $primaryKey = 'barang_id';
    public $incrementing = true;
    protected $keyType = 'int';

    // inisialisasi data yang dapat kita isi
    protected $fillable = ['nama_barang', 'category', 'unit'];

    // inisialisasi data yang tidak dapat kita isi
    // protected $guarded = [''];
    public function stocks()
    {
        return $this->hasMany(WarehouseStock::class, 'barang_id', 'barang_id');
    }

    public function movedItems()
    {
        return $this->hasMany(BarangDipindah::class, 'barang_id', 'barang_id');
    }
}
