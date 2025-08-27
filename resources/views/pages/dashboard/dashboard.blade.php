@extends('layout.master')

@section('konten')
<h1 class="mb-4">Dashboard</h1>

<div class="row g-4">
    <!-- Total Barang -->
    <div class="col-md-3 d-flex">
        <div class="card text-white bg-primary shadow-sm flex-fill hover-card">
            <div class="card-body d-flex flex-column justify-content-center align-items-center text-center">
                <i class="bi bi-box-seam" style="font-size: 2.5rem;"></i>
                <h5 class="card-title mt-2">Total Barang</h5>
                <h2>{{ $totalBarang }}</h2>
            </div>
        </div>
    </div>

    <!-- Barang Masih Banyak -->
    <div class="col-md-3 d-flex">
        <div class="card bg-success shadow-sm flex-fill hover-card">
            <div class="card-body d-flex flex-column justify-content-center align-items-center text-center text-white">
                <i class="bi bi-check-circle" style="font-size: 2.5rem;"></i>
                <h5 class="card-title mt-2">Barang Masih Banyak</h5>
                <h2>{{ $stokBanyak }}</h2>
            </div>
        </div>
    </div>

    <!-- Barang Hampir Habis -->
    <div class="col-md-3 d-flex">
        <div class="card bg-warning shadow-sm flex-fill hover-card">
            <div class="card-body d-flex flex-column justify-content-center align-items-center text-center text-dark">
                <i class="bi bi-exclamation-triangle" style="font-size: 2.5rem;"></i>
                <h5 class="card-title mt-2">Barang Hampir Habis</h5>
                <h2>{{ $stokHampirHabis }}</h2>
            </div>
        </div>
    </div>

    <!-- Barang Kosong -->
    <div class="col-md-3 d-flex">
        <div class="card text-white bg-danger shadow-sm flex-fill hover-card">
            <div class="card-body d-flex flex-column justify-content-center align-items-center text-center">
                <i class="bi bi-x-circle" style="font-size: 2.5rem;"></i>
                <h5 class="card-title mt-2">Barang Kosong</h5>
                <h2>{{ $stokKosong }}</h2>
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
    /* Hover effect: sedikit membesar dan shadow lebih tebal */
    .hover-card {
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .hover-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.3);
    }
</style>
@endpush