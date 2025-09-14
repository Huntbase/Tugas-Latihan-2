@extends('layout.master')

@section('konten')
<h1>Dashboard Warehouse</h1>

<h3>Gudang: {{ $warehouse->name_id }}</h3>
<p>Lokasi: {{ $warehouse->location_id }}</p>
<p>Deskripsi: {{ $warehouse->description }}</p>

<hr>

<h4>Stok Barang</h4>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>No</th>
            <th>Nama Barang</th>
            <th>Stok</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($warehouse->stocks as $stock)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $stock->product->nama_barang ?? '-' }}</td>
            <td>{{ $stock->stock_quantity }}</td>
        </tr>
        @empty
        <tr>
            <td colspan="3" class="text-center">Belum ada stok barang di gudang ini</td>
        </tr>
        @endforelse
    </tbody>
</table>
@endsection