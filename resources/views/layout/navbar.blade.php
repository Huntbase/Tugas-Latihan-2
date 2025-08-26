<style>
  /* Sidebar dasar */
  #sidebar {
    width: 250px;
    transition: width 0.3s, background-color 0.3s;
  }

  #sidebar.collapsed {
    width: 70px;
  }

  #sidebar.dark {
    background-color: #1e1e2f;
    color: #fff;
  }

  #sidebar.dark .nav-link {
    color: #fff;
  }

  #sidebar.dark .nav-link.active {
    background-color: #343454;
  }

  #sidebar .nav-link span {
    transition: opacity 0.3s;
  }

  #sidebar.collapsed .nav-link span {
    opacity: 0;
    pointer-events: none;
  }

  #sidebar .navbar-brand {
    transition: opacity 0.3s;
  }

  #sidebar.collapsed .navbar-brand {
    opacity: 0;
    pointer-events: none;
  }

  #sidebar .nav-link i {
    min-width: 30px;
    text-align: center;
  }

  .sidebar-arrow {
    transition: transform 0.3s;
  }

  .sidebar-arrow.rotate {
    transform: rotate(90deg);
  }

  .nav .collapse .nav-link {
    padding-left: 2rem;
  }

  .nav .collapse .collapse .nav-link {
    padding-left: 3rem;
  }

  /* Resize handle */
  #sidebar-resize {
    width: 5px;
    cursor: ew-resize;
    position: absolute;
    top: 0;
    right: 0;
    height: 100%;
    background-color: transparent;
  }
</style>

<div class="d-flex position-relative">
  <!-- Sidebar -->
  <nav id="sidebar" class="bg-light border-end vh-100 d-flex flex-column p-3 position-relative">
    <div id="sidebar-resize"></div>

    <!-- Logo -->
    <a class="navbar-brand mb-3 d-flex align-items-center" href="/">
      <img src="/logo.png" alt="Logo" width="30" class="me-2">
      <span>My Store</span>
    </a>

    <!-- Theme switch -->
    <button class="btn btn-secondary mb-3" id="themeToggle">Toggle Dark/Light</button>

    <!-- Toggle collapse mobile -->
    <button class="btn btn-secondary mb-3 d-lg-none" id="sidebarToggle">
      <i class="bi bi-list"></i>
    </button>

    <!-- Menu -->
    <ul class="nav flex-column flex-grow-1">

      <!-- Home -->
      <li class="nav-item mb-2">
        <a href="/" class="nav-link {{ request()->is('/') ? 'active' : '' }}" data-bs-toggle="tooltip" data-bs-placement="right" title="Home">
          <!-- Masukkan icon di sini -->
          <i class="bi bi-house-door-fill"></i>
          <span class="ms-2">Home</span>
        </a>
      </li>

      <!-- Product dengan submenu -->
      <li class="nav-item mb-2">
        <a class="nav-link d-flex justify-content-between align-items-center {{ request()->is('produk*') ? '' : 'collapsed' }}"
          data-bs-toggle="collapse"
          href="#produkMenu"
          role="button"
          aria-expanded="{{ request()->is('produk*') ? 'true' : 'false' }}"> <span>
            <!-- Masukkan icon di sini -->
            <i class="bi bi-box-seam"></i>
            <span class="ms-2">Product</span>
          </span>
          <i class="bi bi-caret-right-fill sidebar-arrow {{ request()->is('produk*') ? 'rotate' : '' }}"></i>
        </a>
        <div class="collapse {{ request()->is('produk*') ? 'show' : '' }}" id="produkMenu">
          <ul class="nav flex-column">
            <li class="nav-item">
              <a href="/produk" class="nav-link {{ request()->is('produk') ? 'active' : '' }}">
                <!-- Masukkan icon submenu di sini (opsional) -->
                <i class="bi bi-card-list"></i>
                <span class="ms-2">Daftar Produk</span>
              </a>
            </li>
            <li class="nav-item">
              <a href="/produk/create" class="nav-link {{ request()->is('produk/create') ? 'active' : '' }}">
                <!-- Masukkan icon submenu di sini (opsional) -->
                <i class="bi bi-plus-square"></i>
                <span class="ms-2">Tambah Produk</span>
              </a>
            </li>
          </ul>
        </div>
      </li>

      <!-- Audit Log -->
      <li class="nav-item mb-2">
        <a href="/auditLog" class="nav-link {{ request()->is('auditLog') ? 'active' : '' }}" data-bs-toggle="tooltip" data-bs-placement="right" title="Audit Log">
          <!-- Masukkan icon di sini -->
          <i class="bi bi-journal-text"></i>
          <span class="ms-2">Audit Log</span>
        </a>
      </li>

      <!-- Login -->
      <li class="nav-item mb-2">
        <a href="/login" class="nav-link {{ request()->is('login') ? 'active' : '' }}" data-bs-toggle="tooltip" data-bs-placement="right" title="Login">
          <!-- Masukkan icon di sini -->
          <i class="bi bi-person"></i>
          <span class="ms-2">Login</span>
        </a>
      </li>

    </ul>

    <!-- Logout -->
    <a href="/logout" class="btn btn-danger w-100 mt-auto" data-bs-toggle="tooltip" data-bs-placement="right" title="Logout">Logout</a>
  </nav>

  <!-- Konten utama -->
  <div class="flex-grow-1 p-4">
    <button class="btn btn-primary mb-3 d-none d-lg-block" id="sidebarToggleDesktop">
      <i class="bi bi-list"></i> Toggle Sidebar
    </button>
    @yield('konten')
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.getElementById('sidebar');
    const toggleBtn = document.getElementById('sidebarToggle');
    const toggleDesktopBtn = document.getElementById('sidebarToggleDesktop');
    const themeToggle = document.getElementById('themeToggle');

    // Toggle sidebar
    toggleBtn.addEventListener('click', () => sidebar.classList.toggle('collapsed'));
    toggleDesktopBtn.addEventListener('click', () => sidebar.classList.toggle('collapsed'));

    // Theme toggle
    themeToggle.addEventListener('click', () => sidebar.classList.toggle('dark'));

    // Tooltip
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function(tooltipTriggerEl) {
      return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Submenu arrow animation
    var collapseElements = document.querySelectorAll('#sidebar .collapse');
    collapseElements.forEach(function(collapseEl) {
      collapseEl.addEventListener('show.bs.collapse', function() {
        const arrow = collapseEl.previousElementSibling.querySelector('.sidebar-arrow');
        if (arrow) arrow.classList.add('rotate');
      });
      collapseEl.addEventListener('hide.bs.collapse', function() {
        const arrow = collapseEl.previousElementSibling.querySelector('.sidebar-arrow');
        if (arrow) arrow.classList.remove('rotate');
      });
    });

    // Sidebar resize
    const resizeHandle = document.getElementById('sidebar-resize');
    let isResizing = false;
    resizeHandle.addEventListener('mousedown', e => {
      isResizing = true;
    });
    document.addEventListener('mousemove', e => {
      if (!isResizing) return;
      let newWidth = e.clientX;
      if (newWidth < 70) newWidth = 70;
      if (newWidth > 400) newWidth = 400;
      sidebar.style.width = newWidth + 'px';
    });
    document.addEventListener('mouseup', e => {
      isResizing = false;
    });
  });
</script>