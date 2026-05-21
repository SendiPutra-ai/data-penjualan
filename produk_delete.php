<?php
session_start();
if (!isset($_SESSION["authenticated"]) || !$_SESSION["authenticated"]) {
    header("Location: login_form.php");
    exit();
}
?>
<?php include 'sidebar.php'; ?>
<div class="main-content">
  <div style="margin-bottom:20px;">
    <h5 style="font-weight:700;margin:0;color:#1a1a2e;">Hapus Produk</h5>
    <small><a href="produk_tampil.php" style="color:#0f3460;">← Kembali ke daftar</a></small>
  </div>
  <div class="row">
    <div class="col-md-5">
      <div class="card">
        <div class="card-body">
          <?php
          require 'koneksi.php';

          if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['tampilkan'])) {
              $id_barang = (int)$_POST['id_barang'];
              $query     = "SELECT * FROM produk WHERE id='$id_barang'";
              $result    = $koneksi->query($query);
              if ($result && $result->num_rows > 0) {
                  $row = $result->fetch_assoc();
                  echo '<div class="alert alert-info">';
                  echo "<strong>Nama:</strong> " . htmlspecialchars($row['nama_brg']);
                  echo " &nbsp;|&nbsp; <strong>Harga:</strong> Rp " . number_format($row['harga'], 0, ',', '.');
                  echo '</div>';
                  echo "<form action='" . $_SERVER['PHP_SELF'] . "' method='post'>";
                  echo "<input type='hidden' name='id_barang' value='" . $id_barang . "'>";
                  echo "<button type='submit' class='btn btn-danger w-100' name='hapus'>🗑️ Konfirmasi Hapus</button>";
                  echo "</form>";
              } else {
                  echo '<div class="alert alert-warning">Data tidak ditemukan.</div>';
              }
          }

          if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['hapus'])) {
              $id_barang = (int)$_POST['id_barang'];
              $query     = "DELETE FROM produk WHERE id='$id_barang'";
              $result    = $koneksi->query($query);
              if ($result) {
                  echo '<div class="alert alert-success">Data berhasil dihapus!</div>';
              } else {
                  echo '<div class="alert alert-danger">Error: ' . $koneksi->error . '</div>';
              }
          }
          ?>
          <form action="<?= $_SERVER['PHP_SELF'] ?>" method="post">
            <div class="mb-3">
              <label class="form-label fw-semibold">Nomor ID Barang</label>
              <input type="number" class="form-control" name="id_barang" required>
            </div>
            <button type="submit" class="btn btn-primary w-100" name="tampilkan">Tampilkan Data</button>
          </form>
          <?php $koneksi->close(); ?>
        </div>
      </div>
    </div>
  </div>
</div>