<?php
session_start();
if (!isset($_SESSION["authenticated"]) || !$_SESSION["authenticated"]) {
    header("Location: ../login_form.php"); exit();
}
include '../koneksi.php';
?>
<?php include '../sidebar.php'; ?>
<div class="main-content">
  <div style="margin-bottom:20px;">
    <h5 style="font-weight:700;margin:0;color:#1a1a2e;">Laporan Penjualan</h5>
    <small style="color:#6c757d;">Filter berdasarkan tanggal</small>
  </div>

  <div class="card mb-3">
    <div class="card-body">
      <form action="laporan_penjualan.php" method="GET" class="row g-2 align-items-end">
        <div class="col-md-4">
          <label class="form-label fw-semibold" style="font-size:.85rem;">Tanggal Mulai</label>
          <input type="date" name="tanggal_mulai" class="form-control"
                 value="<?= isset($_GET['tanggal_mulai']) ? $_GET['tanggal_mulai'] : '' ?>" required>
        </div>
        <div class="col-md-4">
          <label class="form-label fw-semibold" style="font-size:.85rem;">Tanggal Selesai</label>
          <input type="date" name="tanggal_selesai" class="form-control"
                 value="<?= isset($_GET['tanggal_selesai']) ? $_GET['tanggal_selesai'] : '' ?>" required>
        </div>
        <div class="col-md-4">
          <button type="submit" class="btn btn-primary w-100">Tampilkan</button>
        </div>
      </form>
    </div>
  </div>

  <?php if (isset($_GET['tanggal_mulai']) && isset($_GET['tanggal_selesai'])): ?>
    <?php
    $tgl_mulai   = $_GET['tanggal_mulai'] . " 00:00:00";
    $tgl_selesai = $_GET['tanggal_selesai'] . " 23:59:59";
    $query       = "SELECT * FROM penjualan WHERE tanggal BETWEEN '$tgl_mulai' AND '$tgl_selesai' ORDER BY tanggal ASC";
    $result      = $koneksi->query($query);
    $total_penjualan = 0;
    ?>
    <div class="card">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <span style="font-size:.88rem;color:#6c757d;">
            Periode: <strong><?= $_GET['tanggal_mulai'] ?></strong> s/d <strong><?= $_GET['tanggal_selesai'] ?></strong>
          </span>
          <button onclick="window.print()" class="btn btn-sm btn-outline-secondary">🖨️ Cetak</button>
        </div>
        <div class="table-responsive">
          <table class="table table-hover table-sm align-middle">
            <thead>
              <tr>
                <th>ID</th>
                <th>No. Struk</th>
                <th>Nama Barang</th>
                <th class="text-center">Qty</th>
                <th class="text-end">Harga</th>
                <th class="text-end">Total</th>
                <th>Tanggal</th>
              </tr>
            </thead>
            <tbody>
              <?php if ($result && $result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                  <?php $total_penjualan += $row['total']; ?>
                  <tr>
                    <td><?= $row['id_jual'] ?></td>
                    <td><span class="badge bg-secondary"><?= $row['no_struk'] ?></span></td>
                    <td><?= htmlspecialchars($row['nama_brg']) ?></td>
                    <td class="text-center"><?= $row['qty_jual'] ?></td>
                    <td class="text-end">Rp <?= number_format($row['harga_brg'],0,',','.') ?></td>
                    <td class="text-end">Rp <?= number_format($row['total'],0,',','.') ?></td>
                    <td style="font-size:.82rem;"><?= $row['tanggal'] ?></td>
                  </tr>
                <?php endwhile; ?>
              <?php else: ?>
                <tr><td colspan="7" class="text-center text-muted py-4">Tidak ada data pada periode ini.</td></tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
        <div class="text-end mt-2">
          <strong>Total Penjualan: Rp <?= number_format($total_penjualan, 0, ',', '.') ?></strong>
        </div>
      </div>
    </div>
  <?php endif; ?>
</div>
<?php $koneksi->close(); ?>