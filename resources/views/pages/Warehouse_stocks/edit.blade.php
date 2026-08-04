@extends('layout.master')

@section('konten')
<div class="card" style="max-width: 560px;">
    <div class="card-header fw-semibold">Update Stok</div>
    <div class="card-body">
        <p class="text-muted mb-3">Produk: <strong>{{ $stock->produk->nama_barang ?? '-' }}</strong></p>

        <form action="{{ route('warehouseStocks.update', $stock->ware_stock_id) }}" method="POST">
            @method('PUT')
            @csrf
            <div class="mb-3">
                <label class="form-label">Jumlah Stok</label>
                <input type="number" name="stock_quantity" class="form-control" min="0"
                    value="{{ old('stock_quantity', $stock->stock_quantity) }}" required>
                @error('stock_quantity')
                <div class="form-text text-danger">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">Update Data</button>
            <a href="{{ route('warehouseStocks.index') }}" class="btn btn-outline-secondary">Batal</a>
        </form>
    </div>
</div>
@endsection