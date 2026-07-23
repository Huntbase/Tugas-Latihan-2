<?php

namespace App\Http\Controllers;

use App\Helpers\AuditLogger;
use App\Models\User;
use App\Models\UserWarehouseAssignment;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WarehouseAssignmentController extends Controller
{
    public function index()
    {
        // Hanya Admin yang boleh buka halaman ini
        abort_unless(Auth::user()->role_id === 1, 403);

        $users = User::whereIn('role_id', [2, 3])->orderBy('user_name')->get();
        $warehouses = Warehouse::orderBy('name')->get();
        $assignments = UserWarehouseAssignment::with(['user', 'warehouse'])->get();

        return view('pages.warehouse_assignments.index', compact('users', 'warehouses', 'assignments'));
    }

    public function store(Request $request)
    {
        abort_unless(Auth::user()->role_id === 1, 403);

        $validated = $request->validate([
            'user_id'      => 'required|exists:m_users,user_id',
            'warehouse_id' => 'required|exists:warehouses,warehouse_id',
        ]);

        $assignment = UserWarehouseAssignment::firstOrCreate($validated);

        AuditLogger::log('created', [
            'user'      => $assignment->user->user_name,
            'warehouse' => $assignment->warehouse->name,
            'action'    => 'Assigned user to warehouse',
        ]);

        return back()->with('success', 'Assignment berhasil ditambahkan.');
    }

    public function destroy(UserWarehouseAssignment $assignment)
    {
        abort_unless(Auth::user()->role_id === 1, 403);

        AuditLogger::log('deleted', [
            'user'      => $assignment->user->user_name,
            'warehouse' => $assignment->warehouse->name,
            'action'    => 'Removed user from warehouse',
        ]);

        $assignment->delete();

        return back()->with('success', 'Assignment dihapus.');
    }
}
