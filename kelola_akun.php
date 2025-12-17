<?php
session_start();
if(!isset($_SESSION['login'])){
    header("Location: login.php");
    exit;
}

include 'koneksi.php';

// Ambil data akun
$data = mysqli_query($koneksi, "SELECT * FROM admin ORDER BY id DESC");

// Jika form tambah akun disubmit
if(isset($_POST['tambah'])){
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    mysqli_query($koneksi,"INSERT INTO admin (username,password) VALUES ('$username','$password')");
    header("Location: kelola_akun.php");
    exit;
}

// Jika hapus akun
if(isset($_GET['hapus'])){
    $id = (int)$_GET['hapus'];
    // Opsional: Cegah admin menghapus dirinya sendiri
    mysqli_query($koneksi,"DELETE FROM admin WHERE id=$id");
    header("Location: kelola_akun.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Akun - Tambora Rental</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="assets/css/sidebar.css">
    <link rel="stylesheet" href="assets/css/kelola_akun.css">
    
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

        /* Grid Layout */
        .account-grid {
            display: grid;
            grid-template-columns: 350px 1fr;
            gap: 25px;
            margin-top: 20px;
        }

        /* Card & Form Styling */
        .card {
            background: white; padding: 25px; border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); border: 1px solid #e2e8f0;
        }
        .card-title { font-size: 1rem; font-weight: 700; color: #1e293b; margin-bottom: 20px; display: flex; align-items: center; gap: 10px; }
        
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: 600; font-size: 0.9rem; color: #64748b; }
        .form-group input {
            width: 100%; padding: 10px; border: 1px solid #cbd5e1;
            border-radius: 6px; box-sizing: border-box; outline: none;
        }
        .form-group input:focus { border-color: #3b82f6; }

        /* Table Styling */
        .custom-table { width: 100%; border-collapse: collapse; }
        .custom-table th { background: #f8fafc; padding: 12px; text-align: left; color: #64748b; font-size: 0.8rem; text-transform: uppercase; border-bottom: 2px solid #e2e8f0; }
        .custom-table td { padding: 12px; border-bottom: 1px solid #f1f5f9; }

        .btn { padding: 8px 15px; border-radius: 6px; font-weight: 600; cursor: pointer; text-decoration: none; border: none; font-size: 0.85rem; display: inline-flex; align-items: center; gap: 5px; }
        .btn-success { background: #10b981; color: white; width: 100%; justify-content: center; }
        .btn-warning { background: #f59e0b; color: white; }
        .btn-danger { background: #ef4444; color: white; }
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
            <a href="laporan.php"><i class="fas fa-file-invoice-dollar"></i> Laporan</a>
            <a href="kelola_akun.php" class="active"><i class="fas fa-user-shield"></i> Kelola Akun</a>
        </nav>
    </aside>

    <main style="flex: 1; padding: 30px;">
        <h2 style="color: #1e293b; margin-bottom: 5px;">
            <i class="fas fa-user-gear" style="color:#3b82f6;"></i> Pengaturan Akun Admin
        </h2>
        <p style="color: #64748b; margin-bottom: 25px;">Manajemen hak akses petugas sistem rental.</p>

        <div class="account-grid">
            <div class="card">
                <div class="card-title"><i class="fas fa-user-plus" style="color:#10b981;"></i> Tambah Akun</div>
                <form method="post">
                    <div class="form-group">
                        <label>Username</label>
                        <input type="text" name="username" placeholder="Masukkan username..." required>
                    </div>
                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" name="password" placeholder="Masukkan password..." required>
                    </div>
                    <button type="submit" name="tambah" class="btn btn-success">
                        <i class="fas fa-save"></i> Simpan Akun Baru
                    </button>
                </form>
            </div>

            <div class="card" style="padding: 0; overflow: hidden;">
                <div style="padding: 20px; font-weight: 700; border-bottom: 1px solid #f1f5f9; display: flex; align-items: center; gap: 10px;">
                    <i class="fas fa-users" style="color:#3b82f6;"></i> Daftar Admin Aktif
                </div>
                <table class="custom-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Username</th>
                            <th style="text-align: center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $no = 1;
                        while($row = mysqli_fetch_assoc($data)) { ?>
                        <tr>
                            <td style="padding-left: 20px;"><?= $no++ ?></td>
                            <td>
                                <div style="display:flex; align-items:center; gap:10px;">
                                    <div style="width:32px; height:32px; background:#f1f5f9; border-radius:50%; display:flex; align-items:center; justify-content:center; color:#64748b;">
                                        <i class="fas fa-user" style="font-size:0.8rem;"></i>
                                    </div>
                                    <span style="font-weight:600; color:#1e293b;"><?= htmlspecialchars($row['username']) ?></span>
                                </div>
                            </td>
                            <td style="text-align: center;">
                                <a href="edit_akun.php?id=<?= $row['id'] ?>" class="btn btn-warning" style="background:#fef3c7; color:#92400e;">
                                    <i class="fas fa-pen"></i>
                                </a>
                                <a href="?hapus=<?= $row['id'] ?>" onclick="return confirm('Hapus akun ini?')" class="btn btn-danger" style="background:#fee2e2; color:#b91c1c;">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
                <?php if(mysqli_num_rows($data) == 0): ?>
                    <div style="padding: 40px; text-align: center; color: #94a3b8;">
                        <i class="fas fa-user-slash" style="font-size: 2rem; display: block; margin-bottom: 10px;"></i>
                        Belum ada data akun admin.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </main>
</div>

</body>
</html>