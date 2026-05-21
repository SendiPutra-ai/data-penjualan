<?php
session_start();
if (!isset($_SESSION["authenticated"]) || !$_SESSION["authenticated"]) {
    header("Location: login_form.php");
    exit();
}

include 'koneksi.php';

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];

    $q = $koneksi->prepare("SELECT `gambar` FROM `biodata` WHERE `id` = ?");
    $q->bind_param("i", $id);
    $q->execute();
    $q->bind_result($gambar);
    $q->fetch();
    $q->close();

    if (!empty($gambar) && file_exists($gambar)) {
        unlink($gambar);
    }

    $stmt = $koneksi->prepare("DELETE FROM `biodata` WHERE `id` = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: view_data.php");
        exit();
    } else {
        echo "Penghapusan gagal: " . $stmt->error;
    }
    $stmt->close();
}

$koneksi->close();
?>