@extends('layout.master')

@section('konten')
<style>
    .select-warehouse-wrap {
        max-width: 780px;
        margin: 20px auto 0;
    }

    .select-warehouse-header {
        text-align: center;
        margin-bottom: 28px;
    }

    .select-warehouse-header h3 {
        font-weight: 600;
        margin-bottom: 4px;
    }

    .select-warehouse-header p {
        color: #6c757d;
        font-size: 0.9rem;
        margin: 0;
    }

    .warehouse-card {
        border: 1px solid rgba(0, 0, 0, 0.08);
        border-radius: 14px;
        cursor: pointer;
        transition: transform 0.15s ease, box-shadow 0.15s ease, border-color 0.15s ease;
        height: 100%;
    }

    .warehouse-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
        border-color: var(--primary-color, #695CFE);
    }

    .warehouse-card .icon-badge {
        width: 46px;
        height: 46px;
        border-radius: 12px;
        background: var(--primary-color-light, #f6f5ff);
        color: var(--primary-color, #695CFE);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.3rem;
        margin-bottom: 12px;
    }

    .warehouse-card .warehouse-name {
        font-weight: 600;
        font-size: 1.05rem;
        margin-bottom: 2px;
    }

    .warehouse-card .warehouse-location {
        font-size: 0.85rem;
        color: #6c757d;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .warehouse-card.is-active {
        border-color: #198754;
        background: rgba(25, 135, 84, 0.04);
    }

    .warehouse-card .active-badge {
        font-size: 11px;
        font-weight: 600;
        color: #198754;
        margin-top: 8px;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .empty-state {
        text-align: center;
        padding: 48px 24px;
        color: #6c757d;
    }

    .empty-state i {
        font-size: 40px;
        margin-bottom: 10px;
        display: block;
        opacity: 0.5;
    }
</style>

<div class="select-warehouse-wrap">
    <div class="select-warehouse-header">
        <h3>Pilih Gudang</h3>
        <p>Pilih gudang yang ingin kamu kelola untuk melanjutkan ke dashboard.</p>
    </div>

    @if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @if ($warehouses->isEmpty())
    <div class="empty-state">
        <i class="bi bi-inbox"></i>
        <p class="mb-0">Kamu belum ditugaskan ke gudang manapun.</p>
        <p class="mb-0 small">Hubungi Admin untuk mendapatkan akses ke gudang.</p>
    </div>
    @else
    <div class="row g-3">
        @foreach($warehouses as $w)
        @php $isActive = session('active_warehouse_id') == $w->warehouse_id; @endphp
        <div class="col-md-4">
            <form action="{{ route('warehouse.setActive') }}" method="POST" class="h-100">
                @csrf
                <input type="hidden" name="warehouse_id" value="{{ $w->warehouse_id }}">
                <input type="hidden" name="redirect_to" value="warehouseStocks.index">
                <button type="submit" class="warehouse-card card shadow-sm p-3 w-100 text-start border-0 @if($isActive) is-active @endif" style="background: var(--sidebar-color, #fff);">
                    <div class="icon-badge">
                        <i class="bi bi-building"></i>
                    </div>
                    <div class="warehouse-name">{{ $w->name }}</div>
                    <div class="warehouse-location">
                        <i class="bi bi-geo-alt"></i> {{ $w->location }}
                    </div>
                    @if($isActive)
                    <div class="active-badge">
                        <i class="bi bi-check-circle-fill"></i> Sedang aktif
                    </div>
                    @endif
                </button>
            </form>
        </div>
        @endforeach
    </div>
    @endif
</div>
@endsection