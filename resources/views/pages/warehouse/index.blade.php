@extends('layout.master')

@section('konten')
<h1 class="mb-4">Warehouse</h1>

@if(auth()->user()->role_id === 1)
<a href="{{ route('warehouse.create') }}" class="btn btn-primary mb-3">Tambah Warehouse</a>
@endif

@if (session('pesan'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('pesan') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Daftar Warehouse</h5>
        <div class="d-flex gap-2">
            @if (Request()->keyword != '')
            <a href="{{ route('warehouses.index') }}" class="btn btn-info">Reset</a>
            @endif
            <form class="input-group" style="width: 350px;">
                <input
                    type="text"
                    class="form-control"
                    value="{{ Request()->keyword }}"
                    placeholder="Cari warehouse"
                    name="keyword">
                <button class="btn btn-success" type="submit">
                    Cari
                </button>
            </form>
        </div>
    </div>

    <div class="card-body">
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Warehouse</th>
                    <th>Lokasi</th>
                    <th>Deskripsi</th>
                    <th>Dibuat</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($warehouses as $warehouse)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $warehouse->name }}</td>
                    <td>{{ $warehouse->location }}</td>
                    <td>{{ $warehouse->description ?? '-' }}</td>
                    <td>{{ $warehouse->created_at->format('d M Y') }}</td>
                    <td class="text-center">
                        <div class="d-flex justify-content-center gap-2">
                            @if(auth()->user()->role_id === 1)
                            <a href="{{ route('warehouses.edit', $warehouse->warehouse_id) }}" class="btn btn-warning btn-sm">Edit</a>
                            <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#hapus{{ $warehouse->warehouse_id }}">
                                Hapus
                            </button>
                            @endif
                            <a href="{{ route('warehouses.show', $warehouse->warehouse_id) }}" class="btn btn-info btn-sm">Detail</a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center">Data warehouse tidak ditemukan!</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@if(auth()->user()->role_id === 1)
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
                Apakah Anda yakin ingin menghapus <strong>{{ $warehouse->name }}</strong>?
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