<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserWarehouseAssignment extends Model
{
    protected $fillable = [
        'user_id',
        'warehouse_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id', 'warehouse_id');
    }
}
