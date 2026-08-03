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
            if (empty($changes)) return;

            $before = array_intersect_key($model->getOriginal(), $changes);
            $after  = $changes;

            $after['user_name'] = $model->user_name ?? null;

            // Jangan pernah simpan hash password ke audit log, walau
            // cuma buat ditampilkan sebagai [HIDDEN] di view - lebih
            // aman dibuang dari sumbernya daripada cuma disamarkan
            // saat ditampilkan.
            unset($before['password'], $after['password']);

            $description = json_encode([
                'before' => $before,
                'after'  => $after,
            ]);
        } elseif ($action === 'deleted') {
            $attributes = $model->getOriginal();
            unset($attributes['password']);

            $description = json_encode($attributes);
        } else {
            $attributes = $model->getAttributes();
            unset($attributes['password']);

            $description = json_encode($attributes);
        }

        AuditLogUser::create([
            'user_id'    => Auth::user()?->user_id,
            'user_name'  => Auth::user()?->user_name,
            'action'     => $action,
            'description' => $description,
        ]);
    }
}
