<?php require_once 'php/config.php'; ?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - BSB ARC</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body class="auth-body">
    <div class="auth-container">
        <div class="auth-header">
            <img src="img/logo-bsb.png" alt="Logo Bank Sumsel Babel" class="auth-logo">
        </div>
        <h1>Buat Akun Baru</h1>
        <p>Daftar untuk mengakses BSB ARC</p>

        <?php include 'templates/message.php'; ?>

        <form action="php/auth.php" method="POST">
            <input type="hidden" name="action" value="register">
            <div class="form-group">
                <label for="nama_lengkap">Nama Lengkap</label>
                <input type="text" id="nama_lengkap" name="nama_lengkap" required>
            </div>
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="confirm_password">Konfirmasi Password</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
            </div>
            <button type="submit" class="btn btn-primary">Daftar & Tunggu Persetujuan</button>
        </form>
        <div class="footer-link">
            Sudah punya akun? <a href="login.php">Login di sini</a>
        </div>
    </div>
</body>

</html>