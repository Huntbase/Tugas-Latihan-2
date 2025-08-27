<?php

namespace App\Http\Controllers;

use App\Models\AuditLogUser;
use Illuminate\Http\Request;


class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        $logs = AuditLogUser::with('user')
            ->when($request->user, function ($query, $user) {
                $query->whereHas('user', function ($q) use ($user) {
                    $q->where('name', 'like', "%{$user}%");
                });
            })
            ->when($request->start_date, function ($query, $start) {
                $query->whereDate('created_at', '>=', $start);
            })
            ->when($request->end_date, function ($query, $end) {
                $query->whereDate('created_at', '<=', $end);
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('pages.audit.auditLog', compact('logs'));
    }
}
