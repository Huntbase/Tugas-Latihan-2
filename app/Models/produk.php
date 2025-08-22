<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    // inisialisasi table produk
    protected $table = 'produk';

    // inisialisasi primary key di dalam table
    protected $primaryKey = 'barang_id';
    public $incrementing = true;
    protected $keyType = 'int';

    // inisialisasi data yang dapat kita isi
    protected $fillable = ['nama_barang', 'category', 'unit', 'price'];

    // inisialisasi data yang tidak dapat kita isi
    // protected $guarded = [''];
}
