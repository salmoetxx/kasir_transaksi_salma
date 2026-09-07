<?php
$host     = "localhost";
$user     = "root";
$password = "";
$database = "kasir_db";

$koneksi = mysqli_connect($host, $user, $password, $database);

if (!$koneksi) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}
?>