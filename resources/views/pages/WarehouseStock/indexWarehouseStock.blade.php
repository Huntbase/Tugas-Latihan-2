@extends('layout.master')

@section('konten')
<h1 class="mb-4">Warehouse</h1>

<a href="{{ route('warehouse.create') }}" class="btn btn-primary mb-3">Tambah Warehouse</a>

@if (session('pesan'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('pesan') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Daftar Stok</h5>
    </div>

    <div class="card-body">
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Produk</th>
                    <th>Jumlah Stok</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($stocks as $stock)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $stock->produk->nama_barang ?? '-' }}</td>
                    <td>{{ $stock->stock_quantity }}</td>
                    <td class="text-center">
                        <div class="d-flex justify-content-center gap-2">
                            <a href="{{ route('warehouseStocks.edit', $stock->ware_stock_id) }}" class="btn btn-warning btn-sm">Edit</a>
                            <form action="{{ route('warehouseStocks.destroy', $stock->ware_stock_id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center">Tidak ada stok.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

<!-- Modal Konfirmasi Hapus -->
@foreach ($warehouses as $warehouse)
<div class="modal fade" id="hapus{{ $warehouse->warehouse_id }}" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('warehouses.destroy', $warehouse->warehouse_id) }}" method="POST" class="modal-content">
            @csrf
            @method('DELETE')
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                Apakah Anda yakin ingin menghapus <strong>{{ $warehouse->name_id }}</strong>?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-danger">Hapus Data</button>
            </div>
        </form>
    </div>
</div>
@endforeach
@endsection