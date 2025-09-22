<?php
// php/user_actions.php
require_once 'config.php';
header('Content-Type: application/json');

// Pastikan pengguna sudah login untuk melakukan aksi ini
if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    exit(json_encode(['status' => 'error', 'message' => 'Akses ditolak. Silakan login kembali.']));
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['action'])) {
    exit(json_encode(['status' => 'error', 'message' => 'Permintaan tidak valid.']));
}

$action = $_POST['action'];
$user_id = $_SESSION['user_id'];

// Aksi untuk memperbarui profil (nama lengkap)
if ($action === 'update_profile') {
    $nama_lengkap = trim($_POST['nama_lengkap']);

    if (empty($nama_lengkap)) {
        exit(json_encode(['status' => 'error', 'message' => 'Nama lengkap tidak boleh kosong.']));
    }

    $stmt = $pdo->prepare("UPDATE users SET nama_lengkap = ? WHERE id = ?");
    if ($stmt->execute([$nama_lengkap, $user_id])) {
        // Perbarui juga session agar nama di header langsung berubah
        $_SESSION['nama_lengkap'] = $nama_lengkap;
        exit(json_encode(['status' => 'success', 'message' => 'Profil berhasil diperbarui.']));
    } else {
        exit(json_encode(['status' => 'error', 'message' => 'Gagal memperbarui profil.']));
    }
}

// Aksi untuk mengubah kata sandi
if ($action === 'change_password') {
    $password_current = $_POST['password_current'];
    $password_new = $_POST['password_new'];
    $password_confirm = $_POST['password_confirm'];

    if (empty($password_current) || empty($password_new) || empty($password_confirm)) {
        exit(json_encode(['status' => 'error', 'message' => 'Semua field kata sandi wajib diisi.']));
    }
    if ($password_new !== $password_confirm) {
        exit(json_encode(['status' => 'error', 'message' => 'Konfirmasi kata sandi baru tidak cocok.']));
    }

    // Ambil hash password saat ini dari database
    $stmt = $pdo->prepare("SELECT password FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch();

    // Verifikasi kata sandi saat ini
    if (!$user || !password_verify($password_current, $user['password'])) {
        exit(json_encode(['status' => 'error', 'message' => 'Kata sandi saat ini yang Anda masukkan salah.']));
    }

    // Hash dan simpan kata sandi baru
    $new_hashed_password = password_hash($password_new, PASSWORD_DEFAULT);
    $updateStmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
    if ($updateStmt->execute([$new_hashed_password, $user_id])) {
        exit(json_encode(['status' => 'success', 'message' => 'Kata sandi berhasil diubah.']));
    } else {
        exit(json_encode(['status' => 'error', 'message' => 'Gagal mengubah kata sandi.']));
    }
}
