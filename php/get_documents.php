<?php
require_once 'config.php';
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    exit(json_encode(['status' => 'error', 'message' => 'Akses ditolak.']));
}

$searchTerm = isset($_GET['search']) ? '%' . $_GET['search'] . '%' : '%';
$sortOption = isset($_GET['sort']) ? $_GET['sort'] : 'date_desc';

// Tentukan klausa ORDER BY berdasarkan opsi sort
$orderByClause = "ORDER BY ";
switch ($sortOption) {
    case 'status_asc':
        $orderByClause .= "FIELD(status, 'menunggu', 'disetujui', 'ditolak'), tanggal_unggah DESC";
        break;
    case 'name_asc':
        $orderByClause .= "judul_dokumen ASC";
        break;
    case 'name_desc':
        $orderByClause .= "judul_dokumen DESC";
        break;
    case 'date_asc':
        $orderByClause .= "tanggal_unggah ASC";
        break;
    default: // Termasuk 'date_desc'
        $orderByClause .= "tanggal_unggah DESC";
        break;
}

try {
    // [FIX] Perbarui klausa WHERE untuk pencarian yang lebih luas
    $searchClause = "(judul_dokumen LIKE :searchTerm 
                      OR kategori LIKE :searchTerm 
                      OR keterangan LIKE :searchTerm
                      OR DATE_FORMAT(tanggal_unggah, '%Y-%m-%d') LIKE :searchTerm)";

    if ($_SESSION['role'] === 'admin') {
        // Admin melihat SEMUA dokumen
        $sql = "SELECT * FROM documents 
                WHERE $searchClause
                $orderByClause";
        $stmt = $pdo->prepare($sql);
    } else {
        // User melihat yang 'disetujui' ATAU semua status dokumen miliknya
        $sql = "SELECT * FROM documents 
                WHERE (status = 'disetujui' OR user_id_pengunggah = :user_id)
                AND $searchClause
                $orderByClause";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
    }

    $stmt->bindParam(':searchTerm', $searchTerm, PDO::PARAM_STR);
    $stmt->execute();
    $documents = $stmt->fetchAll();

    echo json_encode(['documents' => $documents, 'role' => $_SESSION['role'], 'user_id' => $_SESSION['user_id']]);
} catch (PDOException $e) {
    http_response_code(500);
    exit(json_encode(['status' => 'error', 'message' => 'Gagal mengambil data: ' . $e->getMessage()]));
}
