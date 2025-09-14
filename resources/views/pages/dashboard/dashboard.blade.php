@extends('layout.master')

@section('konten')
<h1 class="mb-4">Dashboard</h1>

<p>Selamat datang, <strong>{{ auth()->user()?->user_name ?? 'Guest' }}</strong>!</p>
<p>Role: <strong>{{ auth()->user()?->role?->role_name ?? '-' }}</strong></p>

<div class="row mb-4">
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-body">
                <form action="{{ route('warehouse.setActive') }}" method="POST">
                    @csrf
                    <label for="warehouse_id" class="form-label">Pilih Warehouse</label>
                    <select name="warehouse_id" id="warehouse_id" class="form-control"
                        onchange="this.form.submit()">
                        @foreach($warehouses as $w)
                        <option value="{{ $w->warehouse_id }}"
                            {{ session('active_warehouse_id') == $w->warehouse_id ? 'selected' : '' }}>
                            {{ $w->name_id }}
                        </option>
                        @endforeach
                    </select>
                </form>
                <small class="text-muted">
                    Data saat ini menampilkan gudang:
                    <strong>{{ $activeWarehouse?->name_id ?? '-' }}</strong>
                </small>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
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
@endsection