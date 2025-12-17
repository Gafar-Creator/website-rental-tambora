<?php
session_start();
if(!isset($_SESSION['login'])){
    header("Location: login.php");
    exit;
}
include 'koneksi.php';

// Ambil ID alat dari URL
if (!isset($_GET['id'])) {
    header("Location: alat.php");
    exit;
}

$id = (int)$_GET['id'];
$query = mysqli_query($koneksi, "SELECT * FROM alat WHERE id=$id");
$data = mysqli_fetch_assoc($query);

// Jika ID tidak ditemukan
if (!$data) {
    echo "<script>alert('Data tidak ditemukan!'); window.location='alat.php';</script>";
    exit;
}

$pesan = "";

// Jika form disubmit
if(isset($_POST['simpan'])){
    $nama  = htmlspecialchars($_POST['nama']);
    $harga = (int)$_POST['harga'];
    $stok  = (int)$_POST['stok'];

    $foto = $data['foto']; // Gunakan foto lama sebagai default

    // Cek jika ada upload foto baru
    if(isset($_FILES['foto']) && $_FILES['foto']['error'] == 0){
        $allowed_types = ['image/jpeg','image/png','image/jpg', 'image/webp'];
        $ext = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);

        if(!in_array(strtolower($ext), $allowed_types)){
            $pesan = "<div class='alert alert-danger'>File harus berupa gambar (JPG/PNG/WebP)!</div>";
        } else {
            // Hapus file lama jika ada di folder uploads
            if($foto && file_exists('uploads/'.$foto)){
                unlink('uploads/'.$foto);
            }

            // Simpan file baru
            $foto = time() . '_' . preg_replace("/[^a-zA-Z0-9.]/", "_", $_FILES['foto']['name']);
            move_uploaded_file($_FILES['foto']['tmp_name'], 'uploads/'.$foto);
        }
    }

    // Update database jika tidak ada pesan error
    if (empty($pesan)) {
        $stmt = $koneksi->prepare("UPDATE alat SET nama_alat=?, harga_sewa=?, stok=?, foto=? WHERE id=?");
        $stmt->bind_param("siisi", $nama, $harga, $stok, $foto, $id);
        
        if ($stmt->execute()) {
            echo "<script>alert('Data alat berhasil diperbarui!'); window.location='alat.php';</script>";
            exit;
        } else {
            $pesan = "<div class='alert alert-danger'>Gagal memperbarui database.</div>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Alat - Tambora Rental</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="assets/css/sidebar.css">
    
    <style>
        :root { --primary: #3b82f6; --dark: #1e293b; --bg: #f1f5f9; }
        body { background: var(--bg); margin: 0; font-family: 'Segoe UI', sans-serif; }
        
        .header-top {
            display: flex; justify-content: space-between; align-items: center;
            background: var(--dark); color: white; padding: 12px 25px;
            position: sticky; top: 0; z-index: 1000;
        }
        .brand-logo { display: flex; align-items: center; gap: 10px; }
        .brand-text b { font-size: 1.1rem; display: block; line-height: 1; }
        .brand-text small { font-size: 0.7rem; color: #94a3b8; text-transform: uppercase; }

        .form-card {
            background: white; padding: 30px; border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); max-width: 700px;
            margin-top: 20px; border: 1px solid #e2e8f0;
        }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 8px; font-weight: 600; color: #1e293b; }
        .form-group input {
            width: 100%; padding: 12px; border: 1px solid #cbd5e1;
            border-radius: 8px; font-size: 1rem; box-sizing: border-box;
        }
        
        .preview-img {
            width: 100px; height: 100px; object-fit: cover; 
            border-radius: 8px; margin-bottom: 10px; border: 2px solid #e2e8f0;
        }

        .btn { padding: 12px 20px; border-radius: 8px; font-weight: 600; cursor: pointer; text-decoration: none; border: none; font-size: 0.9rem; display: inline-flex; align-items: center; gap: 8px; }
        .btn-success { background: #10b981; color: white; }
        .btn-secondary { background: #64748b; color: white; margin-right: 10px; }
        
        .alert { padding: 15px; border-radius: 8px; margin-bottom: 20px; font-size: 0.9rem; }
        .alert-danger { background: #fee2e2; color: #b91c1c; border: 1px solid #fecaca; }
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
            <a href="pengembalian.php"><i class="fas fa-rotate-left"></i> Pengembalian</a>
            <a href="laporan.php"><i class="fas fa-file-invoice-dollar"></i> Laporan</a>
            <a href="kelola_akun.php"><i class="fas fa-user-shield"></i> Kelola Akun</a>
        </nav>
    </aside>

    <main style="flex: 1; padding: 40px;">
        <h2 style="color: #1e293b; margin: 0;">
            <i class="fas fa-edit" style="color:var(--primary);"></i> Edit Inventaris
        </h2>
        <p style="color: #64748b; margin-top: 5px;">Perbarui informasi alat camping.</p>

        <div class="form-card">
            <?= $pesan ?>
            <form method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label><i class="fas fa-tag"></i> Nama Alat</label>
                    <input type="text" name="nama" value="<?= htmlspecialchars($data['nama_alat']) ?>" required>
                </div>

                <div style="display: flex; gap: 20px;">
                    <div class="form-group" style="flex: 1;">
                        <label><i class="fas fa-money-bill-wave"></i> Harga Sewa / Hari</label>
                        <input type="number" name="harga" value="<?= $data['harga_sewa'] ?>" required>
                    </div>

                    <div class="form-group" style="flex: 1;">
                        <label><i class="fas fa-layer-group"></i> Stok Tersedia</label>
                        <input type="number" name="stok" value="<?= $data['stok'] ?>" required>
                    </div>
                </div>

                <div class="form-group">
                    <label><i class="fas fa-image"></i> Foto Alat</label>
                    <?php if($data['foto'] && file_exists('uploads/'.$data['foto'])): ?>
                        <img src="uploads/<?= $data['foto'] ?>" class="preview-img" alt="Preview">
                    <?php endif; ?>
                    <input type="file" name="foto" accept="image/*" style="border: 1px dashed #cbd5e1; background: #f8fafc; padding: 15px; cursor: pointer;">
                    <small style="color: #94a3b8; display: block; margin-top: 5px;">Kosongkan jika tidak ingin mengganti foto. Format: JPG, PNG, WebP.</small>
                </div>

                <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #e2e8f0; display: flex; justify-content: flex-end;">
                    <a href="alat.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Kembali</a>
                    <button type="submit" name="simpan" class="btn btn-success"><i class="fas fa-save"></i> Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </main>
</div>

</body>
</html>