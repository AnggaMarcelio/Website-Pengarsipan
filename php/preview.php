<?php
// php/preview.php
require_once 'config.php';

// Proteksi: Pastikan pengguna sudah login untuk melihat file
if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    die('Akses ditolak. Silakan login terlebih dahulu.');
}

if (!isset($_GET['id'])) {
    http_response_code(400);
    die('ID dokumen tidak valid.');
}

$id = filter_var($_GET['id'], FILTER_VALIDATE_INT);

try {
    $stmt = $pdo->prepare("SELECT nama_file_asli, nama_file_tersimpan, tipe_file FROM documents WHERE id = ?");
    $stmt->execute([$id]);
    $file = $stmt->fetch();

    if ($file) {
        $filePath = UPLOAD_DIR . $file['nama_file_tersimpan'];
        if (file_exists($filePath)) {
            // Header ini memberitahu browser untuk MENAMPILKAN file, bukan mengunduh
            header('Content-Type: ' . $file['tipe_file']);
            header('Content-Disposition: inline; filename="' . basename($file['nama_file_asli']) . '"');
            header('Content-Length: ' . filesize($filePath));
            header('Accept-Ranges: bytes');

            // Baca dan kirim konten file ke browser
            readfile($filePath);
            exit;
        } else {
            http_response_code(404);
            die('File tidak ditemukan di server.');
        }
    } else {
        http_response_code(404);
        die('Dokumen tidak ditemukan di database.');
    }
} catch (PDOException $e) {
    http_response_code(500);
    die('Terjadi kesalahan database.');
}
