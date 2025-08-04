@extends('layout.master')

@section('konten')

<div class="container mt-5">
  <h2 class="mb-4">List of Products</h2>

  <table class="table table-hover caption-top">
    <thead>
      <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Price</th>
        <th>Stock</th>
        <th>Created At</th>
        <th>Updated At</th>
        <th>Edit</th>
        <th>Delete</th>
      </tr>
    </thead>
    <tbody class="table-group-divider">
      <tr>
        <th scope="row">1</th>
        <td>Produk A</td>
        <td>Rp 10.000</td>
        <td>50</td>
        <td><small class="text-muted">2025-07-30 14:00</small></td>
        <td><small class="text-muted">2025-07-30 14:30</small></td>
        <td>
          <a href="#" class="btn btn-sm btn-outline-primary">Edit</a>
        </td>
        <td>
          <form action="#" method="POST" onsubmit="return confirm('Yakin ingin menghapus?')">
            @csrf
            @method('DELETE')
            <button class="btn btn-sm btn-outline-danger">Delete</button>
          </form>
        </td>
      </tr>
      <tr>
        <th scope="row">2</th>
        <td>Produk B</td>
        <td>Rp 25.000</td>
        <td>30</td>
        <td><small class="text-muted">2025-07-29 10:00</small></td>
        <td><small class="text-muted">2025-07-29 11:30</small></td>
        <td>
          <a href="#" class="btn btn-sm btn-outline-primary">Edit</a>
        </td>
        <td>
          <form action="#" method="POST" onsubmit="return confirm('Yakin ingin menghapus?')">
            @csrf
            @method('DELETE')
            <button class="btn btn-sm btn-outline-danger">Delete</button>
          </form>
        </td>
      </tr>
    </tbody>
  </table>
</div>

@endsection