<?php
// Aktifkan error reporting untuk development agar semua masalah terlihat
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Konfigurasi Database
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', ''); // Biarkan kosong untuk default XAMPP
define('DB_NAME', 'bsb_arc_db');

// Folder untuk menyimpan file upload
define('UPLOAD_DIR', '../uploads/');

// Selalu mulai session di file konfigurasi agar tersedia di semua halaman
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Buat koneksi menggunakan PDO
try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4", DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // Hentikan aplikasi jika koneksi database gagal
    die("Koneksi database gagal: " . $e->getMessage());
}
