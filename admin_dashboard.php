<?php
require_once 'php/config.php';
// Proteksi Halaman Admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

// Ambil jumlah user yang menunggu persetujuan
$stmt = $pdo->query("SELECT COUNT(*) as count FROM users WHERE status = 'menunggu'");
$pending_users_count = $stmt->fetchColumn();
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin - BSB ARC</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <?php include 'templates/admin_header.php'; ?>
    <div class="container">
        <main>
            <h2>Selamat Datang, <?php echo htmlspecialchars($_SESSION['nama_lengkap']); ?>!</h2>
            <p>Anda login sebagai Administrator. Gunakan menu navigasi di atas untuk mengelola sistem.</p>
            <div class="dashboard-stats">
                <div class="stat-card">
                    <h3>Persetujuan Pengguna</h3>
                    <p class="stat-number"><?php echo $pending_users_count; ?></p>
                    <a href="admin_manajemen_user.php" class="stat-link">Lihat Detail &raquo;</a>
                </div>
                <div class="stat-card">
                    <h3>Total Dokumen</h3>
                    <p class="stat-number"><?php echo $pdo->query("SELECT COUNT(*) FROM documents")->fetchColumn(); ?></p>
                    <a href="admin_arsip.php" class="stat-link">Kelola Arsip &raquo;</a>
                </div>
            </div>
        </main>
    </div>
</body>

</html>