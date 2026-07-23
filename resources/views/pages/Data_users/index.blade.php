@extends('layout.master')

@section('konten')
<h1 class="mb-4">Daftar User</h1>

<a href="{{ route('Data_users.create') }}" class="btn btn-primary mb-3">Tambah User</a>

@if (session('pesan'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('pesan') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Daftar User</h5>
        <div class="d-flex gap-2">
            @if (Request()->keyword != '')
            <a href="{{ route('Data_users.index') }}" class="btn btn-info">Reset</a>
            @endif
            <form class="input-group" style="width: 350px;">
                <input
                    type="text"
                    class="form-control"
                    value="{{ Request()->keyword }}"
                    placeholder="Cari user"
                    name="keyword"
                    aria-label="Cari user"
                    aria-describedby="button-addon2">
                <button class="btn btn-success" type="submit" id="button-addon2">
                    Cari User
                </button>
            </form>
        </div>
    </div>

    <div class="card-body">
        <table class="table table-striped table-bordered" id="m_user">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Role</th>
                    <th>Warehouse</th>
                    <th>Dibuat</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $user)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $user->user_name }}</td>
                    <td>{{ $user->role?->role_name ?? 'Belum ada role' }}</td>
                    <td>
                        @if ($user->role_id === 1)
                        <span class="badge bg-primary">Semua Warehouse</span>
                        @elseif ($user->warehouses->isNotEmpty())
                        @foreach ($user->warehouses as $w)
                        <span class="badge bg-secondary">{{ $w->name }}</span>
                        @endforeach
                        @else
                        <span class="text-muted small">Belum ditugaskan</span>
                        @endif
                    </td>
                    <td>{{ $user->created_at->format('d M Y') }}</td>
                    <td class="text-center">
                        <div class="d-flex justify-content-center gap-2">
                            <a href="{{ route('Data_users.edit', $user->user_id) }}" class="btn btn-warning btn-sm">Edit</a>
                            <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#hapus{{ $user->user_id }}">
                                Hapus
                            </button>
                            <a href="{{ route('Data_users.show', $user->user_id) }}" class="btn btn-info btn-sm">Detail</a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center">Data user tidak ditemukan!</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Konfirmasi Hapus -->
@foreach ($users as $user)
<div class="modal fade" id="hapus{{ $user->user_id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('Data_users.destroy', $user->user_id) }}" method="POST" class="modal-content">
            @csrf
            @method('DELETE')
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Konfirmasi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Apakah Anda yakin ingin menghapus <strong>{{ $user->user_name }}</strong>?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-danger">Hapus Data</button>
            </div>
        </form>
    </div>
</div>
@endforeach

@endsection