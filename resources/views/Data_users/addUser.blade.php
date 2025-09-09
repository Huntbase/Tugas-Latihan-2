@extends('layout.master')

@section('konten')
<style>
    .form-control,
    .form-select {
        background-color: var(--sidebar-color);
        color: var(--text-color);
        border: 1px solid var(--toggle-color);
        transition: var(--trans-03);
        font-family: 'Poppins', sans-serif;
    }

    .form-control:focus,
    .form-select:focus {
        background-color: var(--primary-color-light);
        color: var(--text-color);
        border-color: var(--primary-color);
        box-shadow: 0 0 0 0.2rem rgba(105, 92, 254, 0.25);
    }

    .card .card-header {
        font-family: 600;
    }

    .btn {
        font-family: 'Poppins', sans-serif;
        font-weight: 500;
        border-radius: 8px;
        padding: 8px 16px;
        transition: var(--trans-03);
    }
</style>
<div class="card">
    <div class="card-header fw-semibold">Tambah produk</div>
    <div class="card-body">
        <form action="/Data_users" method="POST">
            @csrf
            <div class="row">
                <div class="row-sm-6">
                    <div class="mb-3">
                        <label class="form-label">Nama Username</label>
                        <input type="text" name="user_name" class="form-control" value="{{ old('user_name') }}">
                        @error('user_name')
                        <div id="emailHelp" class="form-text text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="row-sm-6">
                    <div class="mb-3">
                        <label class="form-label">Role</label>
                        <select name="role_id" class="form-select">
                            <option value="">-- Pilih Role --</option>
                            @foreach ($roles as $role)
                            <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                {{ $role->role_name }}
                            </option>
                            @endforeach
                        </select>
                        @error('role_id')
                        <div class="form-text text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="row-sm-6">
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" value="{{ old('password') }}">
                        @error('password')
                        <div class="form-text text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-sm-12 mt-3">
                    <button type="submit" class="btn btn-primary">Tambah Data</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection