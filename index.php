<?php
session_start();
include 'koneksi.php';

$query_barang = mysqli_query($koneksi, "SELECT * FROM barang WHERE stok > 0");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BioskopSALMA Transaksi</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style-index.css">
</head>
<body>

    <!-- Header Navbar -->
    <header class="navbar">
        <div class="navbar-container">
            <div class="brand">
                <span class="logo-icon">🎬</span>
                <div>
                    <h1>SALMA CINEMA STORE</h1>
                    <p>Sistem Kasir Tiket dan Cafe SALNEMA</p>
                </div>
            </div>
            <!-- Menampilkan Nama Kasir Aktif di Header -->
            <div class="badge-status">Kasir: Salma</div>
        </div>
    </header>

    <div class="container">
        <!-- Katalog Barang/Tiket -->
        <div class="card">
            <h2>Pilih Tiket / Menu Paket</h2>
            <div class="catalog-grid">
                <?php while ($b = mysqli_fetch_assoc($query_barang)) : ?>
                    <div class="item-card">
                        <img src="img/<?php echo $b['gambar']; ?>" alt="<?php echo $b['nama_barang']; ?>" class="item-img">
                        <div class="item-details">
                            <span class="item-title"><?php echo $b['nama_barang']; ?></span>
                            <span class="item-price">Rp <?php echo number_format($b['harga'], 0, ',', '.'); ?></span>
                            <span class="item-stok">Stok: <?php echo $b['stok']; ?></span>
                            
                            <form action="tambah_keranjang.php" method="POST" class="item-form">
                                <input type="hidden" name="id_barang" value="<?php echo $b['id_barang']; ?>">
                                <div class="qty-group">
                                    <input type="number" name="qyt" value="1" min="1" max="<?php echo $b['stok']; ?>" required>
                                    <button type="submit" class="btn btn-primary">+ Tambah</button>
                                </div>
                            </form>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>

        <!-- Keranjang Belanja & Form Pelanggan -->
        <div class="card card-cart">
            <h2>Keranjang Belanja</h2>
            <table class="table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Barang/Tiket</th>
                        <th>Qty</th>
                        <th>Subtotal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $total_belanja = 0;
                    if (isset($_SESSION['keranjang']) && !empty($_SESSION['keranjang'])) :
                        $no = 1;
                        foreach ($_SESSION['keranjang'] as $index => $item) : 
                            $total_belanja += $item['subtotal'];
                    ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><?php echo $item['nama_barang']; ?></td>
                            <td><?php echo $item['qyt']; ?></td>
                            <td>Rp <?php echo number_format($item['subtotal'], 0, ',', '.'); ?></td>
                            <td>
                                <a href="hapus_keranjang.php?index=<?php echo $index; ?>" class="btn-delete" onclick="return confirm('Apakah Anda yakin ingin membatalkan item ini?');">Hapus</a>
                            </td>
                        </tr>
                    <?php 
                        endforeach; 
                    else : 
                    ?>
                        <tr>
                            <td colspan="5" style="text-align: center; color: #888;">Keranjang masih kosong. Pilih menu/tiket di samping dulu.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>

            <div class="total-box">
                <span>Total Bayar:</span>
                <strong>Rp <?php echo number_format($total_belanja, 0, ',', '.'); ?></strong>
            </div>

            <!-- Form Data Pelanggan -->
            <form action="simpan.php" method="POST" style="margin-top: 15px;">
                <!-- Informasi Kasir Default (Otomatis / Disabled Input) -->
                <div class="form-group">
                    <label>Petugas Kasir:</label>
                    <input type="text" value="Salma" disabled style="background-color: #e2e8f0; font-weight: 600;">
                </div>

                <div class="form-group">
                    <label>Nama Pelanggan:</label>
                    <input type="text" name="nama_pelanggan" placeholder="Contoh: Wita" required>
                </div>

                <div class="form-group">
                    <label>No. HP / WA:</label>
                    <input type="text" name="no_hp" placeholder="Contoh: 08123456789" required>
                </div>

                <div class="form-group">
                    <label>Nomor Kursi Duduk:</label>
                    <select name="kursi" required>
                        <option value="">-- Pilih Kursi --</option>
                        <option value="A1">A1</option>
                        <option value="A2">A2</option>
                        <option value="A3">A3</option>
                        <option value="A4">A4</option>
                        <option value="B1">B1</option>
                        <option value="B2">B2</option>
                        <option value="B3">B3</option>
                        <option value="B4">B4</option>
                        <option value="C1">C1</option>
                        <option value="C2">C2</option>
                    </select>
                </div>

                <?php if (isset($_SESSION['keranjang']) && !empty($_SESSION['keranjang'])) : ?>
                    <button type="submit" class="btn btn-success" style="width:100%; margin-top: 10px; font-size:15px;">
                        💳 Proses & Simpan Transaksi
                    </button>
                <?php else : ?>
                    <button type="button" class="btn btn-success" style="width:100%; margin-top: 10px; font-size:15px; opacity: 0.5; cursor: not-allowed;" disabled>
                        💳 Keranjang Kosong
                    </button>
                <?php endif; ?>
            </form>
        </div>
    </div>

</body>
</html>