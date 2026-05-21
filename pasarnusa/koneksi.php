<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "pasarnusa";

$koneksi = mysqli_connect($host, $user, $pass, $db);

if (!$koneksi) {
    die("Koneksi ke database gagal: " . mysqli_connect_error());
}
?>