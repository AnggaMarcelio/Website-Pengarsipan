<?php
// php/auth.php
require_once 'config.php';

function redirect_with_message($url, $message, $is_error = true)
{
    $_SESSION['message'] = $message;
    $_SESSION['is_error'] = $is_error;
    header("Location: $url");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['action'])) {
    header('Location: ../login.php');
    exit();
}

$action = $_POST['action'];

// PROSES REGISTRASI BARU
if ($action === 'register') {
    $nama_lengkap = trim($_POST['nama_lengkap']);
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if (empty($nama_lengkap) || empty($username) || empty($password)) {
        redirect_with_message('../register.php', 'Semua field wajib diisi.');
    }
    if ($password !== $confirm_password) {
        redirect_with_message('../register.php', 'Konfirmasi password tidak cocok.');
    }

    $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->execute([$username]);
    if ($stmt->fetch()) {
        redirect_with_message('../register.php', 'Username sudah digunakan.');
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Masukkan user baru dengan status 'menunggu'
    $stmt = $pdo->prepare("INSERT INTO users (nama_lengkap, username, password, role, status) VALUES (?, ?, ?, 'user', 'menunggu')");
    $stmt->execute([$nama_lengkap, $username, $hashed_password]);

    redirect_with_message('../login.php', 'Registrasi berhasil! Akun Anda sedang menunggu persetujuan dari Admin.', false);
}

// PROSES LOGIN BARU
if ($action === 'login') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        redirect_with_message('../login.php', 'Username dan password wajib diisi.');
    }

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        // CEK STATUS AKUN
        if ($user['status'] === 'menunggu') {
            redirect_with_message('../login.php', 'Akun Anda belum aktif. Silakan tunggu persetujuan dari Admin.');
        }

        // Login sukses, buat session
        session_regenerate_id(true);
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['nama_lengkap'] = $user['nama_lengkap'];
        $_SESSION['role'] = $user['role'];

        // Arahkan berdasarkan role
        if ($user['role'] === 'admin') {
            header("Location: ../admin_dashboard.php");
        } else {
            header("Location: ../user_dashboard.php");
        }
        exit();
    } else {
        redirect_with_message('../login.php', 'Username atau password salah.');
    }
}

// Proses Logout
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    session_destroy();
    redirect_with_message('../login.php', 'Anda telah berhasil logout.', false);
}
