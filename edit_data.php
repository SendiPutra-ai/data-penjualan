<?php
session_start();
if (!isset($_SESSION["authenticated"]) || !$_SESSION["authenticated"]) {
    header("Location: login_form.php");
    exit();
}
include 'koneksi.php';
?>
<?php include 'sidebar.php'; ?>
<div class="main-content">
  <div style="margin-bottom:20px;">
    <h5 style="font-weight:700;margin:0;color:#1a1a2e;">Edit Biodata</h5>
    <small style="color:#6c757d;"><a href="view_data.php" style="color:#0f3460;">← Kembali ke daftar</a></small>
  </div>

  <div class="row">
    <div class="col-md-6">
      <div class="card">
        <div class="card-body">
          <?php
          if (isset($_GET['id'])) {
              $id    = (int)$_GET['id'];
              $query = "SELECT `id`, `nama`, `email`, `alamat`, `gambar` FROM `biodata` WHERE `id` = ?";
              $stmt  = $koneksi->prepare($query);
              $stmt->bind_param("i", $id);
              $stmt->execute();
              $result = $stmt->get_result();
              $data   = $result->fetch_assoc();

              if ($data):
          ?>
          <form method="post" enctype="multipart/form-data" action="edit_data.php">
            <input type="hidden" name="id" value="<?= $data['id'] ?>">
            <div class="mb-3">
              <label class="form-label fw-semibold">Nama</label>
              <input type="text" class="form-control" name="nama" value="<?= htmlspecialchars($data['nama']) ?>" required>
            </div>
            <div class="mb-3">
              <label class="form-label fw-semibold">Email</label>
              <input type="email" class="form-control" name="email" value="<?= htmlspecialchars($data['email']) ?>" required>
            </div>
            <div class="mb-3">
              <label class="form-label fw-semibold">Alamat</label>
              <input type="text" class="form-control" name="alamat" value="<?= htmlspecialchars($data['alamat']) ?>" required>
            </div>
            <div class="mb-3">
              <label class="form-label fw-semibold">Gambar</label>
              <input type="file" class="form-control" name="gambar" accept="image/*">
              <?php if (!empty($data['gambar']) && file_exists($data['gambar'])): ?>
                <div class="mt-2">
                  <small class="text-muted">Gambar saat ini:</small><br>
                  <img src="<?= $data['gambar'] ?>" height="80" style="border-radius:8px;margin-top:6px;">
                </div>
              <?php endif; ?>
            </div>
            <button type="submit" name="submit" class="btn btn-warning w-100 fw-semibold">Update Data</button>
          </form>
          <?php
              else:
                  echo "<div class='alert alert-danger'>Data tidak ditemukan.</div>";
              endif;
              $stmt->close();
          }

          if (isset($_POST['submit'])) {
              $id     = (int)$_POST['id'];
              $nama   = $_POST['nama'];
              $email  = $_POST['email'];
              $alamat = $_POST['alamat'];
              $gambar = '';

              if (!empty($_FILES['gambar']['name'])) {
                  $allowed_types = ['image/jpeg','image/jpg','image/png','image/gif'];
                  $file_type     = $_FILES['gambar']['type'];
                  if (!in_array($file_type, $allowed_types)) {
                      echo "<div class='alert alert-danger'>Hanya file gambar yang diperbolehkan.</div>";
                  } else {
                      $stmt_get = $koneksi->prepare("SELECT gambar FROM biodata WHERE id=?");
                      $stmt_get->bind_param("i", $id);
                      $stmt_get->execute();
                      $stmt_get->bind_result($old_gambar);
                      $stmt_get->fetch();
                      $stmt_get->close();

                      if (!empty($old_gambar) && file_exists($old_gambar)) {
                          unlink($old_gambar);
                      }

                      $ext        = pathinfo($_FILES["gambar"]["name"], PATHINFO_EXTENSION);
                      $nama_file  = $id . "." . $ext;
                      $target_dir = "uploads/";
                      if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);
                      $target_file = $target_dir . $nama_file;

                      if (move_uploaded_file($_FILES["gambar"]["tmp_name"], $target_file)) {
                          $gambar = $target_file;
                      }
                  }
              }

              if ($gambar) {
                  $q    = "UPDATE `biodata` SET `nama`=?, `email`=?, `alamat`=?, `gambar`=? WHERE `id`=?";
                  $stmt = $koneksi->prepare($q);
                  $stmt->bind_param("ssssi", $nama, $email, $alamat, $gambar, $id);
              } else {
                  $q    = "UPDATE `biodata` SET `nama`=?, `email`=?, `alamat`=? WHERE `id`=?";
                  $stmt = $koneksi->prepare($q);
                  $stmt->bind_param("sssi", $nama, $email, $alamat, $id);
              }

              if ($stmt->execute()) {
                  header("Location: view_data.php");
                  exit();
              } else {
                  echo "<div class='alert alert-danger'>Update gagal: " . $stmt->error . "</div>";
              }
              $stmt->close();
          }

          $koneksi->close();
          ?>
        </div>
      </div>
    </div>
  </div>
</div>