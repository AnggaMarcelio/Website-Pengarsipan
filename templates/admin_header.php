<!-- templates/admin_header.php -->
<header class="main-header">
    <div class="header-content">
        <a href="admin_dashboard.php" class="header-logo-container">
            <img src="img/logo-bsb.png" alt="Logo BSB" class="header-logo">
            <span class="header-title">BSB ARC (Admin)</span>
        </a>
        <nav class="main-nav">
            <a href="admin_dashboard.php">Dashboard</a>
            <a href="admin_arsip.php">Manajemen Arsip</a>
            <a href="admin_manajemen_user.php">Manajemen Pengguna</a>
            <a href="pengaturan_akun.php">Pengaturan</a>
        </nav>
        <div class="user-info">
            <span>Halo, <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong>!</span>
            <a href="php/auth.php?action=logout" class="logout-link">Logout</a>
        </div>
    </div>
</header>