@extends('layout.master')

@section('konten')
<div class="card" style="max-width: 560px;">
    <div class="card-header fw-semibold">Tambah Stok</div>
    <div class="card-body">
        <form action="{{ route('warehouseStocks.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label">Produk</label>
                <select name="barang_id" class="form-select" required>
                    <option value="">-- Pilih produk --</option>
                    @foreach ($produks as $p)
                    <option value="{{ $p->barang_id }}" @selected(old('barang_id')==$p->barang_id)>{{ $p->nama_barang }}</option>
                    @endforeach
                </select>
                @error('barang_id')
                <div class="form-text text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Jumlah Stok</label>
                <input type="number" name="stock_quantity" class="form-control" min="1" value="{{ old('stock_quantity') }}" required>
                @error('stock_quantity')
                <div class="form-text text-danger">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">Tambah Data</button>
            <a href="{{ route('warehouseStocks.index') }}" class="btn btn-outline-secondary">Batal</a>
        </form>
    </div>
</div>
@endsection