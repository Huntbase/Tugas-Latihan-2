@extends('layout.master')

@section('konten')
<style>
    .transfer-form-card {
        border: 1px solid rgba(0, 0, 0, 0.08);
        border-radius: 14px;
        max-width: 720px;
    }

    .transfer-form-card .card-header {
        background: transparent;
        border-bottom: 1px solid rgba(0, 0, 0, 0.08);
        padding: 20px 24px;
    }

    .transfer-form-card .card-header h3 {
        font-weight: 600;
        font-size: 1.3rem;
        margin: 0;
    }

    .transfer-form-card .card-header p {
        margin: 4px 0 0;
        font-size: 0.85rem;
        color: #6c757d;
    }

    .transfer-form-card .card-body {
        padding: 24px;
    }

    .form-section-label {
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        opacity: 0.55;
        margin-bottom: 10px;
        margin-top: 22px;
    }

    .form-section-label:first-child {
        margin-top: 0;
    }

    .requester-badge {
        display: flex;
        align-items: center;
        gap: 10px;
        background: var(--primary-color-light, #f6f5ff);
        border-radius: 10px;
        padding: 10px 14px;
    }

    .requester-badge .avatar-circle {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: var(--primary-color, #695CFE);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 14px;
        flex-shrink: 0;
    }

    .requester-badge .label {
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.03em;
        opacity: 0.6;
        margin-bottom: 1px;
    }

    .requester-badge .name {
        font-weight: 600;
        font-size: 14px;
    }

    .warehouse-arrow {
        display: flex;
        align-items: center;
        justify-content: center;
        padding-top: 32px;
        color: #6c757d;
        font-size: 1.2rem;
    }

    @media (max-width: 767px) {
        .warehouse-arrow {
            display: none;
        }
    }

    .form-actions {
        border-top: 1px solid rgba(0, 0, 0, 0.08);
        margin-top: 26px;
        padding-top: 20px;
    }
</style>

<div class="transfer-form-card card shadow-sm mx-auto">
    <div class="card-header">
        <h3>Request Stock Transfer</h3>
        <p>Ajukan permintaan pemindahan stok antar gudang. Transfer akan tersimpan sebagai draft sebelum diajukan untuk approval.</p>
    </div>

    <div class="card-body">
        @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0 ps-3">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <div class="form-section-label">Dibuat oleh</div>
        <div class="requester-badge">
            <div class="avatar-circle">
                {{ strtoupper(substr(auth()->user()->user_name ?? '?', 0, 2)) }}
            </div>
            <div>
                <div class="label">Requester</div>
                <div class="name">{{ auth()->user()->user_name ?? 'Guest' }}</div>
            </div>
        </div>

        <form action="{{ route('stock-transfers.store') }}" method="POST">
            @csrf

            <div class="form-section-label">Barang &amp; Jumlah</div>
            <div class="row g-3">
                <div class="col-md-8">
                    <label class="form-label">Barang</label>
                    <select name="barang_id" class="form-select" required>
                        <option value="">-- Pilih barang --</option>
                        @foreach ($barangList as $b)
                        <option value="{{ $b->barang_id }}" @selected(old('barang_id')==$b->barang_id)>{{ $b->nama_barang }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Quantity</label>
                    <input type="number" name="quantity" class="form-control" min="1" value="{{ old('quantity') }}" required>
                </div>
            </div>

            <div class="form-section-label">Rute Perpindahan</div>
            <div class="row g-3 align-items-start">
                <div class="col-md-5">
                    <label class="form-label">Dari Warehouse</label>
                    <select name="from_warehouse_id" class="form-select" required>
                        <option value="">-- Pilih asal --</option>
                        @forelse ($fromWarehouses as $w)
                        <option value="{{ $w->warehouse_id }}" @selected(old('from_warehouse_id')==$w->warehouse_id)>{{ $w->name }}</option>
                        @empty
                        <option value="" disabled>Kamu belum ditugaskan ke gudang manapun</option>
                        @endforelse
                    </select>
                </div>

                <div class="col-md-2 warehouse-arrow">
                    <i class="bi bi-arrow-right"></i>
                </div>

                <div class="col-md-5">
                    <label class="form-label">Ke Warehouse</label>
                    <select name="to_warehouse_id" class="form-select" required>
                        <option value="">-- Pilih tujuan --</option>
                        @foreach ($toWarehouses as $w)
                        <option value="{{ $w->warehouse_id }}" @selected(old('to_warehouse_id')==$w->warehouse_id)>{{ $w->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-section-label">Catatan</div>
            <textarea name="notes" class="form-control" rows="3" placeholder="Opsional - alasan transfer, nomor referensi, dll.">{{ old('notes') }}</textarea>

            <div class="form-actions d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Simpan sebagai Draft
                </button>
                <a href="{{ route('stock-transfers.index') }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection