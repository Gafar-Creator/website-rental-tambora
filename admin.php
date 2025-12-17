<?php
session_start();
include 'koneksi.php';

// Cek apakah admin sudah login
if(isset($_SESSION['admin_login'])){
    header("Location: dashboard.php");
    exit;
}

$error = "";

if(isset($_POST['login'])){
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = $_POST['password'];

    // Cek data admin
    $query = mysqli_query($koneksi, "SELECT * FROM admin WHERE username='$username'");
    if(mysqli_num_rows($query) === 1){
        $admin = mysqli_fetch_assoc($query);
        if(password_verify($password, $admin['password'])){
            // Login sukses
            $_SESSION['admin_login'] = true;
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_name'] = $admin['nama_lengkap'];
            header("Location: dashboard.php");
            exit;
        } else {
            $error = "Password salah!";
        }
    } else {
        $error = "Username tidak ditemukan!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login Admin</title>
    <link rel="stylesheet" href="assets/css/login.css">
</head>
<body>
<div class="login-container">
    <h2>Login Admin</h2>

    <?php if($error != ""): ?>
        <div class="error"><?= $error ?></div>
    <?php endif; ?>

    <form method="post">
        <div class="form-group">
            <label>Username</label>
            <input type="text" name="username" required placeholder="Masukkan username">
        </div>
        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" required placeholder="Masukkan password">
        </div>
        <button type="submit" name="login" class="btn">Login</button>
    </form>
</div>
</body>
</html>
