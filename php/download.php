<?php
require_once 'config.php';

// Proteksi: Semua user yang login bisa download
if (!isset($_SESSION['user_id'])) die('Akses ditolak. Silakan login terlebih dahulu.');

// ... (Sisa logika download sama seperti sebelumnya, sudah benar) ...
if (!isset($_GET['id'])) die('Akses tidak valid.');
$id = filter_var($_GET['id'], FILTER_VALIDATE_INT);

try {
    $stmt = $pdo->prepare("SELECT nama_file_asli, nama_file_tersimpan, tipe_file FROM documents WHERE id = ?");
    $stmt->execute([$id]);
    $file = $stmt->fetch();
    if ($file) {
        $filePath = UPLOAD_DIR . $file['nama_file_tersimpan'];
        if (file_exists($filePath)) {
            header('Content-Description: File Transfer');
            header('Content-Type: ' . $file['tipe_file']);
            header('Content-Disposition: attachment; filename="' . basename($file['nama_file_asli']) . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($filePath));
            readfile($filePath);
            exit;
        } else {
            die('File tidak ditemukan di server.');
        }
    } else {
        die('Dokumen tidak ditemukan di database.');
    }
} catch (PDOException $e) {
    die('Terjadi kesalahan database.');
}
