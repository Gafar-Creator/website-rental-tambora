<?php
session_start();
if(!isset($_SESSION['login'])){
    header("Location: login.php");
    exit;
}
include 'koneksi.php';

// Logika Pencarian
$search = "";
if (isset($_GET['keyword'])) {
    $search = mysqli_real_escape_string($koneksi, $_GET['keyword']);
    $query = "SELECT sewa.*, alat.nama_alat 
              FROM sewa 
              JOIN alat ON sewa.alat_id = alat.id
              WHERE sewa.nama_penyewa LIKE '%$search%' 
              OR alat.nama_alat LIKE '%$search%'
              ORDER BY sewa.tgl_sewa DESC";
} else {
    $query = "SELECT sewa.*, alat.nama_alat 
              FROM sewa 
              JOIN alat ON sewa.alat_id = alat.id
              ORDER BY sewa.id DESC";
}

$data = mysqli_query($koneksi, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Transaksi - Tambora Rental</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="assets/css/sidebar.css">
    <link rel="stylesheet" href="assets/css/laporan.css">
    
    <style>
        body { background: #f1f5f9; margin: 0; font-family: 'Segoe UI', sans-serif; }
        
        /* Header Top */
        .header-top {
            display: flex; justify-content: space-between; align-items: center;
            background: #1e293b; color: white; padding: 12px 25px;
            position: sticky; top: 0; z-index: 1000;
        }
        .brand-logo { display: flex; align-items: center; gap: 10px; }
        .brand-logo i { font-size: 1.5rem; color: #3b82f6; }
        .brand-text b { font-size: 1.1rem; display: block; line-height: 1; }
        .brand-text small { font-size: 0.7rem; color: #94a3b8; text-transform: uppercase; }

        /* Action Bar (Print & Search) */
        .action-bar {
            background: white;
            padding: 15px 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }

        .search-container { display: flex; gap: 8px; }
        .search-input {
            padding: 8px 15px;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            width: 250px;
            outline: none;
        }
        .btn-search {
            background: #1e293b; color: white; border: none;
            padding: 8px 15px; border-radius: 6px; cursor: pointer;
        }

        /* Table Styling */
        .table-wrapper {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
        }
        .custom-table { width: 100%; border-collapse: collapse; }
        .custom-table th { background: #f8fafc; padding: 15px; text-align: left; color: #64748b; font-size: 0.85rem; text-transform: uppercase; border-bottom: 2px solid #e2e8f0; }
        .custom-table td { padding: 15px; border-bottom: 1px solid #f1f5f9; color: #1e293b; }
        .badge-total { background: #dcfce7; color: #166534; padding: 4px 10px; border-radius: 6px; font-weight: bold; }

        .btn-print {
            background: #1d4ed8; color: white; text-decoration: none;
            padding: 10px 20px; border-radius: 8px; font-weight: 600;
            display: inline-flex; align-items: center; gap: 8px;
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
            <a href="alat.php"><i class="fas fa-tents"></i> Data Alat</a>
            <a href="tambah_alat.php"><i class="fas fa-square-plus"></i> Tambah Alat</a>
            <a href="sewa.php"><i class="fas fa-receipt"></i> Transaksi Sewa</a>
            <a href="laporan.php" class="active"><i class="fas fa-file-invoice-dollar"></i> Laporan</a>
            <a href="kelola_akun.php"><i class="fas fa-user-shield"></i> Kelola Akun</a>
        </nav>
    </aside>

    <main style="flex: 1; padding: 30px;">
        <h2 style="color: #1e293b; margin-bottom: 20px;">
            <i class="fas fa-file-lines" style="color:#3b82f6;"></i> Laporan Transaksi
        </h2>

        <div class="action-bar">
            <a href="cetak_pdf.php<?= isset($_GET['keyword']) ? '?keyword='.$_GET['keyword'] : '' ?>" class="btn-print">
                <i class="fas fa-file-pdf"></i> Cetak Laporan PDF
            </a>
            
            <form action="" method="GET" class="search-container">
                <input type="text" name="keyword" class="search-input" placeholder="Cari penyewa atau alat..." value="<?= htmlspecialchars($search) ?>">
                <button type="submit" class="btn-search"><i class="fas fa-magnifying-glass"></i></button>
                <?php if($search != ""): ?>
                    <a href="laporan.php" style="background:#f1f5f9; padding:8px 12px; border-radius:6px; color:#64748b;"><i class="fas fa-xmark"></i></a>
                <?php endif; ?>
            </form>
        </div>

        <div class="table-wrapper">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Penyewa</th>
                        <th>Alat Camping</th>
                        <th><i class="far fa-calendar-alt"></i> Sewa</th>
                        <th><i class="far fa-calendar-check"></i> Kembali</th>
                        <th>Total Biaya</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1;
                    while($d = mysqli_fetch_assoc($data)) { ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td style="font-weight: 600;"><?= htmlspecialchars($d['nama_penyewa']) ?></td>
                            <td><?= htmlspecialchars($d['nama_alat']) ?></td>
                            <td><small><?= date('d/m/Y', strtotime($d['tgl_sewa'])) ?></small></td>
                            <td><small><?= date('d/m/Y', strtotime($d['tgl_kembali'])) ?></small></td>
                            <td><span class="badge-total">Rp <?= number_format($d['total'],0,',','.') ?></span></td>
                        </tr>
                    <?php } ?>
                    
                    <?php if(mysqli_num_rows($data) == 0){ ?>
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 40px; color: #94a3b8;">
                                <i class="fas fa-search" style="font-size: 2rem; display: block; margin-bottom: 10px;"></i>
                                Tidak ada data ditemukan untuk "<b><?= htmlspecialchars($search) ?></b>"
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </main>
</div>

</body>
</html>