<?php
session_start();
if (!isset($_SESSION["authenticated"]) || !$_SESSION["authenticated"]) {
    header("Location: login_form.php");
    exit();
}
$pesan = "";
?>
<?php include 'sidebar.php'; ?>
<div class="main-content">
  <div style="margin-bottom:20px;">
    <h5 style="font-weight:700;margin:0;color:#1a1a2e;">Edit Produk</h5>
    <small><a href="produk_tampil.php" style="color:#0f3460;">← Kembali ke daftar</a></small>
  </div>
  <div class="row">
    <div class="col-md-5">
      <div class="card">
        <div class="card-body">
          <?php
          require 'koneksi.php';

          if (isset($_GET['id'])) {
              $id_get = (int)$_GET['id'];
              $row    = $koneksi->query("SELECT * FROM produk WHERE id='$id_get'")->fetch_assoc();
          }

          if ($_SERVER["REQUEST_METHOD"] == "POST") {
              $id_barang = (int)$_POST['id_barang'];
              $nama_brg  = $_POST['nama_brg'];
              $harga     = $_POST['harga'];
              $query     = "UPDATE produk SET nama_brg='$nama_brg', harga='$harga' WHERE id='$id_barang'";
              $result    = $koneksi->query($query);
              if ($result) {
                  $pesan = "<div class='alert alert-success'>Data berhasil diupdate!</div>";
              } else {
                  $pesan = "<div class='alert alert-danger'>Error: " . $koneksi->error . "</div>";
              }
          }
          echo $pesan;
          ?>
          <form action="<?= $_SERVER['PHP_SELF'] ?>" method="post">
            <div class="mb-3">
              <label class="form-label fw-semibold">ID Barang</label>
              <input type="number" class="form-control" name="id_barang"
                     value="<?= isset($id_get) ? $id_get : '' ?>" required>
            </div>
            <div class="mb-3">
              <label class="form-label fw-semibold">Nama Barang Baru</label>
              <input type="text" class="form-control" name="nama_brg"
                     value="<?= isset($row['nama_brg']) ? htmlspecialchars($row['nama_brg']) : '' ?>" required>
            </div>
            <div class="mb-3">
              <label class="form-label fw-semibold">Harga Baru</label>
              <input type="number" class="form-control" name="harga"
                     value="<?= isset($row['harga']) ? $row['harga'] : '' ?>" required min="0">
            </div>
            <button type="submit" class="btn btn-warning w-100 fw-semibold">Update</button>
          </form>
          <?php $koneksi->close(); ?>
        </div>
      </div>
    </div>
  </div>
</div>
