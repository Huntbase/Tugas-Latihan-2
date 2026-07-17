<?php

namespace App\Helpers;

use App\Models\AuditLogUser;
use Illuminate\Support\Facades\Auth;

class AuditLogger
{
    /**
     * Write one row to audit_log_user, matching the app's real schema.
     *
     * $action must be 'created', 'updated', or 'deleted' to match the
     * badge/icon logic already in resources/views/pages/audit/auditLog.blade.php.
     *
     * $description is stored as flat key => value JSON. It intentionally does
     * NOT use a 'before'/'after' wrapper (that shape is reserved in the view
     * for user-role-change entries) so it renders through the generic
     * foreach branch in the audit log view.
     */
    public static function log(string $action, array $description): void
    {
        $user = Auth::user();

        AuditLogUser::create([
            'user_id'     => $user?->user_id,
            'user_name'   => $user?->user_name ?? 'System',
            'action'      => $action,
            'description' => json_encode($description),
        ]);
    }
}
