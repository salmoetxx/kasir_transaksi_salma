<?php
session_start();
include 'koneksi.php';

if (isset($_POST['id_barang']) && isset($_POST['qyt'])) {
    $id_barang = $_POST['id_barang'];
    $qyt       = (int)$_POST['qyt'];

    $query  = mysqli_query($koneksi, "SELECT * FROM barang WHERE id_barang = '$id_barang'");
    $barang = mysqli_fetch_assoc($query);

    if ($barang) {
        $nama_barang = $barang['nama_barang'];
        $harga       = $barang['harga'];
        $subtotal    = $harga * $qyt;

        if (!isset($_SESSION['keranjang'])) {
            $_SESSION['keranjang'] = array();
        }

        $sudah_ada = false;
        foreach ($_SESSION['keranjang'] as $key => $item) {
            if ($item['id_barang'] == $id_barang) {
                $_SESSION['keranjang'][$key]['qyt'] += $qyt;
                $_SESSION['keranjang'][$key]['subtotal'] = $_SESSION['keranjang'][$key]['qyt'] * $harga;
                $sudah_ada = true;
                break;
            }
        }

        if (!$sudah_ada) {
            $_SESSION['keranjang'][] = array(
                'id_barang'   => $id_barang,
                'nama_barang' => $nama_barang,
                'harga'       => $harga,
                'qyt'         => $qyt,
                'subtotal'    => $subtotal
            );
        }
    }
}

header("location: index.php");
exit;
?>