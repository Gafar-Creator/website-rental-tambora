<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

include 'koneksi.php';

$pesan = "";

if (isset($_POST['registrasi'])) {
    $nama_lengkap = htmlspecialchars($_POST['nama_lengkap']);
    $username     = htmlspecialchars($_POST['username']);
    $password     = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role         = $_POST['role'];

    // Cek apakah username sudah dipakai
    $cek = $koneksi->prepare("SELECT username FROM users WHERE username = ?");
    $cek->bind_param("s", $username);
    $cek->execute();
    if ($cek->get_result()->num_rows > 0) {
        $pesan = "<div class='alert alert-danger'>Username sudah digunakan, cari yang lain!</div>";
    } else {
        // Query insert sesuai struktur gambar database kamu
        $stmt = $koneksi->prepare("INSERT INTO users (nama_lengkap, username, password, role) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $nama_lengkap, $username, $password, $role);
        
        if ($stmt->execute()) {
            echo "<script>
                    alert('Member Baru Berhasil Didaftarkan!');
                    window.location='user.php';
                  </script>";
        } else {
            $pesan = "<div class='alert alert-danger'>Terjadi kesalahan saat mendaftar.</div>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi Member - Tambora Rental</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        :root { --primary: #3b82f6; --dark: #1e293b; --bg: #f1f5f9; }
        body { background: var(--bg); font-family: 'Segoe UI', sans-serif; display: flex; justify-content: center; align-items: center; min-height: 100vh; margin: 0; }
        .card { background: white; width: 100%; max-width: 450px; padding: 30px; border-radius: 15px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); }
        .card-header { text-align: center; margin-bottom: 25px; }
        .card-header h2 { margin: 0; color: var(--dark); }
        .card-header p { color: #64748b; font-size: 0.9rem; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: 600; color: #475569; font-size: 0.9rem; }
        .form-group input, .form-group select { width: 100%; padding: 12px; border: 1px solid #cbd5e1; border-radius: 8px; box-sizing: border-box; }
        .btn-submit { width: 100%; padding: 12px; background: var(--primary); color: white; border: none; border-radius: 8px; font-weight: 700; cursor: pointer; margin-top: 10px; }
        .btn-back { display: block; text-align: center; margin-top: 15px; text-decoration: none; color: #64748b; font-size: 0.85rem; }
        .alert { padding: 10px; border-radius: 5px; margin-bottom: 15px; font-size: 0.85rem; text-align: center; }
        .alert-danger { background: #fee2e2; color: #b91c1c; border: 1px solid #fecaca; }
    </style>
</head>
<body>

<div class="card">
    <div class="card-header">
        <i class="fas fa-user-plus" style="font-size: 2rem; color: var(--primary);"></i>
        <h2>Registrasi Member</h2>
        <p>Tambahkan data pelanggan baru ke sistem</p>
    </div>

    <?= $pesan ?>

    <form method="POST">
        <div class="form-group">
            <label>Nama Lengkap</label>
            <input type="text" name="nama_lengkap" placeholder="Masukkan nama lengkap..." required>
        </div>
        <div class="form-group">
            <label>Username</label>
            <input type="text" name="username" placeholder="Buat username..." required>
        </div>
        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" placeholder="Buat password..." required>
        </div>
        <div class="form-group">
            <label>Role / Level</label>
            <select name="role">
                <option value="Member">Member</option>
                <option value="Pelanggan Biasa">Pelanggan Biasa</option>
            </select>
        </div>
        <button type="submit" name="registrasi" class="btn-submit">Daftarkan Sekarang</button>
        <a href="user.php" class="btn-back"><i class="fas fa-arrow-left"></i> Kembali ke Tabel User</a>
    </form>
</div>

</body>
</html>