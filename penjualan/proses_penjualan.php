<?php
session_start();
if (!isset($_SESSION["authenticated"]) || !$_SESSION["authenticated"]) {
    header("Location: ../login_form.php"); exit();
}
include '../koneksi.php';

$no_struk = (int)$_POST['no_struk'];
$id_brg   = (int)$_POST['id_brg'];
$qty_jual = (int)$_POST['qty_jual'];
$tanggal  = date('Y-m-d H:i:s');

$query = "SELECT nama_brg, harga FROM produk WHERE id = ?";
$stmt  = $koneksi->prepare($query);
if (!$stmt) { die("Query failed: " . $koneksi->error); }
$stmt->bind_param('i', $id_brg);
$stmt->execute();
$result = $stmt->get_result();
$row    = $result->fetch_assoc();

if ($row) {
    $nama_brg  = $row['nama_brg'];
    $harga_brg = $row['harga'];
    $total     = $qty_jual * $harga_brg;

    $q_ins = "INSERT INTO penjualan (no_struk, id_brg, tanggal, nama_brg, qty_jual, harga_brg, total)
              VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt_i = $koneksi->prepare($q_ins);
    if (!$stmt_i) { die("Insert failed: " . $koneksi->error); }
    $stmt_i->bind_param('iissiii', $no_struk, $id_brg, $tanggal, $nama_brg, $qty_jual, $harga_brg, $total);
    if ($stmt_i->execute()) {
        header("Location: index.php?no_struk=$no_struk");
        exit();
    } else {
        die("Execute failed: " . $stmt_i->error);
    }
} else {
    die("Produk tidak ditemukan: $id_brg");
}
?>