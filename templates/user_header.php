<!-- templates/user_header.php -->
<header class="main-header">
    <div class="header-content">
        <a href="user_dashboard.php" class="header-logo-container">
            <img src="img/logo-bsb.png" alt="Logo BSB" class="header-logo">
            <span class="header-title">BSB ARC</span>
        </a>
        <nav class="main-nav">
            <a href="user_dashboard.php">Dashboard</a>
            <a href="user_arsip.php">Arsip Dokumen</a>
            <a href="pengaturan_akun.php">Pengaturan Akun</a>
        </nav>
        <div class="user-info">
            <span>Halo, <strong><?php echo htmlspecialchars($_SESSION['nama_lengkap']); ?></strong>!</span>
            <a href="php/auth.php?action=logout" class="logout-link">Logout</a>
        </div>
    </div>
</header>