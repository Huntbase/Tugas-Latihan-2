@extends('layout.master')

@section('konten')
<h1 class="mb-4">Dashboard</h1>

<div class="row g-4">
    <!-- Total Barang -->
    <div class="col-md-3">
        <div class="card text-white bg-primary shadow-sm">
            <div class="card-body">
                <h5 class="card-title">Total Barang</h5>
                <h2>{{ $totalBarang }}</h2>
            </div>
        </div>
    </div>

    <!-- Barang Masih Banyak -->
    <div class="col-md-3">
        <div class="card text-white bg-success shadow-sm">
            <div class="card-body">
                <h5 class="card-title">Barang Masih Banyak</h5>
                <h2>{{ $stokBanyak }}</h2>
            </div>
        </div>
    </div>

    <!-- Barang Hampir Habis -->
    <div class="col-md-3">
        <div class="card text-dark bg-warning shadow-sm">
            <div class="card-body">
                <h5 class="card-title">Barang Hampir Habis</h5>
                <h2>{{ $stokHampirHabis }}</h2>
            </div>
        </div>
    </div>

    <!-- Barang Kosong -->
    <div class="col-md-3">
        <div class="card text-white bg-danger shadow-sm">
            <div class="card-body">
                <h5 class="card-title">Barang Kosong</h5>
                <h2>{{ $stokKosong }}</h2>
            </div>
        </div>
    </div>
</div>
@endsection