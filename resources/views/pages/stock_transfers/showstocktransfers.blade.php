@extends('layout.master')

@section('konten')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Stock Transfers</h3>
        <a href="{{ route('stock-transfers.create') }}" class="btn btn-primary">+ New Transfer</a>
    </div>

    @if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <table class="table table-bordered table-striped align-middle">
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
                <td>{{ $t->fromWarehouse->name }}</td>
                <td>{{ $t->toWarehouse->name }}</td>
                <td>{{ $t->quantity }}</td>
                <td>
                    <span class="badge bg-{{ match($t->status) {
                            'pending' => 'warning',
                            'completed' => 'success',
                            'rejected' => 'danger',
                            'cancelled' => 'secondary',
                            default => 'light'
                        } }}">
                        {{ ucfirst($t->status) }}
                    </span>
                </td>
                <td>{{ $t->requester->user_name ?? '-' }}</td>
                <td>
                    @if ($t->status === 'pending')
                    <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#approveModal{{ $t->id }}">Approve</button>
                    <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $t->id }}">Reject</button>

                    @if ($t->requested_by === auth()->id())
                    <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#cancelModal{{ $t->id }}">Cancel</button>
                    @endif

                    <!-- Approve confirmation modal -->
                    <div class="modal fade" id="approveModal{{ $t->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <form action="{{ route('stock-transfers.approve', $t) }}" method="POST">
                                @csrf
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">
                                            <i class="bi bi-check-circle text-success"></i>
                                            Approve Transfer {{ $t->transfer_code }}
                                        </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p class="mb-2">You're about to move stock:</p>
                                        <ul class="mb-0">
                                            <li><strong>{{ $t->quantity }}</strong> x {{ $t->barang->nama_barang ?? '-' }}</li>
                                            <li>{{ $t->fromWarehouse->name }} &rarr; {{ $t->toWarehouse->name }}</li>
                                        </ul>
                                        <p class="text-muted mt-2 mb-0">This action moves stock immediately and cannot be undone.</p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-success">
                                            <i class="bi bi-check-circle"></i> Confirm Approve
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Reject reason modal -->
                    <div class="modal fade" id="rejectModal{{ $t->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <form action="{{ route('stock-transfers.reject', $t) }}" method="POST">
                                @csrf
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">
                                            <i class="bi bi-x-circle text-danger"></i>
                                            Reject Transfer {{ $t->transfer_code }}
                                        </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <label class="form-label">Reason</label>
                                        <textarea name="rejection_reason" class="form-control" required placeholder="Why is this transfer being rejected?"></textarea>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Back</button>
                                        <button type="submit" class="btn btn-danger">
                                            <i class="bi bi-x-circle"></i> Confirm Reject
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Cancel confirmation modal -->
                    @if ($t->requested_by === auth()->id())
                    <div class="modal fade" id="cancelModal{{ $t->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <form action="{{ route('stock-transfers.cancel', $t) }}" method="POST">
                                @csrf
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Cancel Transfer {{ $t->transfer_code }}?</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p class="mb-0">This will cancel your pending transfer request. This can't be undone.</p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Keep it</button>
                                        <button type="submit" class="btn btn-outline-danger">Yes, cancel it</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    @endif
                    @else
                    <span class="text-muted">—</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="text-center">No transfers yet.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    {{ $transfers->links() }}
</div>
@endsection