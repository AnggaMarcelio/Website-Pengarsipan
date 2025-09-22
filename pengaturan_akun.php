<?php
require_once 'php/config.php';

// Proteksi Halaman: Semua user yang login bisa akses
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengaturan Akun - BSB ARC</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <?php
    // Tampilkan header yang sesuai berdasarkan role pengguna
    if ($_SESSION['role'] === 'admin') {
        include 'templates/admin_header.php';
    } else {
        include 'templates/user_header.php';
    }
    ?>

    <div class="container">
        <main>
            <h2>Pengaturan Akun</h2>

            <div class="settings-grid">
                <!-- Bagian Pembaruan Profil -->
                <div class="settings-card">
                    <h3>Profil Saya</h3>
                    <form id="profileForm">
                        <input type="hidden" name="action" value="update_profile">
                        <div class="form-group">
                            <label for="nama_lengkap">Nama Lengkap</label>
                            <input type="text" id="nama_lengkap" name="nama_lengkap" value="<?php echo htmlspecialchars($_SESSION['nama_lengkap']); ?>" required>
                        </div>
                        <div class="form-group">
                            <label>Username</label>
                            <input type="text" value="<?php echo htmlspecialchars($_SESSION['username']); ?>" disabled>
                        </div>
                        <div class="form-group">
                            <label>Peran</label>
                            <input type="text" value="<?php echo ucfirst(htmlspecialchars($_SESSION['role'])); ?>" disabled>
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan Profil</button>
                    </form>
                </div>

                <!-- Bagian Ubah Kata Sandi -->
                <div class="settings-card">
                    <h3>Ubah Kata Sandi</h3>
                    <form id="passwordForm">
                        <input type="hidden" name="action" value="change_password">
                        <div class="form-group">
                            <label for="password_current">Kata Sandi Saat Ini</label>
                            <input type="password" id="password_current" name="password_current" required>
                        </div>
                        <div class="form-group">
                            <label for="password_new">Kata Sandi Baru</label>
                            <input type="password" id="password_new" name="password_new" required>
                        </div>
                        <div class="form-group">
                            <label for="password_confirm">Konfirmasi Kata Sandi Baru</label>
                            <input type="password" id="password_confirm" name="password_confirm" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan Kata Sandi Baru</button>
                    </form>
                </div>
            </div>
        </main>
    </div>

    <!-- Notifikasi Toast (akan dikontrol oleh JavaScript) -->
    <div id="toast" class="toast"></div>

    <script src="js/pengaturan_script.js"></script>
</body>

</html>