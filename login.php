<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include 'koneksi.php';

$error = '';
if(isset($_POST['login'])){
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = md5($_POST['password']); 

    $query = "SELECT * FROM admin WHERE username='$username' AND password='$password'";
    $result = mysqli_query($koneksi, $query);

    if($result === false){
        die("Terjadi error query: " . mysqli_error($koneksi));
    }

    if(mysqli_num_rows($result) > 0){
        $_SESSION['login'] = true;
        $_SESSION['username'] = $username;
        header("Location: dashboard.php");
        exit;
    } else {
        $error = "Kredensial yang Anda masukkan tidak valid.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Tambora Camping Rental</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    
    <style>
        :root {
            --primary: #2563eb;
            --primary-hover: #1d4ed8;
            --dark: #0f172a;
            --text-main: #1e293b;
            --text-muted: #64748b;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: 'Inter', sans-serif;
            background: linear-gradient(rgba(15, 23, 42, 0.7), rgba(15, 23, 42, 0.7)), 
                        url('uploads/pulau_kelapa.jpg') no-repeat center center fixed;
            background-size: cover;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .login-box {
            width: 100%;
            max-width: 400px;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            text-align: center;
        }

        .brand-icon {
            width: 60px;
            height: 60px;
            background: var(--primary);
            color: white;
            border-radius: 15px;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 1.5rem;
            margin: 0 auto 20px;
            box-shadow: 0 10px 15px -3px rgba(37, 99, 235, 0.3);
        }

        h1 {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 8px;
        }

        p.subtitle {
            color: var(--text-muted);
            font-size: 0.9rem;
            margin-bottom: 30px;
        }

        .input-group {
            position: relative;
            margin-bottom: 20px;
            text-align: left;
        }

        .input-group i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            font-size: 1rem;
        }

        .input-group input {
            width: 100%;
            padding: 12px 15px 12px 45px;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            font-size: 0.95rem;
            box-sizing: border-box;
            transition: all 0.3s ease;
            background: #f8fafc;
        }

        .input-group input:focus {
            outline: none;
            border-color: var(--primary);
            background: #fff;
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
        }

        button {
            width: 100%;
            padding: 12px;
            background: var(--primary);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 10px;
        }

        button:hover {
            background: var(--primary-hover);
            transform: translateY(-1px);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .error-msg {
            background: #fee2e2;
            color: #b91c1c;
            padding: 10px;
            border-radius: 8px;
            font-size: 0.85rem;
            margin-bottom: 20px;
            border: 1px solid #fecaca;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .footer {
            margin-top: 30px;
            font-size: 0.8rem;
            color: var(--text-muted);
            border-top: 1px solid #f1f5f9;
            padding-top: 20px;
        }
    </style>
</head>
<body>

<div class="login-box">
    <div class="brand-icon">
        <i class="fas fa-mountain-sun"></i>
    </div>
    
    <h1>Selamat Datang</h1>
    <p class="subtitle">Silakan login ke Panel Administrasi Tambora</p>

    <?php if($error): ?>
    <div class="error-msg">
        <i class="fas fa-circle-exclamation"></i> <?= $error ?>
    </div>
    <?php endif; ?>

    <form method="post">
        <div class="input-group">
            <i class="fas fa-user"></i>
            <input type="text" name="username" placeholder="Username" required autocomplete="off">
        </div>
        
        <div class="input-group">
            <i class="fas fa-lock"></i>
            <input type="password" name="password" placeholder="Password" required>
        </div>

        <button name="login">Masuk ke Sistem</button>
    </form>

    <div class="footer">
        &copy; 2025 Tambora Camping Rental<br>
        <span style="font-size: 0.7rem; text-transform: uppercase; letter-spacing: 1px;">Professional</span>
    </div>
</div>

</body>
</html>
