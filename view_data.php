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
  <title>Data Biodata</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
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
    .card { border: none; border-radius: 12px; box-shadow: 0 2px 12px rgba(0,0,0,0.07); }
    .card-body { padding: 24px; }
    .table thead th { background: #f8f9fa; font-weight: 600; font-size: 0.85rem; }
    .btn { border-radius: 7px; font-size: 0.85rem; }
  </style>
</head>
<body>
 
<?php include 'sidebar.php'; ?>
 
<div class="main-content">
  <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;">
    <div>
      <h5 style="font-weight:700;margin:0;color:#1a1a2e;">Data Biodata</h5>
      <small style="color:#6c757d;">Kelola data biodata pengguna</small>
    </div>
    <a href="tambah_biodata.php" class="btn btn-primary btn-sm">➕ Tambah Data</a>
  </div>
 
  <div class="card">
    <div class="card-body">
      <?php
      include 'koneksi.php';
      $query  = "SELECT `id`, `nama`, `email`, `alamat`, `gambar` FROM `biodata`";
      $result = $koneksi->query($query);
      if (!$result) {
          die("Query gagal: " . $koneksi->error);
      }
      ?>
      <div class="table-responsive">
        <table class="table table-hover align-middle">
          <thead>
            <tr>
              <th>ID</th>
              <th>Nama</th>
              <th>Email</th>
              <th>Alamat</th>
              <th>Gambar</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php if ($result->num_rows > 0): ?>
              <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                  <td><?= $row["id"] ?></td>
                  <td><?= htmlspecialchars($row["nama"]) ?></td>
                  <td><?= htmlspecialchars($row["email"]) ?></td>
                  <td><?= htmlspecialchars($row["alamat"]) ?></td>
                  <td>
                    <?php if (!empty($row["gambar"]) && file_exists($row["gambar"])): ?>
                      <img src="<?= $row["gambar"] ?>?t=<?= time() ?>" height="50" style="border-radius:6px;object-fit:cover;">
                    <?php else: ?>
                      <span style="color:#adb5bd;font-size:0.8rem;">-</span>
                    <?php endif; ?>
                  </td>
                  <td>
                    <a href="edit_data.php?id=<?= $row["id"] ?>" class="btn btn-sm btn-warning">✏️ Edit</a>
                    <a href="delete_data.php?id=<?= $row["id"] ?>"
                       class="btn btn-sm btn-danger"
                       onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?');">🗑️ Hapus</a>
                  </td>
                </tr>
              <?php endwhile; ?>
            <?php else: ?>
              <tr><td colspan="6" class="text-center text-muted py-4">Tidak ada data.</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
      <?php $koneksi->close(); ?>
    </div>
  </div>
</div>
 
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.getElementById('products-link') && document.getElementById('products-link').addEventListener('click', function(e) {
  e.preventDefault();
  var sub   = document.getElementById('products-submenu');
  var arrow = this.querySelector('.nav-arrow');
  sub.classList.toggle('show');
  if (arrow) arrow.classList.toggle('rotate-arrow');
});
</script>
</body>
</html>
 