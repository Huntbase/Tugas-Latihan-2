<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransferItem extends Model
{
    protected $table = 'transfer_items';
    protected $primaryKey = 'transfer_item_id';

    protected $fillable = [
        'transfer_id',
        'barang_id',
        'quantity',
    ];

    public function transfer()
    {
        return $this->belongsTo(Transfer::class, 'transfer_id', 'transfer_id');
    }

    public function product()
    {
        return $this->belongsTo(Produk::class, 'barang_id', 'barang_id');
    }
}
