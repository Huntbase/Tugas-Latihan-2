<?php

namespace App\Http\Controllers;

use App\Models\AuditLogUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuditLogController extends Controller
{
    // Role IDs sesuai tabel roles: 1=Admin, 2=Supervisor, 3=Staff
    private const ADMIN = 1;
    private const SUPERVISOR = 2;
    private const STAFF = 3;

    public function index(Request $request)
    {
        $currentUser = Auth::user();

        // Staff tidak punya akses ke halaman ini sama sekali - audit log
        // adalah alat pengawasan manajerial (Admin & Supervisor), bukan
        // domain eksekusi fisik yang jadi porsi Staff.
        abort_if($currentUser->role_id === self::STAFF, 403);

        $logs = AuditLogUser::with('user')
            ->when($currentUser->role_id === self::SUPERVISOR, function ($query) use ($currentUser) {
                // Supervisor cuma boleh lihat:
                // 1. Log aksinya sendiri
                // 2. Log aksi Staff yang bekerja di gudang yang dia awasi
                $supervisedWarehouseIds = $currentUser->warehouseAssignments()
                    ->pluck('warehouse_id');

                $staffIdsInMyWarehouses = User::where('role_id', self::STAFF)
                    ->whereHas('warehouseAssignments', function ($q) use ($supervisedWarehouseIds) {
                        $q->whereIn('warehouse_id', $supervisedWarehouseIds);
                    })
                    ->pluck('user_id');

                $query->where(function ($q) use ($currentUser, $staffIdsInMyWarehouses) {
                    $q->where('user_id', $currentUser->user_id)
                        ->orWhereIn('user_id', $staffIdsInMyWarehouses);
                });
            })
            // Admin tidak kena filter apapun - lihat semua log
            ->when($request->user, function ($query, $user) {
                $query->whereHas('user', function ($q) use ($user) {
                    $q->where('user_name', 'like', "%{$user}%");
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
