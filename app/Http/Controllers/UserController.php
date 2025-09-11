<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Http\Controllers\Alert;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $roles = Role::all();
        $search = $request->keyword;

        $users = User::with('role')
            ->when($search, function ($query, $search) {
                return $query->where('user_name', 'like', "%{$search}%");
            })
            ->get();

        return view('pages.Data_users.index', compact('users', 'roles'));
    }

    public function create()
    {
        $roles = Role::all();

        return view('pages.Data_users.addUser', compact('roles'));
    }

    public function show($user_id)
    {
        // perintah untuk mengambil data 
        $user = User::findOrFail($user_id);

        return view('pages.Data_users.detail', compact('user'));
    }
    public function store(Request $request)
    {
        // validasi
        $request->validate([
            'user_name' => 'required|unique:m_users,user_name',
            'role_id' => 'required',
            'password' => 'required|min:6',
        ], [
            'user_name.required' => 'Nama User wajib diisi!',
            'user_name.unique'   => 'Nama User sudah digunakan!',
            'role_id.required'   => 'Role wajib diisi!',
            'password.required'  => 'Password wajib diisi!',
            'password.min'       => 'Password minimal 6 karakter!',
        ]);

        // untuk menambah data ke tb_produk
        // query tambah data
        User::create([
            'user_name' => $request->user_name,
            'role_id' => $request->role_id,
            'password'  => bcrypt($request->password),
        ]);

        // setelah data berhasil di tambah, akan mengarahkan ke halaman /produk dan memberikan notif menambahkan data
        return redirect('/Data_users')->with('pesan', 'berhasil menambahkan data');
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

    public function destroy($id)
    {
        // query untuk menghapus data di database
        User::findOrFail($id)->delete();
        return redirect('/Data_users')->with('pesan', 'data berhasil di hapus');
    }
}
