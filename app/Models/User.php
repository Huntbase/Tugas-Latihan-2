<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Traits\Auditable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, Auditable;

    protected $table = 'm_users';
    protected $primaryKey = 'user_id';

    protected $fillable = [
        'user_name',
        'password',
        'role_id',
    ];

    protected $hidden = [
        'password',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id', 'id');
    }

    public static function roleMap()
    {
        return [
            1 => 'Admin',
            2 => 'Supervisor',
            3 => 'Staff',
        ];
    }

    // Transfer yang diminta (dibuat) oleh user ini
    public function transfers()
    {
        return $this->hasMany(StockTransfer::class, 'requested_by', 'user_id');
    }

    public function warehouseAssignments()
    {
        return $this->hasMany(UserWarehouseAssignment::class, 'user_id', 'user_id');
    }

    public function warehouses()
    {
        return $this->belongsToMany(
            Warehouse::class,
            'user_warehouse_assignments',
            'user_id',
            'warehouse_id',
            'user_id',
            'warehouse_id'
        );
    }
}
