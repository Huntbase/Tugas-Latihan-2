@extends('layout.master')

@section('konten')
<div class="card">
    <div class="card-header">tambah data produk</div>
    <div class="card-body">
        <form action="/produk" method="POST">
            @csrf
            <div class="row">
                <div class="row-sm-6">
                    <div class="mb-3">
                        <label class="form-label">Nama Barang</label>
                        <input type="text" name="nama_barang" class="form-control" value="{{old('nama_barang')}}">
                        @error('nama_barang')
                        <div id="emailHelp" class="form-text text-danger">{{$message}}</div>
                        @enderror
                    </div>
                </div>
                <div class="row-sm-6">
                    <div class="mb-3">
                        <label class="form-label">Category</label>
                        <select name="category" class="form-control">
                            <option value="">-- Pilih Category --</option>
                            <option value="Makanan" {{ old('category') == 'Makanan' ? 'selected' : '' }}>Makanan</option>
                            <option value="Minuman" {{ old('category') == 'Minuman' ? 'selected' : '' }}>Minuman</option>
                            <option value="Elektronik" {{ old('category') == 'Elektronik' ? 'selected' : '' }}>Elektronik</option>
                            <option value="Alat Rumah Tangga" {{ old('category') == 'Alat Rumah Tangga' ? 'selected' : '' }}>Alat Rumah Tangga</option>
                        </select>
                        @error('category')
                        <div id="emailHelp" class="form-text text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="row-sm-6">
                    <div class="mb-3">
                        <label class="form-label">Unit</label>
                        <input type="number" name="unit" class="form-control" value="{{old('unit')}}">
                        @error('unit')
                        <div id="emailHelp" class="form-text text-danger">{{$message}}</div>
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