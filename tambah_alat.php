<?php
session_start();
if(!isset($_SESSION['login'])){
    header("Location: login.php");
    exit;
}
include 'koneksi.php';

if(isset($_POST['simpan'])){
    $nama  = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $harga = (int) $_POST['harga'];
    $stok  = (int) $_POST['stok'];

    $foto = null;
    if(isset($_FILES['foto']) && $_FILES['foto']['error'] == 0){
        $allowed_types = ['image/jpeg','image/png','image/jpg'];
        if(!in_array($_FILES['foto']['type'], $allowed_types)){
            echo "<script>alert('File harus berupa gambar (JPG/PNG)!'); window.history.back();</script>";
            exit;
        }

        $foto = time() . '_' . $_FILES['foto']['name'];
        move_uploaded_file($_FILES['foto']['tmp_name'], 'uploads/'.$foto);
    }

    mysqli_query($koneksi, "
        INSERT INTO alat (nama_alat, harga_sewa, stok, foto)
        VALUES ('$nama', '$harga', '$stok', '$foto')
    ");

    header("Location: alat.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Alat - Tambora Rental</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="assets/css/sidebar.css">
    <link rel="stylesheet" href="assets/css/tambah-alat.css">
    
    <style>
        body { background: #f1f5f9; margin: 0; font-family: 'Segoe UI', sans-serif; }
        
        /* Header Styling */
        .header-top {
            display: flex; justify-content: space-between; align-items: center;
            background: #1e293b; color: white; padding: 12px 25px;
            position: sticky; top: 0; z-index: 1000;
        }
        .brand-logo { display: flex; align-items: center; gap: 10px; }
        .brand-logo i { font-size: 1.5rem; color: #3b82f6; }
        .brand-text b { font-size: 1.1rem; display: block; line-height: 1; }
        .brand-text small { font-size: 0.7rem; color: #94a3b8; text-transform: uppercase; }

        /* Form Container Styling */
        .form-card {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
            max-width: 700px;
            margin-top: 20px;
            border: 1px solid #e2e8f0;
        }
        .form-group { margin-bottom: 20px; }
        .form-group label { 
            display: block; margin-bottom: 8px; font-weight: 600; color: #1e293b; 
        }
        .form-group input {
            width: 100%; padding: 10px 15px; border: 1px solid #cbd5e1;
            border-radius: 8px; font-size: 1rem; box-sizing: border-box;
        }
        .form-group input:focus { border-color: #3b82f6; outline: none; box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1); }
        
        .btn { padding: 12px 20px; border-radius: 8px; font-weight: 600; cursor: pointer; text-decoration: none; border: none; font-size: 0.9rem; }
        .btn-primary { background: #1d4ed8; color: white; transition: 0.3s; }
        .btn-primary:hover { background: #1e40af; }
        .btn-secondary { background: #64748b; color: white; margin-right: 10px; }
        .btn-secondary:hover { background: #475569; }
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
            <a href="tambah_alat.php" class="active"><i class="fas fa-square-plus"></i> Tambah Alat</a>
            <a href="sewa.php"><i class="fas fa-receipt"></i> Transaksi Sewa</a>
            <a href="laporan.php"><i class="fas fa-file-invoice-dollar"></i> Laporan</a>
            <a href="kelola_akun.php"><i class="fas fa-user-shield"></i> Kelola Akun</a>
        </nav>
    </aside>

    <main style="flex: 1; padding: 40px; background: #f1f5f9;">
        <h2 style="color: #1e293b; margin-bottom: 5px;">
            <i class="fas fa-plus-circle" style="color:#3b82f6;"></i> Tambah Inventaris
        </h2>
        <p style="color: #64748b; margin-bottom: 25px;">Masukkan detail alat camping baru ke dalam sistem.</p>

        <div class="form-card">
            <form method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label><i class="fas fa-tag"></i> Nama Alat</label>
                    <input type="text" name="nama" placeholder="Contoh: Tenda Dome Kapasitas 4 Orang" required>
                </div>

                <div style="display: flex; gap: 20px;">
                    <div class="form-group" style="flex: 1;">
                        <label><i class="fas fa-money-bill-wave"></i> Harga Sewa / Hari</label>
                        <input type="number" name="harga" placeholder="50000" required>
                    </div>

                    <div class="form-group" style="flex: 1;">
                        <label><i class="fas fa-layer-group"></i> Stok Tersedia</label>
                        <input type="number" name="stok" placeholder="10" required>
                    </div>
                </div>

                <div class="form-group">
                    <label><i class="fas fa-image"></i> Unggah Foto Alat</label>
                    <input type="file" name="foto" accept="image/*" style="border: 1px dashed #cbd5e1; background: #f8fafc; padding: 20px;">
                    <small style="color: #94a3b8; display: block; margin-top: 5px;">Format: JPG, JPEG, atau PNG.</small>
                </div>

                <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #e2e8f0; display: flex; justify-content: flex-end;">
                    <a href="alat.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Kembali</a>
                    <button type="submit" name="simpan" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Data Alat</button>
                </div>
            </form>
        </div>
    </main>
</div>

</body>
</html>

