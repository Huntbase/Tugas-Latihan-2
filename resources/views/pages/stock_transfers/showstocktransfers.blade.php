@extends('layout.master')

@section('konten')
<style>
    .badge-status {
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 0.78rem;
        font-weight: 600;
    }
    .status-draft { background: #6c757d; color: #fff; }
    .status-menunggu_approval { background: rgba(255,193,7,0.2); color: #b8860b; }
    .status-disetujui { background: rgba(13,110,253,0.15); color: #0d6efd; }
    .status-dikirim { background: rgba(111,66,193,0.15); color: #6f42c1; }
    .status-diterima { background: rgba(25,135,84,0.15); color: #198754; }
    .status-ditolak { background: rgba(220,53,69,0.15); color: #dc3545; }
</style>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="mb-0">Stock Transfers</h1>
    <a href="{{ route('stock-transfers.create') }}" class="btn btn-primary">+ New Transfer</a>
</div>

@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif
@if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show">{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card">
    <div class="card-body p-0">
        <table class="table table-striped table-bordered align-middle mb-0">
            <thead>
                <tr>
                    <th>Code</th>
                    <th>Product</th>
                    <th>From</th>
                    <th>To</th>
                    <th>Qty</th>
                    <th>Status</th>
                    <th>Requested By</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($transfers as $t)
                <tr>
                    <td>{{ $t->transfer_code }}</td>
                    <td>{{ $t->barang->nama_barang ?? '-' }}</td>
                    <td>{{ $t->fromWarehouse->name ?? '-' }}</td>
                    <td>{{ $t->toWarehouse->name ?? '-' }}</td>
                    <td>{{ $t->quantity }}</td>
                    <td>
                        <span class="badge-status status-{{ $t->status }}">
                            {{ str_replace('_', ' ', ucfirst($t->status)) }}
                        </span>
                    </td>
                    <td>{{ $t->requester->user_name ?? '-' }}</td>
                    <td>
                        <div class="d-flex flex-wrap gap-1">

                            {{-- draft -> menunggu_approval : requester (Staff) submit --}}
                            @can('submit', $t)
                                <form action="{{ route('stock-transfers.submit', $t) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button class="btn btn-sm btn-primary" onclick="return confirm('Ajukan transfer ini untuk approval?')">Submit</button>
                                </form>
                            @endcan

                            {{-- menunggu_approval -> disetujui / ditolak : Admin atau Supervisor lain --}}
                            @can('approve', $t)
                                <form action="{{ route('stock-transfers.approve', $t) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button class="btn btn-sm btn-success" onclick="return confirm('Setujui transfer ini?')">Approve</button>
                                </form>
                                <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $t->id }}">Reject</button>
                            @endcan

                            {{-- disetujui -> dikirim : Staff gudang asal --}}
                            @can('ship', $t)
                                <form action="{{ route('stock-transfers.ship', $t) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button class="btn btn-sm btn-primary" onclick="return confirm('Kirim barang ini sekarang? Stok gudang asal akan berkurang.')">Ship</button>
                                </form>
                            @endcan

                            {{-- dikirim -> diterima / ditolak : Staff gudang tujuan --}}
                            @can('receive', $t)
                                <form action="{{ route('stock-transfers.receive', $t) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button class="btn btn-sm btn-success" onclick="return confirm('Konfirmasi barang sudah diterima? Stok gudang tujuan akan bertambah.')">Receive</button>
                                </form>
                                <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#rejectDeliveryModal{{ $t->id }}">Reject</button>
                            @endcan

                            @cannot('submit', $t)
                                @cannot('approve', $t)
                                    @cannot('ship', $t)
                                        @cannot('receive', $t)
                                            <span class="text-muted">—</span>
                                        @endcannot
                                    @endcannot
                                @endcannot
                            @endcannot
                        </div>

                        {{-- Modal reject (menunggu_approval -> ditolak) --}}
                        <div class="modal fade" id="rejectModal{{ $t->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <form action="{{ route('stock-transfers.reject', $t) }}" method="POST" class="modal-content">
                                    @csrf
                                    <div class="modal-header">
                                        <h5 class="modal-title">Reject Transfer {{ $t->transfer_code }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <label class="form-label">Alasan penolakan</label>
                                        <textarea name="rejection_reason" class="form-control" required></textarea>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-danger">Reject</button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        {{-- Modal reject delivery (dikirim -> ditolak) --}}
                        <div class="modal fade" id="rejectDeliveryModal{{ $t->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <form action="{{ route('stock-transfers.reject-delivery', $t) }}" method="POST" class="modal-content">
                                    @csrf
                                    <div class="modal-header">
                                        <h5 class="modal-title">Tolak Pengiriman {{ $t->transfer_code }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <label class="form-label">Alasan penolakan (misal: barang rusak/salah jumlah)</label>
                                        <textarea name="rejection_reason" class="form-control" required></textarea>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-danger">Tolak</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="text-center py-4 text-muted">Belum ada transfer.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">
    {{ $transfers->withQueryString()->links() }}
</div>
@endsection