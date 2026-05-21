<?php
session_start();
if (!isset($_SESSION["authenticated"]) || !$_SESSION["authenticated"]) {
    header("Location: ../login_form.php"); exit();
}
include '../koneksi.php';

$no_struk    = "";
$show_struk  = false;
$error_msg   = "";
$transaksi   = [];
$grand_total = 0;

$q_nomor = "SELECT DISTINCT no_struk FROM penjualan ORDER BY no_struk DESC";
$res_nom = $koneksi->query($q_nomor);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['no_struk'])) {
    $no_struk = (int)$_POST['no_struk'];
    $q_tr     = "SELECT id_brg, nama_brg, harga_brg, qty_jual, total FROM penjualan WHERE no_struk = ?";
    $stmt_tr  = $koneksi->prepare($q_tr);
    $stmt_tr->bind_param('i', $no_struk);
    $stmt_tr->execute();
    $res_tr = $stmt_tr->get_result();

    if ($res_tr->num_rows > 0) {
        $show_struk = true;
        while ($row = $res_tr->fetch_assoc()) {
            $transaksi[]  = $row;
            $grand_total += $row['total'];
        }
    } else {
        $error_msg = "Nomor struk tidak ditemukan.";
    }
}
?>
<?php include '../sidebar.php'; ?>
<div class="main-content">
  <div style="margin-bottom:20px;">
    <h5 style="font-weight:700;margin:0;color:#1a1a2e;">Cetak Ulang Struk</h5>
    <small><a href="index.php" style="color:#0f3460;">← Kembali ke Transaksi</a></small>
  </div>

  <div class="row g-3">
    <div class="col-md-4">
      <div class="card">
        <div class="card-body">
          <form action="" method="POST">
            <div class="mb-3">
              <label class="form-label fw-semibold" style="font-size:.85rem;">Pilih Nomor Struk</label>
              <select name="no_struk" class="form-select" required>
                <option value="">-- Pilih Nomor Struk --</option>
                <?php if ($res_nom): while ($r = $res_nom->fetch_assoc()): ?>
                  <option value="<?= $r['no_struk'] ?>"
                    <?= ($r['no_struk'] == $no_struk) ? 'selected' : '' ?>>
                    <?= $r['no_struk'] ?>
                  </option>
                <?php endwhile; endif; ?>
              </select>
            </div>
            <button type="submit" class="btn btn-primary w-100">Tampilkan Struk</button>
          </form>
          <?php if ($error_msg): ?>
            <div class="alert alert-warning mt-3" style="font-size:.85rem;"><?= $error_msg ?></div>
          <?php endif; ?>
        </div>
      </div>
    </div>

    <?php if ($show_struk): ?>
    <div class="col-md-5">
      <div class="card" style="border:1.5px dashed #dee2e6;">
        <div class="card-body">
          <div class="text-center mb-3">
            <strong style="font-size:1.05rem;">🧾 Struk Pembelanjaan</strong><br>
            <small class="text-muted">Tanggal: <?= date('d/m/Y H:i:s') ?></small><br>
            <small class="text-muted">No. Struk: <?= $no_struk ?></small>
          </div>
          <table class="table table-sm" style="font-size:.85rem;">
            <thead>
              <tr style="border-bottom:1.5px dashed #ccc;">
                <th>Barang</th>
                <th class="text-center">Qty</th>
                <th class="text-end">Total</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($transaksi as $d): ?>
                <tr style="border-bottom:1px dashed #eee;">
                  <td>
                    <div style="font-weight:600;"><?= htmlspecialchars($d['nama_brg']) ?></div>
                    <small class="text-muted">Rp <?= number_format($d['harga_brg'],0,',','.') ?>/pcs</small>
                  </td>
                  <td class="text-center"><?= $d['qty_jual'] ?></td>
                  <td class="text-end">Rp <?= number_format($d['total'],0,',','.') ?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
          <div class="text-end mt-2">
            <strong>Grand Total: Rp <?= number_format($grand_total, 0, ',', '.') ?></strong>
          </div>
          <div class="mt-3 d-grid">
            <button onclick="window.print()" class="btn btn-primary">🖨️ Cetak Struk</button>
          </div>
        </div>
      </div>
    </div>
    <?php endif; ?>
  </div>
</div>
<?php $koneksi->close(); ?>