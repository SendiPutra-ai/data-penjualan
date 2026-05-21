<?php
session_start();
if (!isset($_SESSION["authenticated"]) || !$_SESSION["authenticated"]) {
    header("Location: ../login_form.php"); exit();
}
include '../koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id_jual']) && isset($_GET['no_struk'])) {
    $id_jual  = (int)$_GET['id_jual'];
    $no_struk = (int)$_GET['no_struk'];

    $query = "DELETE FROM penjualan WHERE id_jual = $id_jual";
    if ($koneksi->query($query) === TRUE) {
        header("Location: index.php?no_struk=$no_struk");
        exit();
    } else {
        echo "Error: " . $koneksi->error;
    }
} else {
    header("Location: index.php");
    exit();
}
?>