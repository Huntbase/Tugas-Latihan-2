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

            $description = json_encode([
                'before' => $before,
                'after'  => $after,
            ]);
        } elseif ($action === 'deleted') {
            $description = json_encode($model->getOriginal());
        } else {
            $description = json_encode($model->getAttributes());
        }

        AuditLogUser::create([
            'user_id'    => Auth::user()?->user_id,
            'user_name'  => Auth::user()?->user_name,
            'action'     => $action,
            'description' => $description,
        ]);
    }
}
