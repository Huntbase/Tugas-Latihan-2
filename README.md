<div id="top">
<h1 align="center">🏭 Warehouse Management System</h1>
<p align="center">
  <em>Sistem manajemen perpindahan stok antar gudang dengan alur persetujuan bertingkat dan penegakan segregation of duties.</em>
</p>

<p align="center">
  <a href="https://github.com/Huntbase/Warehouse-Management-System">
    <img src="https://img.shields.io/github/repo-size/Huntbase/Warehouse-Management-System?label=Repo%20Size&color=green&logo=github" alt="Repo Size">
  </a>
  <a href="https://github.com/Huntbase/Warehouse-Management-System/commits/main">
    <img src="https://img.shields.io/github/last-commit/Huntbase/Warehouse-Management-System?label=Last%20Commit&color=blueviolet&logo=git" alt="Last Commit">
  </a>
  <br />
  <img src="https://img.shields.io/badge/Laravel-11-FF2D20?logo=laravel&logoColor=white" alt="Laravel">
  <img src="https://img.shields.io/badge/PHP-8.x-777BB4?logo=php&logoColor=white" alt="PHP">
  <img src="https://img.shields.io/badge/MySQL-database-4479A1?logo=mysql&logoColor=white" alt="MySQL">
  <img src="https://img.shields.io/badge/Bootstrap-5.3-7952B3?logo=bootstrap&logoColor=white" alt="Bootstrap">
</p>
</div>

🔗 **Live Demo:** _[belum di-deploy — dalam proses]_
📦 **Repo:** [github.com/Huntbase/Warehouse-Management-System](https://github.com/Huntbase/Warehouse-Management-System)

---

## Masalah yang Diselesaikan

Sistem transfer stok antar gudang secara manual rentan terhadap penyalahgunaan wewenang — misalnya satu pihak yang sama bisa mengajukan sekaligus menyetujui transfer stok tanpa pengawasan. Sistem ini dirancang dengan **segregation of duties**: pihak yang mengajukan transfer tidak dapat menyetujui transfer yang sama, dan setiap transisi status divalidasi melalui *policy layer* terpisah.

---

## ✨ Fitur Utama

- **Role-Based Access Control (RBAC)** — tiga tingkat role (Admin, Supervisor, Staff) dengan hak akses berbeda, dikelola melalui tabel pivot `user_warehouse_assignments` untuk mengatur akses staf ke gudang tertentu
- **Alur Persetujuan Stock Transfer 6-State** — status transfer stok berjalan melalui state machine (draft → menunggu approval → disetujui → dikirim → diterima/ditolak), dikelola melalui `StockTransferApprovalService`
- **Segregation of Duties** — pengaju dan penyetuju transfer wajib berbeda pihak, ditegakkan lewat `StockTransferPolicy` via `Gate::policy()`
- **Audit & Keamanan** — proses code audit internal yang menemukan dan memperbaiki sejumlah bug arsitektur dan migrasi, termasuk potensi kebocoran data sensitif (password hash) ke log aplikasi
- **UI Modern** — dark/light mode dengan Bootstrap 5.3

---

## 🔧 Teknologi yang Digunakan

- **Backend:** Laravel 11 (PHP)
- **Database:** MySQL
- **Frontend:** Bootstrap 5.3
- **Arsitektur:** Service layer pattern (`StockTransferApprovalService`), Policy-based authorization (`StockTransferPolicy`)

---

## Desain Sistem

Proyek ini dirancang dari nol — mulai dari ERD, migrasi database, hingga service layer dan policy — bukan berdasarkan template atau boilerplate. Keputusan arsitektur utama:
- Pemisahan logic approval ke dedicated service class agar policy segregation of duties konsisten diterapkan di seluruh alur, bukan tersebar di controller
- Pivot table `user_warehouse_assignments` untuk mendukung staf yang bertugas di lebih dari satu gudang

---

## 🚀 Cara Menjalankan Lokal

```bash
git clone https://github.com/Huntbase/Warehouse-Management-System.git
cd Warehouse-Management-System
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

---

<p align="center"><em>Dibuat oleh Benediktus Mikael Auwdinata</em></p>
