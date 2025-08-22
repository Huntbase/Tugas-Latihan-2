@extends('layout.master')

@section('konten')
<h1 class="mb-4">Detail Produk Kami</h1>

<div class="card mb-4 shadow-sm">
    <div class="row g-0">
        <!-- Gambar Produk -->
        <div class="col-md-5">
            <img src="https://placehold.co/600x400" class="img-fluid rounded-start" alt="{{ $produk->nama_barang }}">
        </div>

        <!-- Detail Produk -->
        <div class="col-md-7">
            <div class="card-body">
                <h3 class="card-title">{{ $produk->nama_barang }}</h3>

                <!-- Kategori sebagai badge -->
                <p class="card-text">
                    <strong>Kategori:</strong>
                    <span class="badge bg-info text-dark">{{ $produk->category }}</span>
                </p>

                <!-- Unit -->
                <p class="card-text">
                    <i class="bi bi-box-seam"></i> Unit: {{ $produk->unit }}
                </p>

                <!-- Tanggal dibuat & diupdate -->
                <p class="card-text">
                    <i class="bi bi-calendar-plus"></i> Dibuat pada: {{ $produk->created_at->format('d M Y H:i') }}
                </p>
                <p class="card-text">
                    <i class="bi bi-calendar-check"></i> Diupdate pada: {{ $produk->updated_at ? $produk->updated_at->format('d M Y H:i') : '-' }}
                </p>

                <!-- Deskripsi (opsional) -->
                @if($produk->description)
                <p class="card-text mt-3">{{ $produk->description }}</p>
                @endif

                <!-- Tombol kembali -->
                <a href="/produk" class="btn btn-primary mt-4">Kembali ke Halaman Produk</a>
            </div>
        </div>
    </div>
</div>
@endsection