@extends('layout.master')

@section('konten')
<style>
    .edit-user-card {
        border: 1px solid rgba(0, 0, 0, 0.08);
        border-radius: 14px;
        max-width: 560px;
    }

    .edit-user-card .card-header {
        background: transparent;
        border-bottom: 1px solid rgba(0, 0, 0, 0.08);
        padding: 20px 24px;
    }

    .edit-user-card .card-header h3 {
        font-weight: 600;
        font-size: 1.3rem;
        margin: 0;
    }

    .edit-user-card .card-body {
        padding: 24px;
    }

    .form-hint {
        font-size: 12px;
        color: #6c757d;
        margin-top: 4px;
    }
</style>

<div class="edit-user-card card shadow-sm mx-auto">
    <div class="card-header">
        <h3>Edit User</h3>
    </div>

    <div class="card-body">
        @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0 ps-3">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('Data_users.update', $user->user_id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">Nama User</label>
                <input type="text" name="user_name" class="form-control" value="{{ old('user_name', $user->user_name) }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Role</label>
                <select name="role_id" class="form-select" required>
                    @foreach ($roles as $role)
                    <option value="{{ $role->id }}" @selected(old('role_id', $user->role_id) == $role->id)>
                        {{ $role->role_name }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Password Baru</label>
                <input type="password" name="password" class="form-control" placeholder="Kosongkan jika tidak ingin mengganti">
                <div class="form-hint">Biarkan kosong kalau tidak mau mengganti password.</div>
            </div>

            <div class="mb-3">
                <label class="form-label">Ditugaskan ke Warehouse</label>
                <div class="form-hint mb-2">Centang gudang yang boleh diakses user ini. Untuk memindahkan staff, hapus centang gudang lama lalu centang gudang baru.</div>
                <div class="border rounded p-3">
                    @forelse ($warehouses as $w)
                    <div class="form-check">
                        <input
                            class="form-check-input"
                            type="checkbox"
                            name="warehouse_ids[]"
                            value="{{ $w->warehouse_id }}"
                            id="wh{{ $w->warehouse_id }}"
                            @checked(in_array($w->warehouse_id, old('warehouse_ids', $assignedWarehouseIds)))
                        >
                        <label class="form-check-label" for="wh{{ $w->warehouse_id }}">
                            {{ $w->name }}
                        </label>
                    </div>
                    @empty
                    <p class="text-muted mb-0">Belum ada warehouse yang terdaftar.</p>
                    @endforelse
                </div>
            </div>

            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-circle"></i> Simpan Perubahan
                </button>
                <a href="{{ route('Data_users.index') }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection