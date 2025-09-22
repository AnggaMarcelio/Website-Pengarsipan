<?php
require_once 'config.php';
header('Content-Type: application/json');

// Proteksi: Hanya admin yang bisa hapus
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    http_response_code(403);
    echo json_encode(['status' => 'error', 'message' => 'Akses ditolak. Hanya admin yang bisa menghapus.']);
    exit();
}

// ... (Sisa logika delete sama seperti sebelumnya, sudah benar) ...
if (!isset($_GET['id'])) exit(json_encode(['status' => 'error', 'message' => 'ID tidak ditemukan.']));

$id = filter_var($_GET['id'], FILTER_VALIDATE_INT);
try {
    $pdo->beginTransaction();
    $stmt = $pdo->prepare("SELECT nama_file_tersimpan FROM documents WHERE id = ?");
    $stmt->execute([$id]);
    $document = $stmt->fetch();
    if ($document) {
        $deleteStmt = $pdo->prepare("DELETE FROM documents WHERE id = ?");
        $deleteStmt->execute([$id]);
        $filePath = UPLOAD_DIR . $document['nama_file_tersimpan'];
        if (file_exists($filePath)) unlink($filePath);
        $pdo->commit();
        echo json_encode(['status' => 'success', 'message' => 'Dokumen berhasil dihapus.']);
    } else {
        $pdo->rollBack();
        echo json_encode(['status' => 'error', 'message' => 'Dokumen tidak ditemukan.']);
    }
} catch (PDOException $e) {
    $pdo->rollBack();
    echo json_encode(['status' => 'error', 'message' => 'Gagal menghapus dokumen.']);
}
