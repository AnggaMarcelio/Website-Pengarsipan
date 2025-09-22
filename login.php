<?php require_once 'php/config.php'; ?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - BSB ARC</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body class="auth-body">
    <div class="auth-container">
        <div class="auth-header">
            <img src="img/logo-bsb.png" alt="Logo Bank Sumsel Babel" class="auth-logo">
        </div>
        <h1>BSB ARC</h1>
        <p>Sistem Pengarsipan Digital</p>

        <?php include 'templates/message.php'; ?>

        <form action="php/auth.php" method="POST">
            <input type="hidden" name="action" value="login">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary">Login</button>
        </form>
        <div class="footer-link">
            Belum punya akun? <a href="register.php">Daftar di sini</a>
        </div>
    </div>
</body>

</html>