<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Aplikasi Produk</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<style>
  /* ===========================
   Global Reset
=========================== */
  * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Poppins', sans-serif;
  }

  /* ===========================
   CSS Variables
=========================== */
  :root {
    --body-color: #e4e9f7;
    --sidebar-color: #fff;
    --primary-color: #695CFE;
    --primary-color-light: #f6f5ff;
    --toggle-color: #ddd;
    --text-color: #707070;

    --trans-02: all 0.2s ease;
    --trans-03: all 0.3s ease;
    --trans-04: all 0.4s ease;
    --trans-05: all 0.5s ease;
  }

  body.dark {
    --body-color: #18191a;
    --sidebar-color: #242526;
    --primary-color: #3a3b3c;
    --primary-color-light: #3a3b3c;
    --toggle-color: #fff;
    --text-color: #ccc;
  }

  html,
  body {
    height: 100%;
  }

  body {
    font-family: 'Poppins', sans-serif;
    display: flex;
    min-height: 100vh;
    background-color: var(--body-color);
    color: var(--text-color);
    transition: var(--trans-03);
  }

  /* Konten utama */
  .content-wrapper {
    display: flex;
    flex-direction: column;
    flex: 1;
    min-height: 100vh;
    margin-left: 250px;
    transition: var(--trans-03);
  }

  main {
    flex: 1;
    padding: 20px;
  }

  /* Jika sidebar ditutup */
  .sidebar.close~.content-wrapper {
    margin-left: 88px;
  }
</style>

<body>
  {{-- Sidebar / Navbar --}}
  @include('layout.navbar')

  {{-- Wrapper konten + footer --}}
  <div class="content-wrapper">

    <main>
      <div class="container mt-4">
        @yield('konten')
      </div>
    </main>

    {{-- Footer HARUS di dalam content-wrapper --}}
    @include('layout.footer')

  </div>

  {{-- Script --}}
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  @stack('scripts')
</body>

</html>