<?php
session_start();
if (!isset($_SESSION["authenticated"]) || !$_SESSION["authenticated"]) {
    header("Location: login_form.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard - Data Profile</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <style>
    body { background: #f4f6fb; }
    .sidebar {
      width: 240px; min-height: 100vh; background: #1a1a2e;
      position: fixed; top: 0; left: 0; z-index: 100;
      display: flex; flex-direction: column;
    }
    .sidebar-brand {
      padding: 24px 20px 16px; color: #fff;
      font-size: 1.1rem; font-weight: 700;
      border-bottom: 1px solid rgba(255,255,255,0.08);
    }
    .sidebar .nav-link {
      color: rgba(255,255,255,0.7); padding: 10px 20px;
      font-size: 0.9rem; border-radius: 0;
      display: flex; align-items: center; gap: 10px;
      transition: all 0.15s;
    }
    .sidebar .nav-link:hover, .sidebar .nav-link.active {
      background: rgba(255,255,255,0.1); color: #fff;
    }
    .sidebar .nav-link.logout-link {
      color: #ff6b6b;
      margin-top: auto;
    }
    .main-content { margin-left: 240px; padding: 32px 28px; }
    .page-header { margin-bottom: 24px; }
    .page-header h4 { font-weight: 700; color: #1a1a2e; margin: 0; }
    .page-header small { color: #6c757d; font-size: 0.85rem; }
    .stat-card {
      background: #fff; border-radius: 14px; padding: 24px;
      box-shadow: 0 2px 12px rgba(0,0,0,0.06);
      border: 1px solid #f0f0f0;
      transition: transform 0.15s;
    }
    .stat-card:hover { transform: translateY(-2px); }
    .stat-icon {
      width: 48px; height: 48px; border-radius: 12px;
      display: flex; align-items: center; justify-content: center;
      font-size: 1.4rem; margin-bottom: 12px;
    }
    .stat-value { font-size: 1.7rem; font-weight: 700; color: #1a1a2e; }
    .stat-label { color: #6c757d; font-size: 0.85rem; margin-top: 2px; }
    .sub-menu { display: none; padding-left: 16px; }
    .sub-menu.show { display: block; }
    .sub-menu .nav-link { font-size: 0.83rem; padding: 7px 20px; }
    .nav-arrow { margin-left: auto; transition: transform 0.2s; font-size: 0.75rem; }
    .rotate-arrow { transform: rotate(90deg); }
  </style>
</head>
<body>

<?php include 'sidebar.php'; ?>

<div class="main-content">
  <div class="page-header">
    <h4>Dashboard</h4>
    <small>Selamat datang, <?= htmlspecialchars($_SESSION['email'] ?? 'Admin') ?></small>
  </div>

  <?php
  require 'koneksi.php';
  $total_biodata = $koneksi->query("SELECT COUNT(*) as c FROM biodata")->fetch_assoc()['c'] ?? 0;
  $total_produk  = $koneksi->query("SELECT COUNT(*) as c FROM produk")->fetch_assoc()['c'] ?? 0;
  $total_transaksi = $koneksi->query("SELECT COUNT(DISTINCT no_struk) as c FROM penjualan")->fetch_assoc()['c'] ?? 0;
  $total_omzet   = $koneksi->query("SELECT SUM(total) as c FROM penjualan")->fetch_assoc()['c'] ?? 0;
  ?>

  <div class="row g-3">
    <div class="col-md-3">
      <div class="stat-card">
        <div class="stat-icon" style="background:#eef2ff;">👤</div>
        <div class="stat-value"><?= $total_biodata ?></div>
        <div class="stat-label">Total Biodata</div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="stat-card">
        <div class="stat-icon" style="background:#fff3e0;">📦</div>
        <div class="stat-value"><?= $total_produk ?></div>
        <div class="stat-label">Total Produk</div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="stat-card">
        <div class="stat-icon" style="background:#e8f5e9;">🧾</div>
        <div class="stat-value"><?= $total_transaksi ?></div>
        <div class="stat-label">Total Transaksi</div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="stat-card">
        <div class="stat-icon" style="background:#fce4ec;">💰</div>
        <div class="stat-value">Rp <?= number_format($total_omzet, 0, ',', '.') ?></div>
        <div class="stat-label">Total Omzet</div>
      </div>
    </div>
  </div>

  <div class="row g-3 mt-2">
    <div class="col-12">
      <div class="stat-card">
        <h6 style="font-weight:700;margin-bottom:16px;">Menu Navigasi Cepat</h6>
        <div class="d-flex flex-wrap gap-2">
          <a href="view_data.php" class="btn btn-sm btn-outline-primary">👥 View Biodata</a>
          <a href="tambah_biodata.php" class="btn btn-sm btn-outline-success">➕ Tambah Biodata</a>
          <a href="produk_tampil.php" class="btn btn-sm btn-outline-warning">📦 Produk</a>
          <a href="penjualan/" class="btn btn-sm btn-outline-info">🛒 Entri Penjualan</a>
          <a href="penjualan/laporan_penjualan.php" class="btn btn-sm btn-outline-secondary">📊 Laporan</a>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.querySelectorAll('.has-sub').forEach(function(link) {
  link.addEventListener('click', function(e) {
    e.preventDefault();
    var sub = this.nextElementSibling;
    var arrow = this.querySelector('.nav-arrow');
    if (sub) sub.classList.toggle('show');
    if (arrow) arrow.classList.toggle('rotate-arrow');
  });
});
</script>
</body>
</html>