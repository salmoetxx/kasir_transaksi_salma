<?php
session_start();

if (!isset($_SESSION['struk_data'])) {
    header("location: index.php");
    exit;
}

$struk       = $_SESSION['struk_data'];
$belanja     = $struk['items'];
$total_bayar = 0;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Struk Pembayaran - SALMA CINEMA</title>
    <link rel="stylesheet" href="style-struk.css">
</head>
<body>

    <div class="struk-card">
        <div class="struk-header">
            <h2>SALMA CINEMA STORE</h2>
            <p>Sistem Kasir Tiket dan Cafe SALNEMA</p>
        </div>

        <div class="divider"></div>

        <table class="info-table">
            <tr>
                <td>No. Nota</td>
                <td>: <?php echo $struk['no_nota']; ?></td>
            </tr>
            <tr>
                <td>Tanggal</td>
                <td>: <?php echo date("d/m/Y H:i", strtotime($struk['tgl_transaksi'])); ?></td>
            </tr>
            <tr>
                <td>Kasir</td>
                <td>: <?php echo $struk['nama_kasir']; ?></td>
            </tr>
            <tr>
                <td>Pelanggan</td>
                <td>: <?php echo $struk['nama_pelanggan']; ?></td>
            </tr>
            <tr>
                <td>No. HP</td>
                <td>: <?php echo $struk['no_hp']; ?></td>
            </tr>
            <tr>
                <td><b>KURSI</b></td>
                <td>: <b><?php echo $struk['kursi']; ?></b></td>
            </tr>
        </table>

        <div class="divider"></div>

        <table class="item-table">
            <thead>
                <tr>
                    <th align="left">Item</th>
                    <th align="center">Qty</th>
                    <th align="right">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($belanja as $item) : 
                    $total_bayar += $item['subtotal'];
                ?>
                    <tr>
                        <td align="left"><?php echo $item['nama_barang']; ?></td>
                        <td align="center"><?php echo $item['qyt']; ?></td>
                        <td align="right">Rp <?php echo number_format($item['subtotal'], 0, ',', '.'); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="divider"></div>

        <table class="total-table">
            <tr>
                <td align="left"><b>TOTAL BAYAR</b></td>
                <td align="right"><b>Rp <?php echo number_format($total_bayar, 0, ',', '.'); ?></b></td>
            </tr>
        </table>

        <div class="divider"></div>

        <div class="struk-footer">
            <p>=== TERIMA KASIH ===</p>
            <p>Selamat Menikmati Film Anda!</p>
        </div>

        <div class="no-print">
            <button class="btn btn-print" onclick="window.print()">🖨️ Cetak Struk</button>
            <a href="index.php" class="btn btn-back">➕ Transaksi Baru</a>
        </div>
    </div>

</body>
</html>