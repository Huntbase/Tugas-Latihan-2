@extends('layout.master')

@section('konten')
<h1 class="mb-4">Daftar Produk Kami</h1>

<a href="/produk/create" class="btn btn-primary mb-3">Tambah Produk</a>

<div class="alert alert-primary mb-3">
  <b>Nama Toko:</b> {{ $data_toko['nama_toko'] }}<br>
  <b>Alamat:</b> {{ $data_toko['alamat'] }}<br>
  <b>Tipe Toko:</b> {{ $data_toko['type'] }}
</div>

@if (session('pesan'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
  {{ session('pesan') }}
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h5 class="mb-0">Daftar Produk</h5>
    <div class="d-flex gap-2">
      @if (Request()->keyword != '')
      <a href="/produk" class="btn btn-info">Reset</a>
      @endif
      <form class="input-group" style="width: 350px;">
        <input
          type="text"
          class="form-control"
          value="{{ Request()->keyword }}"
          placeholder="Cari produk"
          name="keyword"
          aria-label="Cari produk"
          aria-describedby="button-addon2">
        <button class="btn btn-success" type="submit" id="button-addon2">
          Cari Data
        </button>
      </form>
    </div>
  </div>

  <div class="card-body">
    <table class="table table-striped table-bordered">
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
        @forelse ($data_produk as $item)
        <tr>
          <td>{{ $loop->iteration }}</td>
          <td>{{ $item->nama_barang }}</td>
          <td>{{ $item->category }}</td>
          <td>{{ $item->unit }}</td>
          <td>{{ $item->created_at->format('d M Y') }}</td>
          <td class="text-center">
            <div class="d-flex justify-content-center gap-2">
              <a href="/produk/{{ $item->barang_id }}/edit" class="btn btn-warning btn-sm">Edit</a>
              <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#hapus{{ $item->barang_id }}">
                Hapus
              </button>
              <a href="/produk/{{ $item->barang_id }}" class="btn btn-info btn-sm">Detail</a>
            </div>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="6" class="text-center">Data yang anda cari tidak ada!</td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

<!-- Modal -->
@foreach ($data_produk as $item)
<div class="modal fade" id="hapus{{ $item->barang_id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form action="/produk/{{ $item->barang_id }}" method="POST" class="modal-content">
      @csrf
      @method('DELETE')
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Konfirmasi</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Apakah Anda yakin ingin menghapus <strong>{{ $item->nama_barang }}</strong>?
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