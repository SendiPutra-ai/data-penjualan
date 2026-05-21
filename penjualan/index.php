<?php
session_start();
if (!isset($_SESSION["authenticated"]) || !$_SESSION["authenticated"]) {
    header("Location: ../login_form.php");
    exit();
}
include '../koneksi.php';

$query  = "SELECT * FROM produk";
$result = $koneksi->query($query);

if (!isset($_GET['no_struk'])) {
    $no_struk = rand(100000, 999999);
} else {
    $no_struk = (int)$_GET['no_struk'];
}
?>
<?php include '../sidebar.php'; ?>
<div class="main-content">
  <div style="margin-bottom:20px;">
    <h5 style="font-weight:700;margin:0;color:#1a1a2e;">Entri Data Penjualan</h5>
    <small style="color:#6c757d;">No. Struk: <strong><?= $no_struk ?></strong></small>
  </div>

  <div class="row g-3">
    <div class="col-md-5">
      <div class="card">
        <div class="card-body">
          <h6 class="fw-bold mb-3">Input Barang</h6>
          <form action="proses_penjualan.php" method="POST">
            <input type="hidden" name="no_struk" value="<?= $no_struk ?>">
            <div class="mb-3">
              <label class="form-label fw-semibold" style="font-size:.85rem;">ID Barang</label>
              <div class="input-group">
                <input type="text" name="id_brg" id="id_brg" class="form-control" placeholder="Masukkan ID">
                <button type="button" class="btn btn-secondary btn-sm" onclick="populateName()">Cari</button>
              </div>
            </div>
            <div class="mb-3">
              <label class="form-label fw-semibold" style="font-size:.85rem;">Atau Pilih Produk</label>
              <select name="id_brg_select" id="id_brg_select" class="form-select"
                      onchange="document.getElementById('id_brg').value=this.value; populateName();">
                <option value="">-- Pilih Produk --</option>
                <?php if ($result): while ($row = $result->fetch_assoc()): ?>
                  <option value="<?= $row['id'] ?>"><?= htmlspecialchars($row['nama_brg']) ?> - Rp <?= number_format($row['harga'],0,',','.') ?></option>
                <?php endwhile; endif; ?>
              </select>
            </div>
            <div class="mb-3">
              <label class="form-label fw-semibold" style="font-size:.85rem;">Nama Barang</label>
              <input type="text" id="nama_brg" class="form-control" style="background:#f8f9fa;" disabled>
            </div>
            <div class="mb-3">
              <label class="form-label fw-semibold" style="font-size:.85rem;">Harga Satuan</label>
              <input type="text" id="harga_brg" class="form-control" style="background:#f8f9fa;" disabled>
            </div>
            <div class="mb-3">
              <label class="form-label fw-semibold" style="font-size:.85rem;">Jumlah</label>
              <input type="number" name="qty_jual" id="qty_jual" class="form-control" required min="1" value="1">
            </div>
            <button type="submit" class="btn btn-success w-100 fw-semibold">➕ Tambah ke Transaksi</button>
          </form>
        </div>
      </div>
    </div>

    <div class="col-md-7">
      <div class="card">
        <div class="card-body">
          <h6 class="fw-bold mb-3">Transaksi Struk: <?= $no_struk ?></h6>
          <?php
          $query_penjualan  = "SELECT * FROM penjualan WHERE no_struk = $no_struk";
          $result_penjualan = $koneksi->query($query_penjualan);
          $grand_total = 0;
          ?>
          <div class="table-responsive">
            <table class="table table-sm table-hover align-middle">
              <thead>
                <tr>
                  <th>Nama Barang</th>
                  <th class="text-center">Qty</th>
                  <th class="text-end">Harga</th>
                  <th class="text-end">Total</th>
                  <th class="text-center">Aksi</th>
                </tr>
              </thead>
              <tbody>
                <?php if ($result_penjualan && $result_penjualan->num_rows > 0): ?>
                  <?php while ($row_p = $result_penjualan->fetch_assoc()): ?>
                    <?php $grand_total += $row_p['total']; ?>
                    <tr>
                      <td><?= htmlspecialchars($row_p['nama_brg']) ?></td>
                      <td class="text-center"><?= $row_p['qty_jual'] ?></td>
                      <td class="text-end">Rp <?= number_format($row_p['harga_brg'],0,',','.') ?></td>
                      <td class="text-end">Rp <?= number_format($row_p['total'],0,',','.') ?></td>
                      <td class="text-center">
                        <a href="hapus_transaksi.php?id_jual=<?= $row_p['id_jual'] ?>&no_struk=<?= $no_struk ?>"
                           class="btn btn-sm btn-danger"
                           onclick="return confirm('Hapus item ini?');">🗑️</a>
                      </td>
                    </tr>
                  <?php endwhile; ?>
                <?php else: ?>
                  <tr><td colspan="5" class="text-center text-muted py-3">Belum ada item.</td></tr>
                <?php endif; ?>
              </tbody>
            </table>
          </div>

          <div class="text-end mt-2">
            <strong>Grand Total: Rp <?= number_format($grand_total, 0, ',', '.') ?></strong>
          </div>

          <hr>
          <h6 class="fw-bold mb-2">Pembayaran</h6>
          <form action="proses_pembayaran.php" method="POST">
            <input type="hidden" name="no_struk" value="<?= $no_struk ?>">
            <div class="mb-3">
              <label class="form-label fw-semibold" style="font-size:.85rem;">Uang Dibayarkan</label>
              <input type="number" name="bayar" class="form-control" required min="0">
            </div>
            <button type="submit" class="btn btn-primary w-100 fw-semibold">💳 Proses Pembayaran</button>
          </form>

          <div class="mt-3 text-center">
            <a href="../menu.php" class="btn btn-outline-secondary btn-sm">Selesai / Transaksi Baru</a>
            <a href="cetak_ulang_struk.php" class="btn btn-outline-info btn-sm ms-2">🖨️ Cetak Ulang</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
function populateName() {
  var id_brg = document.getElementById('id_brg').value;
  if (!id_brg) return;
  var xhr = new XMLHttpRequest();
  xhr.open('GET', 'get_nama_harga_barang.php?id_brg=' + id_brg, true);
  xhr.onreadystatechange = function() {
    if (xhr.readyState == 4 && xhr.status == 200) {
      var response = JSON.parse(xhr.responseText);
      document.getElementById('nama_brg').value  = response.nama_brg;
      document.getElementById('harga_brg').value = 'Rp ' + Number(response.harga_brg).toLocaleString('id-ID');
    }
  };
  xhr.send();
}
</script>