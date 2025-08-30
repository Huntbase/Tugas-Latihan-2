<style>
  /* ===========================
   Import Font
=========================== */
  @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

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
  }

  .sidebar.close {
    width: 88px;
  }

  /* ---------------------------
   Sidebar Header
--------------------------- */
  .sidebar header {
    position: relative;
  }

  .sidebar header .image-text {
    display: flex;
    align-items: center;
  }

  .sidebar header .image-text img,
  header .image-text .img {
    width: 40px;
    border-radius: 6px;
  }

  .sidebar header .toggle {
    position: absolute;
    top: 50%;
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
    transition: var(--trans-03);
  }

  .sidebar.close header .toggle {
    transform: translateY(-50%);
  }

  body.dark .sidebar header .toggle {
    transform: rotate(180deg);
    color: var(--text-color);
  }

  /* ---------------------------
   Sidebar Text & Image
--------------------------- */
  .sidebar .text {
    font-size: 16px;
    font-weight: 500;
    color: var(--text-color);
    transition: var(--trans-03);
    white-space: nowrap;
    opacity: 1;
  }

  .sidebar.close .text {
    display: none;
    opacity: 0;
  }

  .sidebar .image {
    min-width: 60px;
    display: flex;
    align-items: center;
  }

  /* ---------------------------
   Sidebar List Items
--------------------------- */
  .sidebar ul {
    padding-left: 0;
  }

  .sidebar li {
    height: 50px;
    margin-top: 10px;
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
    border-radius: 6px;
    transition: var(--trans-04);
  }

  .sidebar li a:hover {
    background: var(--primary-color);
  }

  .sidebar li a:hover .icon,
  .sidebar li a:hover .text {
    color: var(--sidebar-color);
  }

  body.dark .sidebar li a:hover .icon,
  body.dark .sidebar li a:hover .text {
    color: var(--text-color);
  }

  .sidebar li .icon {
    display: flex;
    align-items: center;
    justify-content: center;
    min-width: 60px;
    font-size: 20px;
    color: var(--text-color);
  }

  .sidebar li .text {
    color: var(--text-color);
  }

  /* ===========================
   Menu Bar & Dark Mode
=========================== */
  .menu-bar {
    height: calc(100% - 50px);
    display: flex;
    flex-direction: column;
    justify-content: space-between;
  }

  .menu-bar .mode {
    position: relative;
    border-radius: 6px;
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
    border-radius: 6px;
    background: var(--primary-color-light);
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
    height: 50px;
    width: 60px;
    display: flex;
    align-items: center;
  }

  /* ===========================
   Search Box
=========================== */
  .sidebar .search-box {
    background: var(--primary-color-light);
    border-radius: 6px;
  }

  .search-box input {
    width: 100%;
    height: 100%;
    border: none;
    outline: none;
    font-size: 16px;
    font-weight: 500;
    background: var(--primary-color-light);
    border-radius: 6px;
  }
</style>
<nav class="sidebar close">
  <header>
    <div class="image-text">
      <span class="image">
        <img src="profile.jpg" alt="profile">
      </span>
      <div class="text header-text">
        <span class="name">CodingLab</span>
        <span class="profession">Web Developer</span>
      </div>
    </div>

    <i class='bx bx-chevron-right toggle'></i>
  </header>

  <div class="menu-bar">
    <div class="menu">
      <li class="search-box">
        <i class="bx bx-search icon"></i>
        <input type="search" placeholder="Search...">
      </li>
      <ul class="menu-links">
        <li class="nav-link">
          <a href="/dashboard">
            <i class="bx bx-grid-alt icon"></i>
            <span class="text nav-text">Dashboard</span>
          </a>
        </li>
        <li class="nav-link">
          <a href="/produk">
            <i class="bx bx-box icon"></i>
            <span class="text nav-text">Produk</span>
          </a>
        </li>
        <li class="nav-link">
          <a href="/addProduk">
            <i class="bx bx-cart-add icon"></i>
            <span class="text nav-text">Tambah Produk</span>
          </a>
        </li>
        <li class="nav-link">
          <a href="/auditLog">
            <i class="bx bx-clipboard icon"></i>
            <span class="text nav-text">Audit Log</span>
          </a>
        </li>
      </ul>
    </div>

    <div class="bottom-content">
      <li class="">
        <a href="">
          <i class="bx bx-log-out icon"></i>
          <span class="text nav-text">Logout</span>
        </a>
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
    </div>
  </div>
</nav>

<script>
  const body = document.querySelector("body"),
    sidebar = document.querySelector(".sidebar"),
    toggle = document.querySelector(".toggle"),
    searchBtn = document.querySelector(".search-box"),
    modeSwitch = document.querySelector(".toggle-switch"),
    modeText = document.querySelector(".mode-text");

  toggle.addEventListener("click", () => {
    sidebar.classList.toggle("close");
  });
  modeSwitch.addEventListener("click", () => {
    body.classList.toggle("dark");
  });

  if (body.classList.contains("dark")) {
    modeText.innerText = "Light Mode"
  } else {
    modeText.innerText = "Dark Mode"
  }
</script>