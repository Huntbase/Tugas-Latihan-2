<style>
  /* ===========================
     Body
  =========================== */
  body {
    height: 100vh;
    background-color: var(--body-color);
  }

  /* ===========================
     Sidebar
  =========================== */
  .sidebar {
    position: fixed;
    top: 0;
    left: 0;
    height: 100%;
    width: 250px;
    padding: 10px 14px;
    background: var(--sidebar-color);
    transition: var(--trans-05);
    z-index: 100;
  }

  .sidebar.close {
    width: 88px;
  }

  /* ---------------------------
     Sidebar Header
  --------------------------- */
  .sidebar header {
    position: relative;
    border-bottom: 1px solid var(--primary-color-light);
    padding-bottom: 12px;
    margin-bottom: 8px;
  }

  .sidebar header .image-text {
    display: flex;
    align-items: center;
  }

  .sidebar header .image-text img,
  header .image-text .img {
    border-radius: 6px;
  }

  .sidebar header .toggle {
    position: absolute;
    top: 40%;
    right: -25px;
    transform: translateY(-50%) rotate(180deg);
    height: 25px;
    width: 25px;
    border-radius: 50%;
    background-color: var(--primary-color);
    color: var(--sidebar-color);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
    cursor: pointer;
    transition: var(--trans-03);
  }

  .sidebar.close header .toggle {
    transform: translateY(-50%);
  }

  body.dark .sidebar header .toggle {
    transform: rotate(180deg);
    color: var(--text-color);
  }

  body.dark .sidebar.close header .toggle {
    transform: translateY(-50%);
  }

  /* ---------------------------
     Sidebar Text & Image
  --------------------------- */
  .sidebar .text {
    font-size: 15px;
    font-weight: 500;
    color: var(--text-color);
    transition: var(--trans-03);
    white-space: nowrap;
    opacity: 1;
  }

  .sidebar .header-text .name {
    font-family: 'Poppins', sans-serif;
    font-weight: 600;
    font-size: 17px;
  }

  .sidebar.close .text {
    display: none;
    opacity: 0;
  }

  .sidebar .image-text img {
    width: 40px;
    height: 40px;
    object-fit: cover;
    border-radius: 6px;
  }

  .sidebar .image {
    min-width: 50px;
    display: flex;
    align-items: center;
  }

  /* ---------------------------
     Sidebar List Items
  --------------------------- */
  .sidebar ul {
    padding-left: 0;
    margin-bottom: 0;
  }

  .sidebar li {
    height: 46px;
    margin-top: 4px;
    list-style: none;
    display: flex;
    align-items: center;
  }

  .sidebar li a {
    height: 100%;
    width: 100%;
    display: flex;
    align-items: center;
    text-decoration: none;
    border-radius: 8px;
    transition: var(--trans-04);
  }

  .sidebar li a:hover {
    background: var(--primary-color-light);
  }

  .sidebar li a.active {
    background: var(--primary-color);
  }

  .sidebar li a.active .icon,
  .sidebar li a.active .text {
    color: var(--sidebar-color);
    font-weight: 600;
  }

  body.dark .sidebar li a.active .icon,
  body.dark .sidebar li a.active .text {
    color: var(--text-color);
  }

  .sidebar li .icon {
    display: flex;
    align-items: center;
    justify-content: center;
    min-width: 50px;
    font-size: 19px;
    color: var(--text-color);
  }

  .sidebar li .text {
    color: var(--text-color);
  }

  /* Section label (e.g. "ADMIN") shown above grouped links */
  .sidebar .section-label {
    height: auto;
    margin-top: 14px;
    margin-bottom: 4px;
    padding-left: 12px;
    font-size: 11px;
    font-weight: 600;
    letter-spacing: 0.06em;
    text-transform: uppercase;
    color: var(--text-color);
    opacity: 0.45;
  }

  .sidebar.close .section-label {
    display: none;
  }

  /* ===========================
     Menu Bar & Dark Mode
  =========================== */
  .menu-bar {
    height: calc(100% - 70px);
    overflow-y: auto;
    overflow-x: hidden;
    padding-bottom: 25px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
  }

  .menu-bar::-webkit-scrollbar {
    width: 0;
  }

  .menu-bar .mode {
    position: relative;
    border-radius: 8px;
    background: var(--primary-color-light);
  }

  .menu-bar .mode i.sun {
    position: absolute;
    transition: var(--trans-03);
    opacity: 0;
  }

  body.dark .menu-bar .mode i.sun {
    opacity: 1;
  }

  body.dark .menu-bar .mode i.moon {
    opacity: 0;
  }

  .menu-bar .mode .toggle-switch {
    position: absolute;
    display: flex;
    align-items: center;
    justify-content: center;
    right: 0;
    height: 100%;
    min-width: 60px;
    cursor: pointer;
    border-radius: 8px;
  }

  .toggle-switch .switch {
    position: relative;
    height: 22px;
    width: 44px;
    border-radius: 25px;
    background: var(--toggle-color);
  }

  .switch::before {
    content: '';
    position: absolute;
    height: 15px;
    width: 15px;
    border-radius: 50%;
    top: 50%;
    left: 5px;
    transform: translateY(-50%);
    background: var(--sidebar-color);
    transition: var(--trans-03);
  }

  body.dark .switch::before {
    left: 24px;
  }

  .menu-bar .mode .moon-sun {
    height: 46px;
    width: 50px;
    display: flex;
    align-items: center;
    justify-content: center;
  }

  .menu-bar .bottom-content {
    border-top: 1px solid var(--primary-color-light);
    padding-top: 8px;
  }

  .menu-bar .logout a,
  .menu-bar .logout button {
    height: 46px;
    width: 100%;
    display: flex;
    align-items: center;
    border-radius: 8px;
    transition: var(--trans-04);
  }

  .menu-bar .logout button:hover {
    background: var(--primary-color-light);
  }

  /* ===========================
     Search Box
  =========================== */
  .sidebar .search-box {
    background: var(--primary-color-light);
    border-radius: 8px;
    margin-bottom: 10px;
  }

  .search-box input {
    width: 100%;
    height: 100%;
    border: none;
    outline: none;
    font-size: 15px;
    font-weight: 500;
    background: transparent;
    border-radius: 8px;
    color: var(--text-color);
  }

  .search-box input::placeholder {
    color: var(--text-color);
    opacity: 0.5;
  }
</style>

<nav class="sidebar close">
  <header>
    <div class="image-text">
      <span class="image">
        <img src="{{ asset('images/warehouse.png') }}" alt="Logo">
      </span>
      <div class="text header-text">
        <span class="name">Warehouse</span>
      </div>
    </div>
    <i class='bx bx-chevron-right toggle'></i>
  </header>

  <div class="menu-bar">
    <div class="menu">
      <ul>
        <li class="search-box">
          <i class="bx bx-search icon"></i>
          <input type="search" placeholder="Search...">
        </li>
      </ul>

      <ul class="menu-links">
        <li class="nav-link">
          <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="bx bx-grid-alt icon"></i>
            <span class="text nav-text">Dashboard</span>
          </a>
        </li>
        <li class="nav-link">
          <a href="{{ route('warehouse.index') }}" class="{{ request()->routeIs('warehouse.*') ? 'active' : '' }}">
            <i class="bx bx-store icon"></i>
            <span class="text nav-text">Warehouse</span>
          </a>
        </li>
        <li class="nav-link">
          <a href="{{ route('produk.index') }}" class="{{ request()->routeIs('produk.*') ? 'active' : '' }}">
            <i class="bx bx-box icon"></i>
            <span class="text nav-text">Daftar Produk</span>
          </a>
        </li>
        <li class="nav-link">
          <a href="{{ route('stock-transfers.index') }}" class="{{ request()->routeIs('stock-transfers.*') ? 'active' : '' }}">
            <i class="bx bx-transfer icon"></i>
            <span class="text nav-text">Stock Transfer</span>
          </a>
        </li>

        @auth
        @if(in_array(auth()->user()->role_id, [1, 2]))
        <li class="section-label">Admin</li>
        @endif

        @if(auth()->user()->role_id == 1)
        <li class="nav-link">
          <a href="{{ route('Data_users.index') }}" class="{{ request()->routeIs('Data_users.*') ? 'active' : '' }}">
            <i class="bx bx-user icon"></i>
            <span class="text nav-text">Manage User &amp; Role</span>
          </a>
        </li>
        @endif

        @if(in_array(auth()->user()->role_id, [1, 2]))
        <li class="nav-link">
          <a href="{{ route('auditLog.index') }}" class="{{ request()->routeIs('auditLog.*') ? 'active' : '' }}">
            <i class="bx bx-clipboard icon"></i>
            <span class="text nav-text">Audit Log</span>
          </a>
        </li>
        @endif
        @endauth
      </ul>
    </div>

    <div class="bottom-content">
      <ul>
        <li class="logout">
          <form action="{{ route('logout') }}" method="POST" class="w-100">
            @csrf
            <button type="submit" class="d-flex align-items-center text-start" style="background:none; border:none; padding:0;">
              <i class="bx bx-log-out icon"></i>
              <span class="text nav-text">Logout</span>
            </button>
          </form>
        </li>
        <li class="mode">
          <div class="moon-sun">
            <i class="bx bx-moon icon moon"></i>
            <i class="bx bx-sun icon sun"></i>
          </div>
          <span class="mode-text text">Dark Mode</span>

          <div class="toggle-switch">
            <span class="switch"></span>
          </div>
        </li>
      </ul>
    </div>
  </div>
</nav>

<script>
  const body = document.querySelector("body"),
    sidebar = document.querySelector(".sidebar"),
    toggle = document.querySelector(".toggle"),
    modeSwitch = document.querySelector(".toggle-switch"),
    modeText = document.querySelector(".mode-text");

  // Restore saved theme on load
  if (localStorage.getItem("theme") === "dark") {
    body.classList.add("dark");
    modeText.innerText = "Light Mode";
  } else {
    modeText.innerText = "Dark Mode";
  }

  // Sidebar collapse/expand
  toggle.addEventListener("click", () => {
    sidebar.classList.toggle("close");
  });

  // Dark mode toggle
  modeSwitch.addEventListener("click", () => {
    body.classList.toggle("dark");

    if (body.classList.contains("dark")) {
      modeText.innerText = "Light Mode";
      localStorage.setItem("theme", "dark");
    } else {
      modeText.innerText = "Dark Mode";
      localStorage.setItem("theme", "light");
    }
  });
</script>