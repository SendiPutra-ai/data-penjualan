<?php
session_start();
if (!isset($_SESSION["authenticated"]) || !$_SESSION["authenticated"]) {
    header("Location: login_form.php");
    exit();
}
require 'koneksi.php';
?>
<?php include 'sidebar.php'; ?>
<div class="main-content">
  <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;">
    <div>
      <h5 style="font-weight:700;margin:0;color:#1a1a2e;">Daftar Produk</h5>
      <small style="color:#6c757d;">Kelola data produk</small>
    </div>
    <a href="produk_tambah.php" class="btn btn-primary btn-sm">➕ Tambah Produk</a>
  </div>

  <div class="card">
    <div class="card-body">
      <?php
      $query  = "SELECT * FROM produk";
      $result = $koneksi->query($query);
      ?>
      <div class="table-responsive">
        <table class="table table-hover align-middle">
          <thead>
            <tr>
              <th style="width:80px;">ID</th>
              <th>Nama Barang</th>
              <th>Harga</th>
              <th style="width:160px;">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php if ($result && $result->num_rows > 0): ?>
              <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                  <td><?= $row['id'] ?></td>
                  <td><?= htmlspecialchars($row['nama_brg']) ?></td>
                  <td>Rp <?= number_format($row['harga'], 0, ',', '.') ?></td>
                  <td>
                    <a href="produk_edit.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning">✏️ Edit</a>
                    <a href="produk_delete.php?id=<?= $row['id'] ?>"
                       class="btn btn-sm btn-danger"
                       onclick="return confirm('Hapus produk ini?');">🗑️</a>
                  </td>
                </tr>
              <?php endwhile; ?>
            <?php else: ?>
              <tr><td colspan="4" class="text-center text-muted py-4">Tidak ada data produk.</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
      <?php $koneksi->close(); ?>
    </div>
  </div>
</div>