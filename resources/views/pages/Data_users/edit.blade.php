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
    <div class="card-header">Update data produk</div>
    <div class="card-body">
        <form action="/produk/{{ $data->barang_id }}" method="POST">
            @method('PUT')
            @csrf
            <div class="row">
                <div class="col-sm-6">
                    <div class="mb-3">
                        <label class="form-label">Nama Barang</label>
                        <input type="text" name="nama_barang" class="form-control"
                            value="{{ old('nama_barang', $data->nama_barang) }}">
                        @error('nama_barang')
                        <div class="form-text text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-sm-6">
                    <div class="mb-3">
                        <label class="form-label">Category</label>
                        <select name="category" class="form-control">
                            <option value="">-- Pilih Category --</option>
                            <option value="Makanan" {{ old('category', $data->category) == 'Makanan' ? 'selected' : '' }}>Makanan</option>
                            <option value="Minuman" {{ old('category', $data->category) == 'Minuman' ? 'selected' : '' }}>Minuman</option>
                            <option value="Elektronik" {{ old('category', $data->category) == 'Elektronik' ? 'selected' : '' }}>Elektronik</option>
                            <option value="Alat Rumah Tangga" {{ old('category', $data->category) == 'Alat Rumah Tangga' ? 'selected' : '' }}>Alat Rumah Tangga</option>
                        </select>
                        @error('category')
                        <div class="form-text text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-sm-6">
                    <div class="mb-3">
                        <label class="form-label">Unit</label>
                        <input type="number" name="unit" class="form-control"
                            value="{{ old('unit', $data->unit) }}">
                        @error('unit')
                        <div class="form-text text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-sm-12 mt-3">
                    <button type="submit" class="btn btn-primary">Update Data</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection