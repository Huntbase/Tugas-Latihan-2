@extends('layout.master')

@section('konten')
<style>
  .category-badge {
    display: inline-block;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 500;
    color: #fff;
  }
</style>

<h1 class="mb-4">Daftar Produk</h1>

@if(auth()->user()->role_id !== 3)
<a href="{{ route('produk.create') }}" class="btn btn-primary mb-3">Tambah Produk</a>
@endif

@if (session('pesan'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
  {{ session('pesan') }}
  <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
    <h5 class="mb-0">Daftar Produk</h5>
    <div class="d-flex gap-2">
      @if (request('keyword') || request('category'))
      <a href="{{ route('produk.index') }}" class="btn btn-info">Reset</a>
      @endif
      <form class="d-flex gap-2" method="GET">
        <select name="category" class="form-select" style="width: 180px;" onchange="this.form.submit()">
          <option value="">Semua Kategori</option>
          @foreach ($categories as $cat)
          <option value="{{ $cat }}" @selected(request('category')==$cat)>{{ $cat }}</option>
          @endforeach
        </select>
        <input
          type="text"
          class="form-control"
          style="width: 220px;"
          value="{{ request('keyword') }}"
          placeholder="Cari produk"
          name="keyword">
        <button class="btn btn-success" type="submit">
          Cari Data
        </button>
      </form>
    </div>
  </div>

  <div class="card-body">
    <table class="table table-striped table-bordered align-middle">
      <thead>
        <tr>
          <th>No</th>
          <th>Nama Produk</th>
          <th>Kategori</th>
          <th>Unit</th>
          <th>Dibuat</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        @forelse ($data_produk as $produk)
        <tr>
          <td>{{ $loop->iteration + ($data_produk->currentPage() - 1) * $data_produk->perPage() }}</td>
          <td>{{ $produk->nama_barang }}</td>
          <td>
            @php
            // Warna badge dibuat konsisten per kategori berdasarkan hash nama kategori,
            // jadi kategori yang sama selalu dapat warna yang sama tanpa perlu mapping manual
            $colors = ['#0d6efd', '#198754', '#fd7e14', '#6f42c1', '#d63384', '#20c997', '#dc3545'];
            $colorIndex = crc32($produk->category) % count($colors);
            @endphp
            <span class="category-badge" style="background-color: {{ $colors[$colorIndex] }}">
              {{ $produk->category }}
            </span>
          </td>
          <td>{{ $produk->unit }}</td>
          <td>{{ $produk->created_at->format('d M Y') }}</td>
          <td class="text-center">
            <div class="d-flex justify-content-center gap-2">
              @if(auth()->user()->role_id !== 3)
              <a href="{{ route('produk.edit', $produk->barang_id) }}" class="btn btn-warning btn-sm">Edit</a>
              @endif
              <a href="{{ route('produk.show', $produk->barang_id) }}" class="btn btn-info btn-sm">Detail</a>
            </div>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="6" class="text-center">Data produk tidak ditemukan!</td>
        </tr>
        @endforelse
      </tbody>
    </table>

    {{ $data_produk->withQueryString()->links() }}
  </div>
</div>
@endsection