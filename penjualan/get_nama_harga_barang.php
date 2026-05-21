<?php
include '../koneksi.php';
$id_brg = (int)$_GET['id_brg'];
$query  = "SELECT nama_brg, harga FROM produk WHERE id = ?";
$stmt   = $koneksi->prepare($query);
$stmt->bind_param('i', $id_brg);
$stmt->execute();
$result   = $stmt->get_result();
$response = [];
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $response['nama_brg']  = $row['nama_brg'];
    $response['harga_brg'] = $row['harga'];
} else {
    $response['nama_brg']  = 'Tidak ditemukan';
    $response['harga_brg'] = '0';
}
header('Content-Type: application/json');
echo json_encode($response);
?>