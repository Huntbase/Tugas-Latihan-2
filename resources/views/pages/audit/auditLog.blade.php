@extends('layout.master')

@section('konten')
<div class="container py-3">
  <h3 class="mb-4">Audit Log (Feed Modern dengan Avatar)</h3>

  <!-- Filter -->
  <form method="GET" class="row g-3 mb-3">
    <div class="col-md-4">
      <input type="text" name="user" value="{{ request('user') }}" class="form-control" placeholder="Cari berdasarkan user">
    </div>
    <div class="col-md-3">
      <input type="date" name="start_date" value="{{ request('start_date') }}" class="form-control" placeholder="Tanggal mulai">
    </div>
    <div class="col-md-3">
      <input type="date" name="end_date" value="{{ request('end_date') }}" class="form-control" placeholder="Tanggal akhir">
    </div>
    <div class="col-md-2">
      <button type="submit" class="btn btn-primary w-100">Filter</button>
    </div>
  </form>

  <!-- Audit Log Feed -->
  @foreach($logs as $log)
  @php
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
  $userAvatar = $log->user->avatar ?? 'https://ui-avatars.com/api/?name='.urlencode($log->user->name ?? 'Guest');
  @endphp

  <div class="card mb-2 shadow-sm">
    <div class="card-body d-flex justify-content-between align-items-center">
      <div class="d-flex align-items-center gap-2">
        <img src="{{ $userAvatar }}" alt="avatar" class="rounded-circle" width="40" height="40">
        <div>
          <strong>{{ $log->user->name ?? 'Guest' }}</strong>
          <span class="{{ $actionClass }}">
            <i class="bi {{ $actionIcon }}"></i> {{ ucfirst($log->action) }}
          </span>
        </div>
      </div>
      <small class="text-muted">{{ \Carbon\Carbon::parse($log->created_at)->diffForHumans() }}</small>
      <button class="btn btn-sm btn-outline-primary" data-bs-toggle="collapse" data-bs-target="#desc-{{ $log->id }}">
        Detail
      </button>
    </div>
    <div class="collapse mb-3" id="desc-{{ $log->id }}">
      <ul class=" mb-2 mt-2 ms-4">
        @if(is_array($desc))
        @foreach($desc as $key => $value)
        <li>
          <strong>{{ ucfirst(str_replace('_', ' ', $key)) }}:</strong>
          @if(in_array($key, ['created_at', 'updated_at']))
          {{ \Carbon\Carbon::parse($value)->format('d-m-Y H:i:s') }}
          @else
          {{ $value }}
          @endif
        </li>
        @endforeach
        @else
        <li>{{ $log->description }}</li>
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