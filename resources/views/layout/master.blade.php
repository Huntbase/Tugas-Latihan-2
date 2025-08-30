<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Aplikasi Produk</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<style>
  html,
  body {
    height: 100%;
  }

  body {
    display: flex;
    min-height: 100vh;
  }

  /* Konten utama */
  .content-wrapper {
    display: flex;
    flex-direction: column;
    flex: 1;
    /* supaya isi+footer memenuhi ruang */
    margin-left: 250px;
    /* default lebar sidebar */
    transition: all 0.3s ease;
  }

  main {
    flex: 1;
    /* supaya footer turun ke bawah */
    padding: 20px;
  }

  /* Jika sidebar ditutup */
  .sidebar.close~.content-wrapper {
    margin-left: 88px;
    /* sesuaikan dengan sidebar close */
  }
</style>

<body>

  @include('layout.navbar')

  <!-- Wrapper konten + footer -->
  <div class="content-wrapper">

    <main>
      <div class="container mt-4">
        @yield('konten')
      </div>
    </main>

    @include('layout.footer')

  </div>

  <!-- Script -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  @stack('scripts')
</body>

</html>