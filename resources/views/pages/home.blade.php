@extends('layout.master')

@section('konten')
<style>
    .dashboard-header {
        margin-bottom: 24px;
    }

    .dashboard-header h1 {
        font-weight: 600;
        font-size: 1.6rem;
        margin-bottom: 4px;
    }

    .dashboard-header .subtitle {
        color: var(--text-color, #6c757d);
        opacity: 0.75;
        font-size: 0.95rem;
    }

    .dashboard-header .subtitle strong {
        opacity: 1;
    }

    .hover-card {
        border: 1px solid rgba(0, 0, 0, 0.06);
        border-radius: 14px;
        transition: transform 0.15s ease, box-shadow 0.15s ease;
    }

    .hover-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
    }

    .stat-card .icon-badge {
        width: 56px;
        height: 56px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.6rem;
        margin-bottom: 12px;
    }

    .stat-card .stat-value {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 0;
        line-height: 1.1;
    }

    .stat-card .stat-label {
        font-size: 0.85rem;
        color: #6c757d;
        font-weight: 500;
        margin-bottom: 2px;
    }

    .stat-card.tone-primary .icon-badge {
        background: rgba(13, 110, 253, 0.12);
        color: #0d6efd;
    }

    .stat-card.tone-success .icon-badge {
        background: rgba(25, 135, 84, 0.12);
        color: #198754;
    }

    .stat-card.tone-warning .icon-badge {
        background: rgba(255, 193, 7, 0.15);
        color: #b8860b;
    }

    .stat-card.tone-danger .icon-badge {
        background: rgba(220, 53, 69, 0.12);
        color: #dc3545;
    }

    .warehouse-breakdown {
        border: 1px solid rgba(0, 0, 0, 0.08);
        border-radius: 14px;
        margin-top: 28px;
    }

    .warehouse-breakdown .card-header {
        background: transparent;
        border-bottom: 1px solid rgba(0, 0, 0, 0.08);
        font-weight: 600;
        padding: 16px 20px;
    }

    .warehouse-breakdown table {
        margin-bottom: 0;
    }

    .warehouse-breakdown a {
        text-decoration: none;
    }
</style>

@php
$hour = now()->hour;
$greeting = $hour < 11 ? 'Selamat pagi' : ($hour < 15 ? 'Selamat siang' : ($hour < 19 ? 'Selamat sore' : 'Selamat malam' ));
    @endphp

    <div class="dashboard-header">
    <h1>{{ $greeting }}, {{ auth()->user()?->user_name ?? 'Guest' }}</h1>
    <div class="subtitle">
        Role: <strong>{{ auth()->user()?->role?->role_name ?? '-' }}</strong>
        &middot; Menampilkan data dari
        <strong>{{ auth()->user()?->role_id === 1 ? 'semua gudang' : 'gudang yang kamu awasi' }}</strong>
    </div>
    </div>

    <div class="row g-4">
        <div class="col-md-3 d-flex">
            <div class="card shadow-sm flex-fill hover-card stat-card tone-primary">
                <div class="card-body">
                    <div class="icon-badge"><i class="bi bi-box-seam"></i></div>
                    <div class="stat-label">Total Barang</div>
                    <p class="stat-value">{{ $totalBarang ?? 0 }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 d-flex">
            <div class="card shadow-sm flex-fill hover-card stat-card tone-success">
                <div class="card-body">
                    <div class="icon-badge"><i class="bi bi-check-circle"></i></div>
                    <div class="stat-label">Barang Masih Banyak</div>
                    <p class="stat-value">{{ $stokBanyak ?? 0 }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 d-flex">
            <div class="card shadow-sm flex-fill hover-card stat-card tone-warning">
                <div class="card-body">
                    <div class="icon-badge"><i class="bi bi-exclamation-triangle"></i></div>
                    <div class="stat-label">Barang Hampir Habis</div>
                    <p class="stat-value">{{ $stokHampirHabis ?? 0 }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 d-flex">
            <div class="card shadow-sm flex-fill hover-card stat-card tone-danger">
                <div class="card-body">
                    <div class="icon-badge"><i class="bi bi-x-circle"></i></div>
                    <div class="stat-label">Barang Kosong</div>
                    <p class="stat-value">{{ $stokKosong ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card warehouse-breakdown shadow-sm">
        <div class="card-header">Rincian per Gudang</div>
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">Gudang</th>
                        <th>Lokasi</th>
                        <th class="text-end pe-4">Jumlah Barang</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($warehouses as $w)
                    <tr>
                        <td class="ps-4">
                            <a href="{{ route('warehouses.show', $w->warehouse_id) }}">{{ $w->name_id }}</a>
                        </td>
                        <td>{{ $w->location_id }}</td>
                        <td class="text-end pe-4">{{ $w->total_barang }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="text-center py-4 text-muted">Belum ada gudang yang bisa ditampilkan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @endsection