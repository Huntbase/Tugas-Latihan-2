@extends('layout.master')

@section('konten')
<div class="container py-3">
    <h3 class="mb-4">Assign User ke Warehouse</h3>

    @if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form action="{{ route('warehouse-assignments.store') }}" method="POST" class="row g-3 align-items-end">
                @csrf
                <div class="col-md-5">
                    <label class="form-label">User (Supervisor / Staff)</label>
                    <select name="user_id" class="form-select" required>
                        <option value="">-- Pilih user --</option>
                        @foreach ($users as $u)
                        <option value="{{ $u->user_id }}">{{ $u->user_name }} ({{ \App\Models\User::roleMap()[$u->role_id] ?? '-' }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-5">
                    <label class="form-label">Warehouse</label>
                    <select name="warehouse_id" class="form-select" required>
                        <option value="">-- Pilih warehouse --</option>
                        @foreach ($warehouses as $w)
                        <option value="{{ $w->warehouse_id }}">{{ $w->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-plus-circle"></i> Assign
                    </button>
                </div>
            </form>
        </div>
    </div>

    <table class="table table-bordered table-striped align-middle">
        <thead>
            <tr>
                <th>User</th>
                <th>Role</th>
                <th>Warehouse</th>
                <th class="text-end">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($assignments as $a)
            <tr>
                <td>{{ $a->user->user_name ?? '-' }}</td>
                <td>{{ \App\Models\User::roleMap()[$a->user->role_id ?? null] ?? '-' }}</td>
                <td>{{ $a->warehouse->name ?? '-' }}</td>
                <td class="text-end">
                    <form action="{{ route('warehouse-assignments.destroy', $a) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Hapus assignment ini?')">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="text-center">Belum ada assignment.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection