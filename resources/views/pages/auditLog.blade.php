@extends('layout.master')

@section('konten')

<table class="table table-hover caption-top">
  <caption>List of users</caption>
  <thead>
    <tr>
      <th scope="col">id</th>
      <th scope="col">User</th>
      <th scope="col">Nama Product</th>
      <th scope="col">Action</th>
      <th scope="col">Harga Dulu</th>
      <th scope="col">Harga Baru</th>
      <th scope="col">Diupdate</th>
    </tr>
  </thead>
  <tbody class="table-group-divider">
    <tr>
      <th scope="row">1</th>
      <td>Mark</td>
      <td>Otto</td>
      <td>@mdo</td>
    </tr>
    <tr>
      <th scope="row">2</th>
      <td>Jacob</td>
      <td>Thornton</td>
      <td>@fat</td>
    </tr>
    <tr>
      <th scope="row">3</th>
      <td>John</td>
      <td>Doe</td>
      <td>@social</td>
    </tr>
  </tbody>
</table>
@endsection