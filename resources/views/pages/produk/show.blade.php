@extends('layout.master')

@section('konten')
<h1> Daftar Produk Kami </h1>
<hr>
<a href="/produk/tambah" type="button" class="btn btn-primary mb-3"> Tambah Data </a>

<div class="alert alert-primary">
  <b> Nama Toko : </b> {{ $data_toko['nama_toko'] }}
  <br>
  <b> Alamat : </b> {{ $data_toko['alamat'] }}
  <br>
  <b> Tipe Toko : </b> {{ $data_toko['type'] }}
</div>

<div class="card">
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
          <td>{{ $item->barang_id }}</td>
          <td>{{ $item->nama_barang }}</td>
          <td>{{ $item->category }}</td>
          <td>{{ $item->unit }}</td>
          <td>{{ $item->created_at->format('d-m-Y') }}</td>
          <td>
            <a href="/produk/edit/{{ $item->id }}" class="btn btn-warning btn-sm">Edit</a>
            <a href="/produk/hapus/{{ $item->id }}" class="btn btn-danger btn-sm" onclick="return confirm('Yakin mau hapus?')">Hapus</a>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>
@endsection