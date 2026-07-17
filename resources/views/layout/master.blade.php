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
     CSS Variables (light mode / default)
  =========================== */
  :root {
    --body-color: #e4e9f7;
    --sidebar-color: #fff;
    --primary-color: #695CFE;
    --primary-color-light: #f6f5ff;
    --toggle-color: #ddd;
    --text-color: #707070;
    --border-color: rgba(0, 0, 0, 0.08);

    --trans-02: all 0.2s ease;
    --trans-03: all 0.3s ease;
    --trans-04: all 0.4s ease;
    --trans-05: all 0.5s ease;
  }

  /* ===========================
     Dark mode variables
  =========================== */
  body.dark {
    --body-color: #18191a;
    --sidebar-color: #242526;
    --primary-color: #3a3b3c;
    --primary-color-light: #3a3b3c;
    --toggle-color: #fff;
    --text-color: #ccc;
    --border-color: rgba(255, 255, 255, 0.08);
  }

  /*
    This is the key part: Bootstrap 5.3 components (card, table, form
    controls, modal, dropdown, list-group, etc.) all read from these
    --bs-* custom properties instead of hardcoded colors. Overriding
    them here makes every Bootstrap component follow dark mode
    automatically, without needing to override each component by hand.
  */
  body.dark {
    --bs-body-bg: var(--body-color);
    --bs-body-color: var(--text-color);
    --bs-border-color: var(--border-color);
    --bs-border-color-translucent: var(--border-color);
    --bs-secondary-color: var(--text-color);
    --bs-tertiary-bg: var(--primary-color-light);
    --bs-emphasis-color: #fff;
    --bs-link-color: #a89bff;
    --bs-link-hover-color: #c3baff;

    --bs-card-bg: var(--sidebar-color);
    --bs-card-color: var(--text-color);
    --bs-card-cap-bg: var(--primary-color-light);
    --bs-card-border-color: var(--border-color);

    --bs-table-bg: var(--sidebar-color);
    --bs-table-color: var(--text-color);
    --bs-table-border-color: var(--border-color);
    --bs-table-striped-bg: rgba(255, 255, 255, 0.03);
    --bs-table-hover-bg: rgba(255, 255, 255, 0.05);

    --bs-modal-bg: var(--sidebar-color);
    --bs-modal-color: var(--text-color);
    --bs-modal-header-border-color: var(--border-color);
    --bs-modal-footer-border-color: var(--border-color);

    --bs-dropdown-bg: var(--sidebar-color);
    --bs-dropdown-color: var(--text-color);
    --bs-dropdown-link-color: var(--text-color);
    --bs-dropdown-link-hover-bg: var(--primary-color-light);
    --bs-dropdown-border-color: var(--border-color);

    --bs-form-control-bg: var(--sidebar-color);

    --bs-pagination-bg: var(--sidebar-color);
    --bs-pagination-color: var(--text-color);
    --bs-pagination-border-color: var(--border-color);
    --bs-pagination-hover-bg: var(--primary-color-light);
    --bs-pagination-hover-color: var(--text-color);
    --bs-pagination-disabled-bg: var(--sidebar-color);
    --bs-pagination-disabled-color: #555;
  }

  /* Form inputs/selects need explicit color too - Bootstrap doesn't
     fully theme these via --bs-form-control-bg alone */
  body.dark .form-control,
  body.dark .form-select {
    background-color: var(--sidebar-color);
    color: var(--text-color);
    border-color: var(--border-color);
  }

  body.dark .form-control:focus,
  body.dark .form-select:focus {
    background-color: var(--sidebar-color);
    color: var(--text-color);
  }

  body.dark .form-control::placeholder {
    color: var(--text-color);
    opacity: 0.5;
  }

  body.dark .btn-outline-secondary {
    color: var(--text-color);
    border-color: var(--border-color);
  }

  body.dark .btn-outline-secondary:hover {
    background-color: var(--primary-color-light);
    color: #fff;
  }

  body.dark .text-muted {
    color: #999 !important;
  }

  body.dark ::-webkit-scrollbar {
    width: 8px;
  }

  body.dark ::-webkit-scrollbar-track {
    background: var(--body-color);
  }

  body.dark ::-webkit-scrollbar-thumb {
    background: #444;
    border-radius: 8px;
  }

  /* Smooth transition when switching themes */
  body,
  .card,
  .table,
  .form-control,
  .form-select,
  .modal-content,
  .dropdown-menu,
  .page-link {
    transition: background-color 0.25s ease, color 0.25s ease, border-color 0.25s ease;
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