<?php
session_start();
if (!isset($_SESSION["authenticated"]) || !$_SESSION["authenticated"]) {
    header("Location: login_form.php");
    exit();
}
$pesan = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require 'koneksi.php';
    $nama_brg = $_POST['nama_brg'];
    $harga    = $_POST['harga'];
    $query    = "INSERT INTO produk (nama_brg, harga) VALUES ('$nama_brg', '$harga')";
    $result   = $koneksi->query($query);
    if ($result) {
        $pesan = "<div class='alert alert-success'>Data produk berhasil ditambahkan!</div>";
    } else {
        $pesan = "<div class='alert alert-danger'>Error: " . $koneksi->error . "</div>";
    }
    $koneksi->close();
}
?>
<?php include 'sidebar.php'; ?>
<div class="main-content">
  <div style="margin-bottom:20px;">
    <h5 style="font-weight:700;margin:0;color:#1a1a2e;">Tambah Produk</h5>
    <small><a href="produk_tampil.php" style="color:#0f3460;">← Kembali ke daftar</a></small>
  </div>
  <div class="row">
    <div class="col-md-5">
      <div class="card">
        <div class="card-body">
          <?= $pesan ?>
          <form action="<?= $_SERVER['PHP_SELF'] ?>" method="post">
            <div class="mb-3">
              <label class="form-label fw-semibold">Nama Barang</label>
              <input type="text" class="form-control" name="nama_brg" required>
            </div>
            <div class="mb-3">
              <label class="form-label fw-semibold">Harga</label>
              <input type="number" class="form-control" name="harga" required min="0">
            </div>
            <button type="submit" class="btn btn-primary w-100">Simpan</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>