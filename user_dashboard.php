<?php
require_once 'php/config.php';

// Proteksi Halaman: Hanya user yang sudah login yang bisa akses
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Dashboard Pengguna - BSB ARC</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

    <?php include 'templates/user_header.php'; // Menggunakan header baru 
    ?>

    <div class="container">
        <main>
            <h2>Selamat Datang di Sistem Arsip Digital</h2>
            <p>Halo, <strong><?php echo htmlspecialchars($_SESSION['nama_lengkap']); ?></strong>!</p>
            <p>Anda dapat melihat semua dokumen yang tersedia melalui menu navigasi di atas.</p>
            <a href="user_arsip.php" class="btn btn-primary">Lihat Arsip Dokumen</a>
        </main>
    </div>

</body>

</html>