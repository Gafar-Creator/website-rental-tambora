<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Proteksi Halaman
if(!isset($_SESSION['login'])){
    header("Location: login.php");
    exit;
}

include 'koneksi.php'; 

// 1. Ambil data statistik Alat
$queryAlat = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM alat");
$totalAlat = ($queryAlat) ? mysqli_fetch_assoc($queryAlat)['total'] : 0;

// 2. Ambil data statistik Sewa
$querySewa = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM sewa");
$totalTransaksi = ($querySewa) ? mysqli_fetch_assoc($querySewa)['total'] : 0;

// 3. Ambil data statistik User
$queryUser = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM users");
$totalUser = ($queryUser) ? mysqli_fetch_assoc($queryUser)['total'] : 0;
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Tambora Rental</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    
    <style>
        :root {
            --primary: #3b82f6;
            --dark: #1e293b;
            --sidebar-bg: #0f172a;
            --bg: #f1f5f9;
        }

        body { background: var(--bg); margin: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        
        .header-top {
            display: flex; justify-content: space-between; align-items: center;
            background: var(--dark); color: white; padding: 12px 25px;
            position: sticky; top: 0; z-index: 1000;
        }
        
        .brand-logo { display: flex; align-items: center; gap: 10px; }
        .brand-logo i { font-size: 1.5rem; color: var(--primary); }

        /* Sidebar Styling Sesuai User.php */
        .sidebar { width: 240px; min-height: 100vh; background: var(--sidebar-bg); }
        .sidebar nav a { 
            color: #94a3b8; display: block; padding: 15px 25px; 
            text-decoration: none; transition: 0.3s; font-size: 0.9rem;
        }
        .sidebar nav a:hover { background: #1e293b; color: white; }
        .sidebar nav a.active { background: #1e293b; color: white; border-left: 4px solid var(--primary); }

        /* Content Styling */
        .stat-card {
            background: white; padding: 25px; border-radius: 12px;
            display: flex; align-items: center; justify-content: space-between;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
            border: 1px solid #e2e8f0;
        }
        
        .cards-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px; margin-top: 20px;
        }
        
        .stat-info h3 { margin: 0; font-size: 0.85rem; color: #64748b; text-transform: uppercase; }
        .stat-info h1 { margin: 5px 0 0; font-size: 2rem; color: var(--dark); }
        
        .stat-icon {
            width: 55px; height: 55px; border-radius: 12px;
            display: flex; align-items: center; justify-content: center; font-size: 1.6rem;
        }
        .icon-blue { background: #dbeafe; color: #3b82f6; }
        .icon-green { background: #dcfce7; color: #10b981; }
        .icon-orange { background: #ffedd5; color: #f59e0b; }

        .welcome-box {
            background: white; padding: 30px; border-radius: 12px; margin-top: 30px;
            border-left: 5px solid var(--primary); box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        .btn-action {
            background: var(--primary); color: white; padding: 10px 20px; 
            border-radius: 6px; text-decoration: none; display: inline-block;
            font-weight: 500; transition: 0.3s; border: none; cursor: pointer;
        }
    </style>
</head>
<body>

<header class="header-top">
    <div class="brand-logo">
        <i class="fas fa-mountain-sun"></i>
        <div style="line-height: 1;">
            <b style="display: block;">TAMBORA RENTAL</b>
            <small style="font-size: 0.7rem; color: #94a3b8; text-transform: uppercase;">Professional Camping System</small>
        </div>
    </div>
    <a href="logout.php" style="color:#fca5a5; text-decoration:none; font-weight:600; font-size: 0.9rem;">
        <i class="fas fa-right-from-bracket"></i> Keluar
    </a>
</header>

<div style="display: flex;">
    <aside class="sidebar">
        <nav style="padding: 20px 0;">
            <a href="dashboard.php" class="active"><i class="fas fa-chart-pie"></i> &nbsp;Dashboard</a>
            <a href="user.php"><i class="fas fa-users-gear"></i> &nbsp;Tabel User</a>
            <a href="alat.php"><i class="fas fa-tents"></i> &nbsp;Data Alat</a>
            <a href="tambah_alat.php"><i class="fas fa-square-plus"></i> &nbsp;Tambah Alat</a>
            <a href="sewa.php"><i class="fas fa-receipt"></i> &nbsp;Transaksi Sewa</a>
            <a href="laporan.php"><i class="fas fa-file-invoice-dollar"></i> &nbsp;Laporan</a>
            <a href="kelola_akun.php"><i class="fas fa-user-shield"></i> &nbsp;Kelola Akun</a>
        </nav>
    </aside>

    <main style="flex: 1; padding: 30px;">
        <div style="margin-bottom: 25px;">
            <h2 style="color: var(--dark); margin: 0;">
                <i class="fas fa-grip" style="color: var(--primary);"></i> Dashboard Utama
            </h2>
            <p style="color: #64748b; margin: 5px 0 0 0;">Ringkasan aktivitas operasional Tambora Rental.</p>
        </div>
        
        <div class="cards-container">
            <div class="stat-card">
                <div class="stat-info">
                    <h3>Total Alat</h3>
                    <h1><?= $totalAlat ?></h1>
                </div>
                <div class="stat-icon icon-blue">
                    <i class="fas fa-boxes-stacked"></i>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-info">
                    <h3>Total Sewa</h3>
                    <h1><?= $totalTransaksi ?></h1>
                </div>
                <div class="stat-icon icon-green">
                    <i class="fas fa-file-invoice"></i>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-info">
                    <h3>Pengguna</h3>
                    <h1><?= $totalUser ?></h1>
                </div>
                <div class="stat-icon icon-orange">
                    <i class="fas fa-user-group"></i>
                </div>
            </div>
        </div>

        <div class="welcome-box">
            <h2 style="margin-top:0; color: var(--dark);">Selamat Datang, Admin! <i class="fas fa-hand-sparkles" style="color: #f59e0b;"></i></h2>
            <p style="color: #64748b; line-height: 1.6; max-width: 700px;">
                Panel kendali Tambora Rental siap digunakan. Anda dapat memantau ketersediaan perlengkapan kemping, 
                mengelola transaksi sewa, dan melihat laporan pendapatan secara <b>real-time</b>.
            </p>
            <div style="margin-top: 20px; display: flex; gap: 10px;">
                <a href="sewa.php" class="btn-action">
                    <i class="fas fa-plus-circle"></i> Buat Transaksi Baru
                </a>
                <a href="tambah_alat.php" class="btn-action" style="background: #64748b;">
                    <i class="fas fa-plus"></i> Stok Alat Baru
                </a>
            </div>
        </div>
    </main>
</div>

</body>
</html>