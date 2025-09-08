<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Http\Controllers\Alert;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('role')->get();
        $roles = Role::all();
        return view('Data_users.index', compact('users', 'roles'));
    }

    public function updateRole(Request $request)
    {
        $request->validate([
            'role_id' => 'required|exists:roles,id',
            'user_id' => 'required|exists:m_users,user_id'
        ]);

        $user = User::findOrFail($request->user_id);
        $user->role_id = $request->role_id;
        $user->save();

        return redirect()->route('Data_users.index')->with('pesan', 'Role user berhasil diganti!');
    }
}
