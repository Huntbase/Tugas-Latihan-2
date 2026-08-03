@extends('layout.master')

@section('konten')
<style>
    .card {
        background-color: var(--sidebar-color);
        color: var(--text-color);
        border-radius: 10px;
        transition: var(--trans-03);
    }

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

    .btn {
        font-family: 'Poppins', sans-serif;
        border-radius: 8px;
        transition: var(--trans-03);
    }
</style>
<div class="card">
    <div class="card-header">Update Data Warehouse</div>
    <div class="card-body">
        <form action="{{ route('warehouses.update', $data->warehouse_id) }}" method="POST">
            @method('PUT')
            @csrf
            <div class="row">
                <div class="col-sm-6">
                    <div class="mb-3">
                        <label class="form-label">Nama Warehouse</label>
                        <input type="text" name="name" class="form-control"
                            value="{{ old('name', $data->name) }}">
                        @error('name')
                        <div class="form-text text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-sm-6">
                    <div class="mb-3">
                        <label class="form-label">Lokasi</label>
                        <input type="text" name="location" class="form-control"
                            value="{{ old('location', $data->location) }}">
                        @error('location')
                        <div class="form-text text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-sm-12">
                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="description" class="form-control" rows="3">{{ old('description', $data->description) }}</textarea>
                        @error('description')
                        <div class="form-text text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-sm-12 mt-3">
                    <button type="submit" class="btn btn-primary">Update Data</button>
                    <a href="{{ route('warehouse.index') }}" class="btn btn-outline-secondary">Batal</a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection