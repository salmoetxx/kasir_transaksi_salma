<?php
session_start();
include 'koneksi.php';

date_default_timezone_set('Asia/Jakarta');

if (!isset($_SESSION['keranjang']) || empty($_SESSION['keranjang'])) {
    header("location: index.php");
    exit;
}

// Data Kasir Default
$nama_kasir = "Salma";

// Tangkap Data Pelanggan
$nama_pelanggan = mysqli_real_escape_string($koneksi, $_POST['nama_pelanggan']);
$no_hp          = mysqli_real_escape_string($koneksi, $_POST['no_hp']);
$kursi          = mysqli_real_escape_string($koneksi, $_POST['kursi']);

// Simpan Data Pelanggan
mysqli_query($koneksi, "INSERT INTO pelanggan (nama_pelanggan, no_hp, kursi) VALUES ('$nama_pelanggan', '$no_hp', '$kursi')");
$id_pelanggan = mysqli_insert_id($koneksi);

$tgl_transaksi = date("Y-m-d H:i:s");
$no_nota       = "INV-" . date("YmdHis");

// Simpan Transaksi & Potong Stok
foreach ($_SESSION['keranjang'] as $item) {
    $id_barang   = $item['id_barang'];
    $qyt         = $item['qyt'];
    $total_harga = $item['subtotal'];

    mysqli_query($koneksi, "INSERT INTO transaksi (nama_kasir, id_pelanggan, id_barang, qyt, total_harga, tgl_transaksi) 
                            VALUES ('$nama_kasir', '$id_pelanggan', '$id_barang', '$qyt', '$total_harga', '$tgl_transaksi')");

    mysqli_query($koneksi, "UPDATE barang SET stok = stok - $qyt WHERE id_barang = '$id_barang'");
}

// Simpan Data Lengkap ke Session Struk (Termasuk Kasir)
$_SESSION['struk_data'] = [
    'no_nota'        => $no_nota,
    'nama_kasir'     => $nama_kasir,
    'nama_pelanggan' => $nama_pelanggan,
    'no_hp'          => $no_hp,
    'kursi'          => $kursi,
    'tgl_transaksi'  => $tgl_transaksi,
    'items'          => $_SESSION['keranjang']
];

unset($_SESSION['keranjang']);

header("location: struk.php");
exit;
?>