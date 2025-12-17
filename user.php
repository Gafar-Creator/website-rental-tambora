<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

include 'koneksi.php';

$pesan = "";

// 1. PROSES TAMBAH USER (Pelanggan)
if (isset($_POST['tambah_user'])) {
    $nama     = htmlspecialchars($_POST['nama']);
    $email    = htmlspecialchars($_POST['email']);
    $telepon  = htmlspecialchars($_POST['telepon']);
    $alamat   = htmlspecialchars($_POST['alamat']);

    $stmt = $koneksi->prepare("INSERT INTO user (nama, email, telepon, alamat) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $nama, $email, $telepon, $alamat);
    
    if ($stmt->execute()) {
        header("Location: user.php?status=berhasil");
        exit;
    } else {
        $pesan = "<div class='alert alert-danger'>Gagal menambah user.</div>";
    }
}

// 2. PROSES HAPUS USER
if (isset($_GET['hapus'])) {
    $id = (int)$_GET['hapus'];
    $stmt = $koneksi->prepare("DELETE FROM user WHERE id_user = ?"); // Sesuaikan nama kolom ID di DB-mu
    $stmt->bind_param("i", $id);
    $stmt->execute();
    header("Location: user.php?status=terhapus");
    exit;
}

// 3. AMBIL DATA USER
// Ambil data dari tabel 'users' (pake S)
$data_user = mysqli_query($koneksi, "SELECT * FROM users ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tabel User - Tambora Rental</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="assets/css/sidebar.css">
    <style>
        :root {
            --primary: #3b82f6;
            --dark: #1e293b;
            --bg: #f1f5f9;
        }
        body { background: var(--bg); margin: 0; font-family: 'Segoe UI', sans-serif; }
        
        .header-top {
            display: flex; justify-content: space-between; align-items: center;
            background: var(--dark); color: white; padding: 12px 25px;
            position: sticky; top: 0; z-index: 1000;
        }
        .brand-logo { display: flex; align-items: center; gap: 10px; }
        .brand-text b { font-size: 1.1rem; display: block; line-height: 1; }
        .brand-text small { font-size: 0.7rem; color: #94a3b8; text-transform: uppercase; }

        .wrapper { display: flex; }
        .sidebar { width: 240px; min-height: 100vh; background: #0f172a; }
        .main-content { flex: 1; padding: 30px; }

        /* Khusus User menggunakan layout full width atau grid jika ingin tambah form */
        .user-layout { display: grid; grid-template-columns: 1fr; gap: 25px; }

        .card {
            background: white; border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); border: 1px solid #e2e8f0;
            overflow: hidden;
        }
        .card-header { 
            padding: 20px; font-weight: 700; border-bottom: 1px solid #f1f5f9; 
            display: flex; justify-content: space-between; align-items: center; 
        }

        .custom-table { width: 100%; border-collapse: collapse; }
        .custom-table th { background: #f8fafc; padding: 15px; text-align: left; color: #64748b; font-size: 0.75rem; text-transform: uppercase; border-bottom: 2px solid #e2e8f0; }
        .custom-table td { padding: 15px; border-bottom: 1px solid #f1f5f9; vertical-align: middle; }

        .btn { padding: 8px 15px; border-radius: 6px; font-weight: 600; cursor: pointer; text-decoration: none; border: none; font-size: 0.85rem; display: inline-flex; align-items: center; gap: 8px; transition: 0.2s; }
        .btn-primary { background: var(--primary); color: white; }
        .action-btn { width: 32px; height: 32px; justify-content: center; border-radius: 6px; }

        .badge-phone { background: #ecfdf5; color: #065f46; padding: 4px 8px; border-radius: 4px; font-size: 0.8rem; font-weight: 600; }
        
        /* Modal Simple Style */
        .modal-btn { background: #10b981; color: white; }
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

<div class="wrapper">
    <aside class="sidebar">
        <nav class="menu-list" style="padding: 20px 0;">
            <a href="dashboard.php"><i class="fas fa-chart-pie"></i> Dashboard</a>
            <a href="user.php" class="active"><i class="fas fa-users-gear"></i> Tabel User</a>
            <a href="alat.php"><i class="fas fa-tents"></i> Data Alat</a>
            <a href="tambah_alat.php"><i class="fas fa-square-plus"></i> Tambah Alat</a>
            <a href="sewa.php"><i class="fas fa-receipt"></i> Transaksi Sewa</a>
            <a href="laporan.php"><i class="fas fa-file-invoice-dollar"></i> Laporan</a>
            <a href="kelola_akun.php"><i class="fas fa-user-shield"></i> Kelola Akun</a>
        </nav>
    </aside>

    <main class="main-content">
        <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 25px;">
            <div>
                <h2 style="color: var(--dark); margin: 0;">
                    <i class="fas fa-users" style="color: var(--primary);"></i> Data Pelanggan
                </h2>
                <p style="color: #64748b; margin: 5px 0 0 0;">Daftar member yang terdaftar di Tambora Rental.</p>
            </div>
            <a href="tambah_user.php" class="btn btn-primary">
                <i class="fas fa-plus"></i> Registrasi Member Baru
            </a>
        </div>

        <?= $pesan ?>

        <div class="user-layout">
            <div class="card">
                <div class="card-header">
                    <span>Daftar Member Aktif</span>
                    <span style="color: var(--primary); font-size: 0.8rem;"><?= mysqli_num_rows($data_user) ?> Total User</span>
                </div>
                <table class="custom-table">
                    <thead>
                        <tr>
                            <th style="width: 50px; text-align: center;">No</th>
                            <th>Nama Lengkap</th>
                            <th>Kontak</th>
                            <th>Alamat</th>
                            <th style="text-align: center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
$no = 1;
while($user = mysqli_fetch_assoc($data_user)): ?>
<tr>
    <td style="text-align: center; color: #94a3b8;"><?= $no++ ?></td>
    <td>
        <div style="font-weight: 600; color: var(--dark);">
            <?= htmlspecialchars($user['nama_lengkap']) ?>
        </div>
        <div style="font-size: 0.75rem; color: #64748b;">
            @<?= htmlspecialchars($user['username']) ?>
        </div>
    </td>
    <td>
        <span class="badge-phone" style="background: #e0f2fe; color: #0369a1;">
            <i class="fas fa-user-tag"></i> <?= htmlspecialchars($user['role']) ?>
        </span>
    </td>
    <td style="color: #64748b; font-size: 0.85rem;">
        Dibuat: <?= date('d M Y', strtotime($user['created_at'])) ?>
    </td>
    <td style="text-align: center;">
        <a href="edit_user.php?id=<?= $user['id'] ?>" class="btn action-btn" style="background:#f1f5f9; color:#475569;">
            <i class="fas fa-edit"></i>
        </a>
        <a href="?hapus=<?= $user['id'] ?>" 
           onclick="return confirm('Hapus data user ini?')" 
           class="btn action-btn" style="background:#fee2e2; color:#b91c1c;">
            <i class="fas fa-trash"></i>
        </a>
    </td>
</tr>
<?php endwhile; ?>

                        <?php if(mysqli_num_rows($data_user) == 0): ?>
                        <tr>
                            <td colspan="5" style="padding: 50px; text-align: center; color: #94a3b8;">
                                <i class="fas fa-users-slash" style="font-size: 2.5rem; display: block; margin-bottom: 10px;"></i>
                                Belum ada data pelanggan yang terdaftar.
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>

</body>
</html>