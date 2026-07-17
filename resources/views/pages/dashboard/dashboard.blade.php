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

    /* ---------------------------
     Warehouse selector
  --------------------------- */
    .warehouse-picker {
        border: 1px solid rgba(0, 0, 0, 0.08);
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
    }

    .warehouse-picker label {
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        opacity: 0.55;
        margin-bottom: 6px;
    }

    .warehouse-picker select {
        border-radius: 8px;
    }

    .warehouse-picker .active-label {
        font-size: 13px;
        color: #6c757d;
    }

    /* ---------------------------
     Stat cards
  --------------------------- */
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
</style>

<div class="dashboard-header">
    <h1>Dashboard</h1>
    <div class="subtitle">
        Selamat datang, <strong>{{ auth()->user()?->user_name ?? 'Guest' }}</strong>
        &middot; Role: <strong>{{ auth()->user()?->role?->role_name ?? '-' }}</strong>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-6">
        <div class="card warehouse-picker">
            <div class="card-body">
                <form action="{{ route('warehouse.setActive') }}" method="POST">
                    @csrf
                    <label for="warehouse_id" class="form-label d-block">Pilih Warehouse</label>
                    <select name="warehouse_id" id="warehouse_id" class="form-select"
                        onchange="this.form.submit()">
                        @foreach($warehouses as $w)
                        <option value="{{ $w->warehouse_id }}"
                            {{ session('active_warehouse_id') == $w->warehouse_id ? 'selected' : '' }}>
                            {{ $w->name_id }}
                        </option>
                        @endforeach
                    </select>
                </form>
                <div class="active-label mt-2">
                    Data saat ini menampilkan gudang:
                    <strong>{{ $activeWarehouse?->name_id ?? '-' }}</strong>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Total Barang -->
    <div class="col-md-3 d-flex">
        <div class="card shadow-sm flex-fill hover-card stat-card tone-primary">
            <div class="card-body">
                <div class="icon-badge">
                    <i class="bi bi-box-seam"></i>
                </div>
                <div class="stat-label">Total Barang</div>
                <p class="stat-value">{{ $totalBarang ?? 0 }}</p>
            </div>
        </div>
    </div>

    <!-- Barang Masih Banyak -->
    <div class="col-md-3 d-flex">
        <div class="card shadow-sm flex-fill hover-card stat-card tone-success">
            <div class="card-body">
                <div class="icon-badge">
                    <i class="bi bi-check-circle"></i>
                </div>
                <div class="stat-label">Barang Masih Banyak</div>
                <p class="stat-value">{{ $stokBanyak ?? 0 }}</p>
            </div>
        </div>
    </div>

    <!-- Barang Hampir Habis -->
    <div class="col-md-3 d-flex">
        <div class="card shadow-sm flex-fill hover-card stat-card tone-warning">
            <div class="card-body">
                <div class="icon-badge">
                    <i class="bi bi-exclamation-triangle"></i>
                </div>
                <div class="stat-label">Barang Hampir Habis</div>
                <p class="stat-value">{{ $stokHampirHabis ?? 0 }}</p>
            </div>
        </div>
    </div>

    <!-- Barang Kosong -->
    <div class="col-md-3 d-flex">
        <div class="card shadow-sm flex-fill hover-card stat-card tone-danger">
            <div class="card-body">
                <div class="icon-badge">
                    <i class="bi bi-x-circle"></i>
                </div>
                <div class="stat-label">Barang Kosong</div>
                <p class="stat-value">{{ $stokKosong ?? 0 }}</p>
            </div>
        </div>
    </div>
</div>
@endsection