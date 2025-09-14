<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Auditable;

class BarangDipindah extends Model
{
    use Auditable;

    protected $table = 'barang_dipindah';
    protected $primaryKey = 'barang_dipindah_id';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false; // karena di migration kamu tidak pakai timestamps()

    protected $fillable = [
        'transfer_id',
        'barang_id',
        'banyaknya',
    ];

    // Relasi ke Transfer (mutasi stok antar gudang)
    public function transfer()
    {
        return $this->belongsTo(Transfer::class, 'transfer_id', 'transfers_id');
    }

    // Relasi ke Produk (barang yang dipindahkan)
    public function product()
    {
        return $this->belongsTo(Produk::class, 'barang_id', 'barang_id');
    }
}
