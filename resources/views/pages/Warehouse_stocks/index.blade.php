@extends('layout.master')

@section('konten')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="mb-0">Stok Gudang</h1>
    @if(auth()->user()->role_id !== 3)
    <a href="{{ route('warehouseStocks.create') }}" class="btn btn-primary">+ Tambah Stok</a>
    @endif
</div>

@if (session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Daftar Stok</h5>
    </div>

    <div class="card-body">
        <table class="table table-striped table-bordered align-middle">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Produk</th>
                    <th>Jumlah Stok</th>
                    @if(auth()->user()->role_id !== 3)
                    <th class="text-center">Aksi</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @forelse ($stocks as $stock)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $stock->produk->nama_barang ?? '-' }}</td>
                    <td>{{ $stock->stock_quantity }}</td>
                    @if(auth()->user()->role_id !== 3)
                    <td class="text-center">
                        <div class="d-flex justify-content-center gap-2">
                            <a href="{{ route('warehouseStocks.edit', $stock->ware_stock_id) }}" class="btn btn-warning btn-sm">Edit</a>
                            <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#hapus{{ $stock->ware_stock_id }}">
                                Hapus
                            </button>
                        </div>
                    </td>
                    @endif
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center py-4 text-muted">Belum ada stok di gudang ini.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@if(auth()->user()->role_id !== 3)
<!-- Modal Konfirmasi Hapus -->
@foreach ($stocks as $stock)
<div class="modal fade" id="hapus{{ $stock->ware_stock_id }}" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('warehouseStocks.destroy', $stock->ware_stock_id) }}" method="POST" class="modal-content">
            @csrf
            @method('DELETE')
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                Apakah Anda yakin ingin menghapus stok <strong>{{ $stock->produk->nama_barang ?? '-' }}</strong>?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-danger">Hapus Data</button>
            </div>
        </form>
    </div>
</div>
@endforeach
@endif
@endsection