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

    .action-card {
        border: none;
        border-radius: 14px;
        padding: 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        text-decoration: none;
        transition: transform 0.15s ease;
    }

    .action-card:hover {
        transform: translateY(-3px);
    }

    .action-card.tone-ship {
        background: rgba(255, 193, 7, 0.15);
    }

    .action-card.tone-ship .value {
        color: #b8860b;
    }

    .action-card.tone-receive {
        background: rgba(13, 110, 253, 0.12);
    }

    .action-card.tone-receive .value {
        color: #0d6efd;
    }

    .action-card .label {
        font-size: 0.85rem;
        font-weight: 500;
        color: #6c757d;
        margin-bottom: 4px;
    }

    .action-card .value {
        font-size: 1.7rem;
        font-weight: 700;
    }

    .action-card i {
        font-size: 2rem;
        opacity: 0.8;
    }

    .task-list-card {
        border: 1px solid rgba(0, 0, 0, 0.08);
        border-radius: 14px;
        margin-top: 20px;
    }

    .task-list-card .card-header {
        background: transparent;
        border-bottom: 1px solid rgba(0, 0, 0, 0.08);
        font-weight: 600;
        padding: 14px 20px;
    }
</style>

@php
$hour = now()->hour;
$greeting = $hour < 11 ? 'Selamat pagi' : ($hour < 15 ? 'Selamat siang' : ($hour < 19 ? 'Selamat sore' : 'Selamat malam' ));
    @endphp

    <div class="dashboard-header">
    <h1>{{ $greeting }}, {{ auth()->user()?->user_name ?? 'Guest' }}</h1>
    <div class="subtitle">Ini tugas yang perlu kamu tindak lanjuti hari ini.</div>
    </div>

    <div class="row g-3">
        <div class="col-md-6">
            <a href="{{ route('stock-transfers.index') }}" class="action-card tone-ship">
                <div>
                    <div class="label">Perlu Dikirim</div>
                    <div class="value">{{ $needsToShip }} transfer</div>
                </div>
                <i class="bi bi-truck"></i>
            </a>
        </div>
        <div class="col-md-6">
            <a href="{{ route('stock-transfers.index') }}" class="action-card tone-receive">
                <div>
                    <div class="label">Perlu Diterima</div>
                    <div class="value">{{ $needsToReceive }} transfer</div>
                </div>
                <i class="bi bi-box-arrow-in-down"></i>
            </a>
        </div>
    </div>

    <div class="card task-list-card shadow-sm">
        <div class="card-header">Menunggu Dikirim dari Gudangmu</div>
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">Kode</th>
                        <th>Barang</th>
                        <th>Tujuan</th>
                        <th class="text-end pe-4">Qty</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($shipList as $t)
                    <tr>
                        <td class="ps-4">{{ $t->transfer_code }}</td>
                        <td>{{ $t->barang->nama_barang ?? '-' }}</td>
                        <td>{{ $t->toWarehouse->name ?? '-' }}</td>
                        <td class="text-end pe-4">{{ $t->quantity }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-4 text-muted">Tidak ada transfer yang menunggu dikirim.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="card task-list-card shadow-sm">
        <div class="card-header">Menunggu Diterima di Gudangmu</div>
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">Kode</th>
                        <th>Barang</th>
                        <th>Dari</th>
                        <th class="text-end pe-4">Qty</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($receiveList as $t)
                    <tr>
                        <td class="ps-4">{{ $t->transfer_code }}</td>
                        <td>{{ $t->barang->nama_barang ?? '-' }}</td>
                        <td>{{ $t->fromWarehouse->name ?? '-' }}</td>
                        <td class="text-end pe-4">{{ $t->quantity }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-4 text-muted">Tidak ada transfer yang menunggu diterima.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @endsection