@extends('layout.master')

@php
use App\Models\User;
@endphp

@section('konten')
<div class="container py-3">
  <h3 class="mb-4">Audit Log (Feed Modern dengan Avatar)</h3>

  <!-- Filter -->
  <form method="GET" class="row g-3 mb-3">
    <div class="col-md-4">
      <input type="text" name="user" value="{{ request('user') }}" class="form-control" placeholder="Cari berdasarkan user">
    </div>
    <div class="col-md-3">
      <input type="date" name="start_date" value="{{ request('start_date') }}" class="form-control">
    </div>
    <div class="col-md-3">
      <input type="date" name="end_date" value="{{ request('end_date') }}" class="form-control">
    </div>
    <div class="col-md-2">
      <button type="submit" class="btn btn-primary w-100">Filter</button>
    </div>
  </form>

  <!-- Audit Log Feed -->
  @foreach($logs as $log)
  @php
  $roleMap = User::roleMap();
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
  $actorAvatar = $log->user?->avatar ?? 'https://ui-avatars.com/api/?name='.urlencode($actorName);

  // Nama user yang diubah
  $targetName = $desc['after']['user_name'] ?? 'N/A';
  @endphp

  <div class="card mb-2 shadow-sm">
    <div class="card-body d-flex justify-content-between align-items-center">
      <div class="d-flex align-items-center gap-2">
        <img src="{{ $actorAvatar }}" alt="avatar" class="rounded-circle" width="40" height="40">
        <div>
          <strong>{{ $actorName }}</strong>
          <span class="{{ $actionClass }}">
            <i class="bi {{ $actionIcon }}"></i> {{ ucfirst($log->action) }}
          </span>
        </div>
      </div>
      <small class="text-muted">{{ $log->created_at->diffForHumans() }}</small>
      <button class="btn btn-sm btn-outline-primary" data-bs-toggle="collapse" data-bs-target="#desc-{{ $log->audit_log_user_id }}">
        Detail
      </button>
    </div>

    <div class="collapse mb-3" id="desc-{{ $log->audit_log_user_id }}">
      <ul class="mb-2 mt-2 ms-4">
        @if(is_array($desc) && isset($desc['before']) && isset($desc['after']))
        <li><strong>User yang diubah:</strong> {{ $targetName }}</li>

        @if(isset($desc['before']['role_id']) && isset($desc['after']['role_id']))
        <li>
          <strong>Role:</strong>
          {{ $roleMap[$desc['before']['role_id']] ?? $desc['before']['role_id'] }}
          →
          {{ $roleMap[$desc['after']['role_id']] ?? $desc['after']['role_id'] }}
        </li>
        @endif

        @if(isset($desc['after']['updated_at']))
        <li><strong>Updated At:</strong>
          {{ \Carbon\Carbon::parse($desc['after']['updated_at'])->format('d-m-Y H:i:s') }}
        </li>
        @endif
        @else
        @foreach((array) $desc as $key => $value)
        @if($key === 'password')
        <li><strong>Password:</strong> [HIDDEN]</li>
        @else
        <li>
          <strong>{{ ucfirst(str_replace('_', ' ', $key)) }}:</strong>
          {{ in_array($key, ['created_at','updated_at'])
                  ? \Carbon\Carbon::parse($value)->format('d-m-Y H:i:s')
                  : $value }}
        </li>
        @endif
        @endforeach
        @endif
      </ul>
    </div>
  </div>

  @endforeach

  <!-- Pagination -->
  <div class="mt-3">
    {{ $logs->withQueryString()->links() }}
  </div>
</div>
@endsection