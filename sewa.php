<?php
session_start();
if(!isset($_SESSION['login'])){
    header("Location: login.php");
    exit;
}
include 'koneksi.php';

// Ambil data alat untuk dropdown
$alatData = mysqli_query($koneksi,"SELECT * FROM alat WHERE stok > 0");

// Proses simpan transaksi sewa
if(isset($_POST['simpan'])){
    $nama_penyewa = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $alat_id = (int) $_POST['alat'];
    $jumlah = (int) $_POST['jumlah'];
    $tgl_sewa = $_POST['tgl_sewa'];
    $tgl_kembali = $_POST['tgl_kembali'];

    // Ambil harga alat
    $alat = mysqli_fetch_assoc(mysqli_query($koneksi,"SELECT harga_sewa FROM alat WHERE id = $alat_id"));
    if(!$alat){
        die("Data alat tidak ditemukan");
    }

    $total = $alat['harga_sewa'] * $jumlah;

    mysqli_query($koneksi,"
        INSERT INTO sewa (nama_penyewa, alat_id, jumlah, tgl_sewa, tgl_kembali, total)
        VALUES ('$nama_penyewa', '$alat_id', '$jumlah', '$tgl_sewa', '$tgl_kembali', '$total')
    ");

    header("Location: laporan.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Sewa - Tambora Rental</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="assets/css/sidebar.css">
    <link rel="stylesheet" href="assets/css/sewa.css">
    
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

        /* Form Styling */
        .form-card {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
            max-width: 800px;
            margin-top: 20px;
            border: 1px solid #e2e8f0;
        }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 8px; font-weight: 600; color: #1e293b; }
        .form-group input, .form-group select {
            width: 100%; padding: 12px; border: 1px solid #cbd5e1;
            border-radius: 8px; font-size: 1rem; box-sizing: border-box;
            background-color: #f8fafc;
        }
        .form-group input:focus, .form-group select:focus { 
            border-color: #3b82f6; outline: none; background: white;
        }
        
        .row { display: flex; gap: 20px; }
        .col { flex: 1; }

        .btn { padding: 12px 25px; border-radius: 8px; font-weight: 600; cursor: pointer; text-decoration: none; border: none; }
        .btn-primary { background: #1d4ed8; color: white; transition: 0.3s; }
        .btn-primary:hover { background: #1e40af; }
        .btn-secondary { background: #e2e8f0; color: #475569; margin-right: 10px; transition: 0.3s; }
        .btn-secondary:hover { background: #cbd5e1; }
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
            <a href="sewa.php" class="active"><i class="fas fa-file-invoice"></i> Transaksi Sewa</a>
            <a href="laporan.php"><i class="fas fa-file-invoice-dollar"></i> Laporan</a>
            <a href="kelola_akun.php"><i class="fas fa-user-shield"></i> Kelola Akun</a>
        </nav>
    </aside>

    <main style="flex: 1; padding: 40px; background: #f1f5f9;">
        <h2 style="color: #1e293b; margin-bottom: 5px;">
            <i class="fas fa-calendar-check" style="color:#3b82f6;"></i> Input Transaksi Baru
        </h2>
        <p style="color: #64748b; margin-bottom: 25px;">Catat penyewaan alat camping pelanggan.</p>

        <div class="form-card">
            <form method="post">
                <div class="form-group">
                    <label><i class="fas fa-user"></i> Nama Lengkap Penyewa</label>
                    <input type="text" name="nama" placeholder="Masukkan nama pelanggan..." required>
                </div>

                <div class="row">
                    <div class="form-group col">
                        <label><i class="fas fa-campground"></i> Pilih Alat Camping</label>
                        <select name="alat" required>
                            <option value="">-- Cari Alat --</option>
                            <?php while($a=mysqli_fetch_assoc($alatData)) { ?>
                                <option value="<?= $a['id'] ?>"><?= htmlspecialchars($a['nama_alat']) ?> - (Rp <?= number_format($a['harga_sewa'],0,',','.') ?>/Hari)</option>
                            <?php } ?>
                        </select>
                    </div>

                    <div class="form-group" style="width: 150px;">
                        <label><i class="fas fa-hashtag"></i> Jumlah</label>
                        <input type="number" name="jumlah" min="1" value="1" required>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col">
                        <label><i class="fas fa-calendar-plus"></i> Tanggal Sewa</label>
                        <input type="date" name="tgl_sewa" value="<?= date('Y-m-d') ?>" required>
                    </div>

                    <div class="form-group col">
                        <label><i class="fas fa-calendar-check"></i> Rencana Kembali</label>
                        <input type="date" name="tgl_kembali" required>
                    </div>
                </div>

                <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #e2e8f0; display: flex; justify-content: flex-end;">
                    <a href="dashboard.php" class="btn btn-secondary">Batalkan</a>
                    <button type="submit" name="simpan" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan Transaksi Sewa
                    </button>
                </div>
            </form>
        </div>
    </main>
</div>

</body>
</html>
