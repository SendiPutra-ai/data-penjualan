<?php
session_start();
if (!isset($_SESSION["authenticated"]) || !$_SESSION["authenticated"]) {
    header("Location: login_form.php");
    exit();
}
include 'koneksi.php';
 
$pesan = "";
 
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama   = $koneksi->real_escape_string($_POST["nama"]);
    $email  = $koneksi->real_escape_string($_POST["email"]);
    $alamat = $koneksi->real_escape_string($_POST["alamat"]);
 
    $allowed_types = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
    $gambar_path   = "";
 
    if (!empty($_FILES['gambar']['name'])) {
        $file_type = $_FILES['gambar']['type'];
        if (!in_array($file_type, $allowed_types)) {
            $pesan = "<div class='alert alert-danger'>Hanya file JPEG, PNG, GIF yang diperbolehkan.</div>";
        } else {
            $query_insert = "INSERT INTO `biodata` (`nama`, `email`, `alamat`) VALUES ('$nama', '$email', '$alamat')";
            if ($koneksi->query($query_insert) === TRUE) {
                $last_id    = $koneksi->insert_id;
                $ext        = pathinfo($_FILES["gambar"]["name"], PATHINFO_EXTENSION);
                $nama_file  = $last_id . "." . $ext;
                $target_dir = "uploads/";
                if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);
                $target_file = $target_dir . $nama_file;
                if (move_uploaded_file($_FILES["gambar"]["tmp_name"], $target_file)) {
                    $koneksi->query("UPDATE `biodata` SET `gambar`='$target_file' WHERE `id`='$last_id'");
                    $pesan = "<div class='alert alert-success'>Data berhasil ditambahkan.</div>";
                } else {
                    $pesan = "<div class='alert alert-warning'>Data tersimpan, gagal upload gambar.</div>";
                }
            } else {
                $pesan = "<div class='alert alert-danger'>Error: " . $koneksi->error . "</div>";
            }
        }
    } else {
        $query_insert = "INSERT INTO `biodata` (`nama`, `email`, `alamat`) VALUES ('$nama', '$email', '$alamat')";
        if ($koneksi->query($query_insert) === TRUE) {
            $pesan = "<div class='alert alert-success'>Data berhasil ditambahkan.</div>";
        } else {
            $pesan = "<div class='alert alert-danger'>Error: " . $koneksi->error . "</div>";
        }
    }
}
$koneksi->close();
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tambah Biodata</title>
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
    .btn { border-radius: 7px; font-size: 0.85rem; }
  </style>
</head>
<body>
 
<?php include 'sidebar.php'; ?>
 
<div class="main-content">
  <div style="margin-bottom:20px;">
    <h5 style="font-weight:700;margin:0;color:#1a1a2e;">Tambah Biodata</h5>
    <small style="color:#6c757d;"><a href="view_data.php" style="color:#0f3460;">← Kembali ke daftar</a></small>
  </div>
 
  <div class="row">
    <div class="col-md-6">
      <div class="card">
        <div class="card-body">
          <?= $pesan ?>
          <form method="post" enctype="multipart/form-data">
            <div class="mb-3">
              <label class="form-label fw-semibold">Nama</label>
              <input type="text" name="nama" class="form-control" required>
            </div>
            <div class="mb-3">
              <label class="form-label fw-semibold">Email</label>
              <input type="email" name="email" class="form-control" required>
            </div>
            <div class="mb-3">
              <label class="form-label fw-semibold">Alamat</label>
              <input type="text" name="alamat" class="form-control" required>
            </div>
            <div class="mb-3">
              <label class="form-label fw-semibold">Gambar (opsional)</label>
              <input type="file" name="gambar" class="form-control" accept="image/*">
            </div>
            <button type="submit" class="btn btn-primary w-100">Simpan Data</button>
          </form>
        </div>
      </div>
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
 