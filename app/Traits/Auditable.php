<?php

namespace App\Traits;

use App\Models\AuditLogUser;
use Illuminate\Support\Facades\Auth;

trait Auditable
{
    public static function bootAuditable()
    {
        static::created(function ($model) {
            self::log('created', $model);
        });

        static::updated(function ($model) {
            self::log('updated', $model);
        });

        static::deleted(function ($model) {
            self::log('deleted', $model);
        });
    }

    protected static function log($action, $model)
    {
        if ($action === 'updated') {
            $changes = $model->getDirty();
            if (empty($changes)) return; // tidak ada perubahan
            $description = json_encode($changes);
        } elseif ($action === 'deleted') {
            // Ambil semua atribut sebelum dihapus
            $description = json_encode($model->getOriginal());
        } else {
            $description = json_encode($model->getAttributes());
        }

        AuditLogUser::create([
            'user_name'  => Auth::user()?->user_name,
            'action'     => $action,
            'description' => $description,
        ]);
    }
}
