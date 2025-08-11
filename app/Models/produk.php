<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class produk extends Model
{
    // inisialisasi table produk
    protected $table = 'tb_produk';

    // inisialisasi primary key di dalam table
    protected $primaryKey = 'id_produk';

    // inisialisasi data yang dapat kita isi
    protected $fillable = ['nama_produk', 'category', 'unit', 'price'];

    // inisialisasi data yang tidak dapat kita isi
    // protected $guarded = [''];
}
