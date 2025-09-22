<?php
require_once 'config.php';

// Pastikan hanya admin yang bisa mengakses
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    http_response_code(403);
    exit(json_encode(['status' => 'error', 'message' => 'Akses ditolak.']));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {

    // Aksi untuk menyetujui pengguna baru
    if ($_POST['action'] === 'approve_user') {
        $user_id = $_POST['user_id'];
        $stmt = $pdo->prepare("UPDATE users SET status = 'aktif' WHERE id = ?");
        $stmt->execute([$user_id]);
        $_SESSION['message'] = "Pengguna berhasil disetujui.";
        $_SESSION['is_error'] = false;
        header('Location: ../admin_manajemen_user.php');
        exit();
    }

    // Aksi untuk menyetujui dokumen
    if ($_POST['action'] === 'approve_document') {
        $doc_id = $_POST['doc_id'];
        $stmt = $pdo->prepare("UPDATE documents SET status = 'disetujui' WHERE id = ?");
        $stmt->execute([$doc_id]);
        exit(json_encode(['status' => 'success', 'message' => 'Dokumen berhasil disetujui.']));
    }

    // Aksi untuk menolak dokumen
    if ($_POST['action'] === 'reject_document') {
        $doc_id = $_POST['doc_id'];
        $stmt = $pdo->prepare("UPDATE documents SET status = 'ditolak' WHERE id = ?");
        $stmt->execute([$doc_id]);
        exit(json_encode(['status' => 'success', 'message' => 'Dokumen berhasil ditolak.']));
    }
}
