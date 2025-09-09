@extends('layout.master')

@section('konten')
<style>
    .card {
        background-color: var(--sidebar-color);
        color: var(--text-color);
        transition: var(--trans-03);
    }

    .card .card-title {
        color: var(--text-color);
    }

    .badge {
        font-family: 'Poppins', sans-serif;
    }

    .btn {
        font-family: 'Poppins', sans-serif;
        border-radius: 8px;
        transition: var(--trans-03);
    }
</style>

<h1 class="mb-4">Detail User</h1>

<div class="card mb-4 shadow-sm">
    <div class="row g-0">
        <div class="col-md-12">
            <div class="card-body">
                <!-- Username -->
                <h3 class="card-title">{{ $user->user_name }}</h3>

                <!-- Role -->
                <p class="card-text">
                    <strong>Role:</strong>
                    <span class="badge bg-info text-dark">
                        {{ $user->role?->role_name ?? 'Belum ada role' }}
                    </span>
                </p>

                <!-- Tanggal dibuat & diupdate -->
                <p class="card-text">
                    <i class="bi bi-calendar-plus"></i> Dibuat pada:
                    {{ $user->created_at->format('d M Y H:i') }}
                </p>
                <p class="card-text">
                    <i class="bi bi-calendar-check"></i> Diupdate pada:
                    {{ $user->updated_at ? $user->updated_at->format('d M Y H:i') : '-' }}
                </p>

                <!-- Tombol kembali -->
                <a href="{{ route('Data_users.index') }}" class="btn btn-primary mt-4">
                    Kembali ke Halaman User
                </a>
            </div>
        </div>
    </div>
</div>
@endsection