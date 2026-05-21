<?php
session_start();
if (!isset($_SESSION["authenticated"]) || !$_SESSION["authenticated"]) {
    header("Location: ../login_form.php"); exit();
}
include '../koneksi.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: index.php"); exit();
}

if (!isset($_POST['no_struk']) || !isset($_POST['bayar']) || !is_numeric($_POST['bayar'])) {
    echo "Parameter tidak valid!"; exit();
}

$no_struk = (int)$_POST['no_struk'];
$bayar    = (int)$_POST['bayar'];

$q_total  = "SELECT SUM(total) as grand_total FROM penjualan WHERE no_struk = ?";
$stmt     = $koneksi->prepare($q_total);
$stmt->bind_param('i', $no_struk);
$stmt->execute();
$res_tot  = $stmt->get_result();

if ($res_tot->num_rows == 0) {
    echo "Transaksi tidak ditemukan."; exit();
}

$data_total  = $res_tot->fetch_assoc();
$grand_total = (int)$data_total['grand_total'];

$q_det  = "SELECT id_brg, nama_brg, harga_brg, qty_jual, total FROM penjualan WHERE no_struk = ?";
$stmt_d = $koneksi->prepare($q_det);
$stmt_d->bind_param('i', $no_struk);
$stmt_d->execute();
$res_det = $stmt_d->get_result();
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Struk Pembelanjaan</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <style>
    body { background: #f4f6fb; }
    .struk-card {
      max-width: 420px; margin: 40px auto;
      background: #fff; border-radius: 16px;
      box-shadow: 0 4px 24px rgba(0,0,0,0.1); overflow: hidden;
    }
    .struk-header {
      background: #1a1a2e; color: #fff;
      padding: 20px 24px; text-align: center;
    }
    .struk-header h5 { margin:0; font-weight:700; font-size:1.1rem; }
    .struk-header small { opacity:.7; font-size:.82rem; }
    .struk-body { padding: 20px 24px; }
    .struk-table { width: 100%; font-size: .88rem; }
    .struk-table td { padding: 6px 0; }
    .struk-divider { border-top: 1.5px dashed #dee2e6; margin: 10px 0; }
    .summary-row { display:flex; justify-content:space-between; font-size:.9rem; margin-bottom:6px; }
    .grand-total { font-weight:700; font-size:1.05rem; color:#1a1a2e; }
    .kembalian { font-weight:700; color:#0b8a00; font-size:1.05rem; }
    .gagal-card { max-width:400px; margin:60px auto; }
    @media print { .no-print { display: none !important; } }
  </style>
</head>
<body>
<?php if ($bayar >= $grand_total): ?>
  <?php $kembalian = $bayar - $grand_total; ?>
  <div class="struk-card">
    <div class="struk-header">
      <h5>🧾 Struk Pembelanjaan</h5>
      <small>Tanggal: <?= date('d/m/Y H:i:s') ?></small><br>
      <small>No. Struk: <?= $no_struk ?></small>
    </div>
    <div class="struk-body">
      <table class="struk-table">
        <thead>
          <tr style="border-bottom:1.5px dashed #ccc;">
            <th style="padding:6px 0;">Barang</th>
            <th class="text-center" style="padding:6px 0;">Qty</th>
            <th class="text-end" style="padding:6px 0;">Total</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($row = $res_det->fetch_assoc()): ?>
            <tr style="border-bottom:1px dashed #eee;">
              <td style="padding:7px 0;">
                <div style="font-weight:600;"><?= htmlspecialchars($row['nama_brg']) ?></div>
                <div style="font-size:.8rem;color:#6c757d;">Rp <?= number_format($row['harga_brg'],0,',','.') ?>/pcs</div>
              </td>
              <td class="text-center" style="padding:7px 0;"><?= $row['qty_jual'] ?></td>
              <td class="text-end" style="padding:7px 0;">Rp <?= number_format($row['total'],0,',','.') ?></td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>

      <div class="struk-divider"></div>
      <div class="summary-row">
        <span>Grand Total</span>
        <span class="grand-total">Rp <?= number_format($grand_total, 0, ',', '.') ?></span>
      </div>
      <div class="summary-row">
        <span>Dibayar</span>
        <span>Rp <?= number_format($bayar, 0, ',', '.') ?></span>
      </div>
      <div class="struk-divider"></div>
      <div class="summary-row">
        <span>Kembalian</span>
        <span class="kembalian">Rp <?= number_format($kembalian, 0, ',', '.') ?></span>
      </div>

      <div class="mt-3 d-grid gap-2 no-print">
        <button onclick="window.print()" class="btn btn-primary">🖨️ Cetak Struk</button>
        <a href="index.php" class="btn btn-outline-secondary">Transaksi Baru</a>
      </div>
    </div>
  </div>

<?php else: ?>
  <div class="gagal-card card shadow-sm border-0">
    <div class="card-body text-center p-4">
      <div style="font-size:3rem;margin-bottom:12px;">❌</div>
      <h5 class="fw-bold text-danger">Pembayaran Gagal</h5>
      <p class="text-muted">Uang yang dibayarkan kurang dari grand total.</p>
      <p><strong>Grand Total:</strong> Rp <?= number_format($grand_total, 0, ',', '.') ?></p>
      <p><strong>Dibayar:</strong> Rp <?= number_format($bayar, 0, ',', '.') ?></p>
      <a href="index.php?no_struk=<?= $no_struk ?>" class="btn btn-primary w-100">Kembali</a>
    </div>
  </div>
<?php endif; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>