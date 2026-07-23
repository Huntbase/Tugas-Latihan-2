<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Models\Warehouse;
use App\Http\Controllers\Alert;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $roles = Role::all();
        $search = $request->keyword;

        $users = User::with(['role', 'warehouses'])
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
        $user = User::findOrFail($user_id);

        return view('pages.Data_users.detail', compact('user'));
    }

    public function store(Request $request)
    {
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

        User::create([
            'user_name' => $request->user_name,
            'role_id' => $request->role_id,
            'password'  => bcrypt($request->password),
        ]);

        return redirect('/Data_users')->with('pesan', 'berhasil menambahkan data');
    }

    /**
     * Tampilkan form edit lengkap (nama, role, password opsional).
     */
    public function edit($user_id)
    {
        $user = User::findOrFail($user_id);
        $roles = Role::all();
        $warehouses = Warehouse::orderBy('name')->get();

        // ID gudang yang sudah ditugaskan ke user ini - dipakai buat
        // nge-centang checkbox yang sesuai di form
        $assignedWarehouseIds = $user->warehouses()->pluck('warehouses.warehouse_id')->toArray();

        return view('pages.Data_users.edit', compact('user', 'roles', 'warehouses', 'assignedWarehouseIds'));
    }

    /**
     * Simpan perubahan dari form edit.
     * Password hanya diganti kalau field-nya diisi - dikosongkan
     * berarti password lama tetap dipakai.
     */
    public function update(Request $request, $user_id)
    {
        $user = User::findOrFail($user_id);

        $request->validate([
            'user_name' => 'required|unique:m_users,user_name,' . $user->user_id . ',user_id',
            'role_id'   => 'required|exists:roles,id',
            'password'  => 'nullable|min:6',
        ], [
            'user_name.required' => 'Nama User wajib diisi!',
            'user_name.unique'   => 'Nama User sudah digunakan!',
            'role_id.required'   => 'Role wajib diisi!',
            'password.min'       => 'Password minimal 6 karakter!',
        ]);

        $user->user_name = $request->user_name;
        $user->role_id = $request->role_id;

        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
        }

        $user->save();

        // Sinkronkan gudang yang ditugaskan - sync() otomatis handle
        // tambah (checkbox baru dicentang), hapus (checkbox di-uncheck),
        // dan biarkan tetap (checkbox tidak berubah). Ini juga yang
        // dipakai untuk "memindahkan" staff antar gudang: uncheck gudang
        // lama, check gudang baru, simpan.
        $user->warehouses()->sync($request->input('warehouse_ids', []));

        return redirect()->route('Data_users.index')->with('pesan', 'Data user berhasil diperbarui!');
    }

    /**
     * Dipertahankan untuk kompatibilitas kalau masih ada tempat lain yang
     * memanggil ganti role cepat tanpa lewat halaman edit penuh.
     */
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
        User::findOrFail($id)->delete();
        return redirect('/Data_users')->with('pesan', 'data berhasil di hapus');
    }
}
