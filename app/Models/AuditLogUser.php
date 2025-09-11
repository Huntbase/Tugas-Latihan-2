<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditLogUser extends Model
{
    protected $table = 'audit_log_user';
    protected $primaryKey = 'audit_log_user_id';

    protected $fillable = [
        'user_id',
        'user_name',
        'action',
        'description',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}
