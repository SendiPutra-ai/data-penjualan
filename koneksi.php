<?php
$host     = "localhost";
$username = "root";
$password = "";
$database = "data_profile";

$koneksi = new mysqli($host, $username, $password, $database);

if ($koneksi->connect_error) {
    die("Koneksi Gagal: " . $koneksi->connect_error);
}
?>