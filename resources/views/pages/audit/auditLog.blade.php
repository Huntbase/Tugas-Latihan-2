@extends('layout.master')

@php
use App\Models\User;
@endphp

@section('konten')
<style>
  .audit-page h3 {
    font-weight: 600;
  }

  /* ---------------------------
     Filter bar
  --------------------------- */
  .audit-filter {
    background: var(--sidebar-color, #fff);
    border: 1px solid rgba(0, 0, 0, 0.08);
    border-radius: 10px;
    padding: 16px;
  }

  .audit-filter label {
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.03em;
    opacity: 0.6;
    margin-bottom: 4px;
  }

  .audit-filter .form-control {
    border-radius: 8px;
  }

  /* ---------------------------
     Log entry cards
  --------------------------- */
  .audit-entry {
    border: 1px solid rgba(0, 0, 0, 0.08);
    border-left: 4px solid var(--action-color, #6c757d);
    border-radius: 10px;
    transition: box-shadow 0.15s ease;
  }

  .audit-entry:hover {
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.06);
  }

  .audit-entry.action-created {
    --action-color: #198754;
  }

  .audit-entry.action-updated {
    --action-color: #0d6efd;
  }

  .audit-entry.action-deleted {
    --action-color: #dc3545;
  }

  .audit-entry.action-default {
    --action-color: #6c757d;
  }

  .audit-entry .avatar {
    width: 40px;
    height: 40px;
    object-fit: cover;
    border: 1px solid rgba(0, 0, 0, 0.08);
  }

  .audit-entry .badge {
    font-weight: 500;
    padding: 5px 10px;
    border-radius: 20px;
  }

  .audit-entry .btn-detail {
    border-radius: 8px;
    font-size: 13px;
  }

  .audit-detail-list {
    list-style: none;
    display: grid;
    grid-template-columns: max-content 1fr;
    column-gap: 20px;
    row-gap: 0;
    margin: 0;
    padding-left: 0;
  }

  .audit-detail-list li {
    display: contents;
  }

  .audit-detail-list li strong,
  .audit-detail-list li span {
    padding: 8px 0;
    border-bottom: 1px dashed rgba(0, 0, 0, 0.08);
    font-size: 14px;
  }

  .audit-detail-list li strong {
    white-space: nowrap;
    font-weight: 600;
    opacity: 0.65;
  }

  .audit-detail-list li:last-child strong,
  .audit-detail-list li:last-child span {
    border-bottom: none;
  }

  .audit-empty {
    text-align: center;
    padding: 48px 16px;
    opacity: 0.6;
  }

  .audit-empty i {
    font-size: 36px;
    margin-bottom: 8px;
    display: block;
  }
</style>

<div class="container py-3 audit-page">
  <h3 class="mb-4">Audit Log</h3>

  <!-- Filter -->
  <form method="GET" class="audit-filter mb-4">
    <div class="row g-3 align-items-end">
      <div class="col-md-4">
        <label class="d-block">Cari User</label>
        <input type="text" name="user" value="{{ request('user') }}" class="form-control" placeholder="Nama user...">
      </div>
      <div class="col-md-3">
        <label class="d-block">Dari Tanggal</label>
        <input type="date" name="start_date" value="{{ request('start_date') }}" class="form-control">
      </div>
      <div class="col-md-3">
        <label class="d-block">Sampai Tanggal</label>
        <input type="date" name="end_date" value="{{ request('end_date') }}" class="form-control">
      </div>
      <div class="col-md-2 d-flex gap-2">
        <button type="submit" class="btn btn-primary flex-fill">
          <i class="bi bi-funnel"></i> Filter
        </button>
      </div>
    </div>
    @if (request('user') || request('start_date') || request('end_date'))
    <div class="mt-2">
      <a href="{{ route('auditLog.index') }}" class="small text-muted">
        <i class="bi bi-x-circle"></i> Reset filter
      </a>
    </div>
    @endif
  </form>

  <!-- Audit Log Feed -->
  @forelse($logs as $log)
  @php
  $roleMap = User::roleMap();
  $actionKey = in_array($log->action, ['created','updated','deleted']) ? $log->action : 'default';
  $actionClass = match($log->action) {
  'created' => 'badge bg-success',
  'updated' => 'badge bg-primary',
  'deleted' => 'badge bg-danger',
  default => 'badge bg-secondary'
  };
  $actionIcon = match($log->action) {
  'created' => 'bi-plus-circle',
  'updated' => 'bi-pencil-square',
  'deleted' => 'bi-trash',
  default => 'bi-info-circle'
  };
  $desc = json_decode($log->description, true);

  // Nama & avatar yang melakukan aksi (Admin yang login)
  $actorName = $log->user?->user_name ?? 'Guest';
  $actorAvatar = $log->user?->avatar ?? 'https://ui-avatars.com/api/?name='.urlencode($actorName).'&background=random';

  // Nama user yang diubah
  $targetName = $desc['after']['user_name'] ?? 'N/A';
  @endphp

  <div class="card mb-2 audit-entry action-{{ $actionKey }}">
    <div class="card-body d-flex justify-content-between align-items-center flex-wrap gap-2">
      <div class="d-flex align-items-center gap-2">
        <img src="{{ $actorAvatar }}" alt="avatar" class="rounded-circle avatar">
        <div>
          <strong>{{ $actorName }}</strong>
          <span class="{{ $actionClass }} ms-1">
            <i class="bi {{ $actionIcon }}"></i> {{ ucfirst($log->action) }}
          </span>
        </div>
      </div>
      <div class="d-flex align-items-center gap-3">
        <small class="text-muted">{{ $log->created_at->diffForHumans() }}</small>
        <button class="btn btn-sm btn-outline-primary btn-detail" data-bs-toggle="collapse" data-bs-target="#desc-{{ $log->audit_log_user_id }}">
          Detail
        </button>
      </div>
    </div>

    <div class="collapse" id="desc-{{ $log->audit_log_user_id }}">
      <div class="card-body pt-0">
        <ul class="audit-detail-list">
          @if(is_array($desc) && isset($desc['before']) && isset($desc['after']))
          <li><strong>User yang diubah</strong> <span>{{ $targetName }}</span></li>

          @if(isset($desc['before']['role_id']) && isset($desc['after']['role_id']))
          <li>
            <strong>Role</strong>
            <span>
              {{ $roleMap[$desc['before']['role_id']] ?? $desc['before']['role_id'] }}
              &rarr;
              {{ $roleMap[$desc['after']['role_id']] ?? $desc['after']['role_id'] }}
            </span>
          </li>
          @endif

          @if(isset($desc['after']['updated_at']))
          <li><strong>Updated At</strong>
            <span>{{ \Carbon\Carbon::parse($desc['after']['updated_at'])->format('d-m-Y H:i:s') }}</span>
          </li>
          @endif
          @else
          @foreach((array) $desc as $key => $value)
          @if($key === 'password')
          <li><strong>Password</strong> <span>[HIDDEN]</span></li>
          @else
          <li>
            <strong>{{ ucfirst(str_replace('_', ' ', $key)) }}</strong>
            <span>{{ in_array($key, ['created_at','updated_at'])
                    ? \Carbon\Carbon::parse($value)->format('d-m-Y H:i:s')
                    : $value }}</span>
          </li>
          @endif
          @endforeach
          @endif
        </ul>
      </div>
    </div>
  </div>

  @empty
  <div class="audit-empty">
    <i class="bi bi-inbox"></i>
    <p class="mb-0">Belum ada aktivitas yang tercatat.</p>
  </div>
  @endforelse

  <!-- Pagination -->
  <div class="mt-3">
    {{ $logs->withQueryString()->links() }}
  </div>
</div>
@endsection