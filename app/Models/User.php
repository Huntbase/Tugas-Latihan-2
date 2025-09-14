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

    public function getAuthIdentifierName()
    {
        return 'user_name';
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id', 'id');
    }

    public static function roleMap()
    {
        return [
            1 => 'Admin',
            2 => 'Staff',
            3 => 'Supervisor',
        ];
    }

    public function transfers()
    {
        return $this->hasMany(Transfer::class, 'user_id', 'user_id');
    }
}
