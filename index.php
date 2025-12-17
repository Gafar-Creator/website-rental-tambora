<?php
session_start();

// Jika sudah login, langsung ke dashboard
if(isset($_SESSION['login'])){
    header("Location: dashboard.php");
    exit;
} else {
    // Jika belum login, arahkan ke halaman login
    header("Location: login.php");
    exit;
}
?>
