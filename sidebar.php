<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<style>
  body { background: #f4f6fb; margin: 0; }
  .sidebar {
    width: 240px; min-height: 100vh; background: #1a1a2e;
    position: fixed; top: 0; left: 0; z-index: 100;
    display: flex; flex-direction: column; overflow-y: auto;
  }
  .sidebar-brand {
    padding: 22px 20px 14px; color: #fff;
    font-size: 1.05rem; font-weight: 700;
    border-bottom: 1px solid rgba(255,255,255,0.08);
    letter-spacing: 0.5px;
  }
  .sidebar .nav-link {
    color: rgba(255,255,255,0.72); padding: 10px 20px;
    font-size: 0.88rem; border-radius: 0;
    display: flex; align-items: center; gap: 10px;
    transition: all 0.15s; text-decoration: none;
  }
  .sidebar .nav-link:hover, .sidebar .nav-link.active {
    background: rgba(255,255,255,0.12); color: #fff;
  }
  .sidebar .nav-link.logout-link { color: #ff7675; }
  .sidebar .nav-link.logout-link:hover { background: rgba(255,118,117,0.12); }
  .sub-menu { display: none; background: rgba(0,0,0,0.2); }
  .sub-menu.show { display: block; }
  .sub-menu .nav-link { font-size: 0.82rem; padding: 8px 20px 8px 40px; }
  .nav-arrow { margin-left: auto; font-size: 0.7rem; transition: transform 0.2s; }
  .rotate-arrow { transform: rotate(90deg); }
  .main-content { margin-left: 240px; padding: 28px 24px; }
  .row { margin-left: 0; margin-right: 0; }
  .card { border: none; border-radius: 12px; box-shadow: 0 2px 12px rgba(0,0,0,0.07); }
  .card-body { padding: 24px; }
  .table { margin-bottom: 0; }
  .table thead th { background: #f8f9fa; font-weight: 600; font-size: 0.85rem; border-top: none; }
  .btn { border-radius: 7px; font-size: 0.85rem; }
</style>

<?php
$in_penjualan = (strpos($_SERVER['PHP_SELF'], '/penjualan/') !== false);
$base         = $in_penjualan ? '../' : '';
$self         = basename($_SERVER['PHP_SELF']);
?>

<div class="sidebar">
  <div class="sidebar-brand">
    📋 Data Profile
  </div>
  <nav class="d-flex flex-column" style="flex:1;padding:8px 0;">
    <a href="<?= $base ?>view_data.php"
       class="nav-link <?= $self=='view_data.php'?'active':'' ?>">
      👥 View Biodata
    </a>
    <a href="<?= $base ?>tambah_biodata.php"
       class="nav-link <?= $self=='tambah_biodata.php'?'active':'' ?>">
      ➕ Tambah Biodata
    </a>
    <a href="<?= $base ?>penjualan/index.php"
       class="nav-link <?= ($self=='index.php'&&$in_penjualan)?'active':'' ?>">
      🛒 Entry Data Penjualan
    </a>
    <a href="<?= $base ?>penjualan/laporan_penjualan.php"
       class="nav-link <?= $self=='laporan_penjualan.php'?'active':'' ?>">
      📊 Laporan Penjualan
    </a>
    <a href="#" class="nav-link has-sub" id="products-link">
      📦 Products <span class="nav-arrow">▶</span>
    </a>
    <div class="sub-menu" id="products-submenu">
      <a href="<?= $base ?>produk_tampil.php"  class="nav-link <?= $self=='produk_tampil.php'?'active':'' ?>">Tampil Produk</a>
      <a href="<?= $base ?>produk_tambah.php"  class="nav-link <?= $self=='produk_tambah.php'?'active':'' ?>">Tambah Produk</a>
      <a href="<?= $base ?>produk_edit.php"    class="nav-link <?= $self=='produk_edit.php'?'active':'' ?>">Edit Produk</a>
      <a href="<?= $base ?>produk_delete.php"  class="nav-link <?= $self=='produk_delete.php'?'active':'' ?>">Hapus Produk</a>
    </div>
    <div style="flex:1;"></div>
    <a href="<?= $base ?>logout.php" class="nav-link logout-link">
      🚪 Logout
    </a>
  </nav>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
document.getElementById('products-link').addEventListener('click', function(e) {
  e.preventDefault();
  var sub   = document.getElementById('products-submenu');
  var arrow = this.querySelector('.nav-arrow');
  sub.classList.toggle('show');
  if (arrow) arrow.classList.toggle('rotate-arrow');
});
</script>