<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Aplikasi Produk</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>

<body>
  @include('layout.navbar')

  <div class="container mt-3">
    @yield('konten')
  </div>

  @include('layout.footer')

  <!-- Tambahkan Bootstrap JS sebelum penutup body -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>