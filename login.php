<?php
session_start();
require 'koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email    = $_POST["email"];
    $password = $_POST["password"];

    $sql  = "SELECT * FROM Login WHERE email = '$email' AND Password = '$password'";
    $result = $koneksi->query($sql);

    if ($result && $result->num_rows == 1) {
        $_SESSION["authenticated"] = true;
        $_SESSION["email"]         = $email;
        header("Location: menu.php");
        exit();
    } else {
        header("Location: login_form.php?error=1");
        exit();
    }
} else {
    header("Location: login_form.php");
    exit();
}
?>