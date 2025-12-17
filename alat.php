<?php 
session_start();
if(!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}
include 'koneksi.php';

// Logika Pencarian
$keyword = '';
if(isset($_GET['search'])){
    $keyword = mysqli_real_escape_string($koneksi, $_GET['search']);
    $data = mysqli_query($koneksi, "SELECT * FROM alat WHERE nama_alat LIKE '%$keyword%' ORDER BY id DESC");
} else {
    $data = mysqli_query($koneksi, "SELECT * FROM alat ORDER BY id DESC");
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Alat - Tambora Rental</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="assets/css/sidebar.css">
    <link rel="stylesheet" href="assets/css/alat.css">
    
    <style>
        /* Styling Header & Logo */
        .header-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #1e293b;
            color: white;
            padding: 12px 25px;
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        .brand-logo { display: flex; align-items: center; gap: 10px; }
        .brand-logo i { font-size: 1.5rem; color: #3b82f6; }
        .brand-text b { font-size: 1.1rem; display: block; line-height: 1; }
        .brand-text small { font-size: 0.7rem; color: #94a3b8; text-transform: uppercase; }

        /* Tabel & Gambar */
        table img {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 6px;
            border: 1px solid #e2e8f0;
        }
        .badge-stok {
            background: #e2e8f0;
            padding: 3px 8px;
            border-radius: 4px;
            font-weight: bold;
            font-size: 0.85rem;
        }

        /* Form Pencarian */
        .action-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            background: #fff;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        .search-box { display: flex; gap: 8px; }
        .search-box input {
            padding: 8px 12px;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            width: 250px;
        }
        .btn-cari {
            background: #1d4ed8;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 6px;
            cursor: pointer;
        }
    </style>
</head>
<body>

<header class="header-top">
    <div class="brand-logo">
        <i class="fas fa-mountain-sun"></i>
        <div class="brand-text">
            <b>TAMBORA RENTAL</b>
            <small>Professional Camping System</small>
        </div>
    </div>
    <a href="logout.php" style="color:#fca5a5; text-decoration:none; font-size:0.9rem; font-weight:600;">
        <i class="fas fa-right-from-bracket"></i> Keluar
    </a>
</header>

<div style="display: flex;">
    <aside class="sidebar" style="width: 240px; min-height: 100vh; background: #0f172a;">
        <nav class="menu-list" style="padding: 20px 0;">
            <a href="dashboard.php"><i class="fas fa-chart-pie"></i> Dashboard</a>
            <a href="user.php"><i class="fas fa-users-gear"></i> Tabel User</a>
            <a href="alat.php" class="active"><i class="fas fa-tents"></i> Data Alat</a>
            <a href="tambah_alat.php"><i class="fas fa-square-plus"></i> Tambah Alat</a>
            <a href="sewa.php"><i class="fas fa-receipt"></i> Transaksi Sewa</a>
            <a href="laporan.php"><i class="fas fa-file-invoice-dollar"></i> Laporan</a>
            <a href="kelola_akun.php"><i class="fas fa-user-shield"></i> Kelola Akun</a>
        </nav>
    </aside>

    <main class="main" style="flex: 1; padding: 20px; background: #f1f5f9;">
    <h2 style="margin-top:-50px; margin-bottom:20px; color:#1e293b;"><i class="fas fa-boxes-stacked" style="color:#3b82f6;"></i> Manajemen Inventaris Alat</h2>
        <div class="action-container">
            <form method="get" class="search-box">
                <input type="text" name="search" placeholder="Cari nama alat..." value="<?= htmlspecialchars($keyword) ?>">
                <button type="submit" class="btn-cari"><i class="fas fa-magnifying-glass"></i> Cari</button>
                <?php if($keyword != ''): ?>
                    <a href="alat.php" style="padding:8px; color:#64748b;"><i class="fas fa-rotate-left"></i></a>
                <?php endif; ?>
            </form>

            <a href="tambah_alat.php" class="btn btn-primary" style="background:#1d4ed8; color:white; padding:10px 20px; border-radius:6px; text-decoration:none; font-weight:600;">
                <i class="fas fa-plus"></i> Tambah Alat
            </a>
        </div>

        <div class="table-wrapper" style="background:white; border-radius:8px; overflow:hidden; box-shadow:0 4px 6px -1px rgba(0,0,0,0.1);">
            <table class="custom-table" style="width:100%; border-collapse: collapse;">
                <thead>
                    <tr style="background:#f8fafc; border-bottom:2px solid #e2e8f0; text-align:left;">
                        <th style="padding:15px;">No</th>
                        <th>Nama Alat</th>
                        <th>Harga Sewa</th>
                        <th>Stok</th>
                        <th>Foto</th>
                        <th style="text-align:center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no=1; while($d=mysqli_fetch_assoc($data)) { ?>
                    <tr style="border-bottom: 1px solid #f1f5f9;">
                        <td style="padding:15px;"><?= $no++ ?></td>
                        <td style="font-weight:600; color:#1e293b;"><?= htmlspecialchars($d['nama_alat']) ?></td>
                        <td style="color:#059669; font-weight:600;">Rp <?= number_format($d['harga_sewa'],0,',','.') ?></td>
                        <td><span class="badge-stok"><?= $d['stok'] ?></span></td>
                        <td>
                            <?php if($d['foto']): ?>
                                <img src="uploads/<?= $d['foto'] ?>" alt="Foto">
                            <?php else: ?>
                                <i class="fas fa-image" style="font-size: 2rem; color: #e2e8f0;"></i>
                            <?php endif; ?>
                        </td>
                        <td style="text-align:center;">
                            <a href="edit_alat.php?id=<?= $d['id'] ?>" style="color:#d97706; margin-right:15px; text-decoration:none;"><i class="fas fa-pen-to-square"></i> Edit</a>
                            <a href="hapus_alat.php?id=<?= $d['id'] ?>" onclick="return confirm('Yapus data alat ini?')" style="color:#dc2626; text-decoration:none;"><i class="fas fa-trash-can"></i> Hapus</a>
                        </td>
                    </tr>
                    <?php } ?>

                    <?php if(mysqli_num_rows($data) == 0): ?>
                    <tr>
                        <td colspan="6" style="text-align:center; padding:50px; color:#94a3b8;">
                            <i class="fas fa-inbox" style="font-size:3rem; display:block; margin-bottom:10px;"></i>
                            Data tidak ditemukan
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>
</div>

</body>
</html>

