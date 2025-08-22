@extends('layout.master')

@section('konten')
<h1 class="mb-4">Daftar Produk Kami</h1>

<a href="/produk/create" class="btn btn-primary mb-3">Tambah Data</a>

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

<div class="card shadow-sm">
  <div class="card-header">
    Daftar Produk
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
        @foreach ($data_produk as $item)
        <tr>
          <td>{{ $loop->iteration }}</td>
          <td>{{ $item->nama_barang }}</td>
          <td>{{ $item->category }}</td>
          <td>{{ $item->unit }}</td>
          <td>{{ $item->created_at->format('d M Y') }}</td>
          <td class="text-center">
            <div class="d-flex justify-content-center gap-2">
              <a href="/produk/edit/{{ $item->barang_id }}" class="btn btn-warning btn-sm">Edit</a>
              <a href="/produk/hapus/{{ $item->barang_id }}" class="btn btn-danger btn-sm" onclick="return confirm('Yakin mau hapus?')">Hapus</a>
              <a href="/produk/{{ $item->barang_id }}" class="btn btn-info btn-sm">Detail</a>
            </div>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>
@endsection