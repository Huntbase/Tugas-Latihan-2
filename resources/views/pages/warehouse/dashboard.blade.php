@extends('layout.master')

@section('konten')
<style>
    .card {
        background-color: var(--sidebar-color);
        color: var(--text-color);
        border: none;
        transition: var(--trans-03);
    }

    .table {
        background-color: var(--sidebar-color);
        color: var(--text-color);
    }

    .modal-content {
        background-color: #fff !important;
        color: #000 !important;
        border: 1px solid var(--primary-color-light);
    }

    .table thead {
        background-color: var(--primary-color-light);
        color: var(--text-color);
    }

    .alert {
        background-color: var(--primary-color-light);
        color: var(--text-color);
        border: 1px solid var(--primary-color);
    }

    .btn-primary {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
    }

    .btn-info,
    .btn-warning,
    .btn-success,
    .btn-danger {
        color: #fff;
    }

    .btn-custom {
        color: black !important;
        /* teks default hitam */
        font-weight: 500;
    }

    .btn-custom2 {
        background-color: #FF2C2C;
        border-color: #FF2C2C;
        color: black;
    }

    .btn-custom2:hover {
        background-color: #e64a19;
        border-color: #e64a19;
        color: white;
    }

    .btn-custom:hover {
        color: white !important;
        /* teks jadi putih saat hover */
    }
</style>
<h1 class="mb-4">Dashboard Warehouse</h1>

<form action="{{ route('warehouse.setActive') }}" method="POST" class="mb-4">
    @csrf
    <div class="mb-3">
        <label for="warehouse_id" class="form-label">Pilih Gudang</label>
        <select name="warehouse_id" id="warehouse_id" class="form-select" onchange="this.form.submit()">
            @foreach($warehouses as $wh)
            <option value="{{ $wh->warehouse_id }}"
                {{ session('active_warehouse_id') == $wh->warehouse_id ? 'selected' : '' }}>
                {{ $wh->name }} - {{ $wh->location }}
            </option>
            @endforeach
        </select>
    </div>
</form>
<p>Selamat datang, <strong>{{ auth()->user()?->user_name ?? 'Guest' }}</strong>!</p>

<div class="card shadow-sm mb-4">
    <div class="card-body">
        <h3 class="card-title mb-3">Gudang Aktif: {{ $warehouse->name }}</h3>
        <p><strong>Lokasi:</strong> {{ $warehouse->location }}</p>
        <p><strong>Deskripsi:</strong> {{ $warehouse->description }}</p>
    </div>
</div>

<h4 class="mb-3">Statistik Stok Barang</h4>
<div class="row g-4 mb-4">
    <!-- Total Barang -->
    <div class="col-md-3 d-flex">
        <div class="card text-white bg-primary shadow-sm flex-fill hover-card">
            <div class="card-body d-flex flex-column justify-content-center align-items-center text-center">
                <i class="bi bi-box-seam" style="font-size: 2.5rem;"></i>
                <h5 class="card-title mt-2">Total Barang</h5>
                <h2>{{ $totalBarang ?? 0 }}</h2>
            </div>
        </div>
    </div>

    <!-- Barang Masih Banyak -->
    <div class="col-md-3 d-flex">
        <div class="card bg-success shadow-sm flex-fill hover-card">
            <div class="card-body d-flex flex-column justify-content-center align-items-center text-center text-white">
                <i class="bi bi-check-circle" style="font-size: 2.5rem;"></i>
                <h5 class="card-title mt-2">Barang Masih Banyak</h5>
                <h2>{{ $stokBanyak ?? 0 }}</h2>
            </div>
        </div>
    </div>

    <!-- Barang Hampir Habis -->
    <div class="col-md-3 d-flex">
        <div class="card bg-warning shadow-sm flex-fill hover-card">
            <div class="card-body d-flex flex-column justify-content-center align-items-center text-center text-dark">
                <i class="bi bi-exclamation-triangle" style="font-size: 2.5rem;"></i>
                <h5 class="card-title mt-2">Barang Hampir Habis</h5>
                <h2>{{ $stokHampirHabis ?? 0 }}</h2>
            </div>
        </div>
    </div>

    <!-- Barang Kosong -->
    <div class="col-md-3 d-flex">
        <div class="card text-white bg-danger shadow-sm flex-fill hover-card">
            <div class="card-body d-flex flex-column justify-content-center align-items-center text-center">
                <i class="bi bi-x-circle" style="font-size: 2.5rem;"></i>
                <h5 class="card-title mt-2">Barang Kosong</h5>
                <h2>{{ $stokKosong ?? 0 }}</h2>
            </div>
        </div>
    </div>
</div>

<h4 class="mb-3">Detail Stok Barang</h4>
<div class="card shadow-sm">
    <div class="card-body">
        <table class="table table-bordered table-striped align-middle mb-0">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Barang</th>
                    <th class="text-center">Stok</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($warehouse->stocks as $stock)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $stock->produk->nama_barang ?? '-' }}</td>
                    <td class="text-center">{{ $stock->stock_quantity }}</td>
                    <td class="text-center">
                        <div class="d-flex justify-content-center gap-2">
                            <a href="{{ route('warehouseStocks.edit', $stock->id) }}"
                                class="btn btn-warning btn-sm btn-custom">Edit</a>

                            <button type="button"
                                class="btn btn-sm btn-custom2"
                                data-bs-toggle="modal"
                                data-bs-target="#hapus{{ $stock->id }}">
                                Hapus
                            </button>

                            <a href="{{ route('warehouseStocks.show', $stock->id) }}"
                                class="btn btn-info btn-sm btn-custom">Detail</a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center text-muted">
                        Belum ada stok barang di gudang ini
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal -->
@foreach ($warehouse->stocks as $stock)
<form action="{{ route('warehouseStocks.destroy', $stock->id) }}" method="POST">
    @csrf
    @method('DELETE')
    <div class="modal-body">
        Apakah Anda yakin ingin menghapus stok
        <strong>{{ $stock->produk->nama_barang }}</strong>?
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-danger">Hapus Data</button>
    </div>
</form>
@endforeach
@endsection