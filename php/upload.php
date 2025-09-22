<?php
require_once 'config.php';
header('Content-Type: application/json');

// Siapa pun yang sudah login bisa mencoba untuk mengunggah
if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    exit(json_encode(['status' => 'error', 'message' => 'Akses ditolak. Silakan login.']));
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    exit(json_encode(['status' => 'error', 'message' => 'Metode tidak diizinkan.']));
}

if (empty($_POST['judul']) || empty($_POST['kategori']) || empty($_FILES['dokumen'])) {
    exit(json_encode(['status' => 'error', 'message' => 'Judul, kategori, dan file tidak boleh kosong.']));
}

$judul = trim($_POST['judul']);
$kategori = trim($_POST['kategori']);
$keterangan = isset($_POST['keterangan']) ? trim($_POST['keterangan']) : '';
$file = $_FILES['dokumen'];

if ($file['error'] !== UPLOAD_ERR_OK) {
    exit(json_encode(['status' => 'error', 'message' => 'Terjadi kesalahan saat upload file. Kode: ' . $file['error']]));
}

$nama_file_asli = basename($file['name']);
$extension = pathinfo($nama_file_asli, PATHINFO_EXTENSION);
$nama_file_tersimpan = 'doc_' . time() . '_' . bin2hex(random_bytes(8)) . '.' . $extension;
$target_path = UPLOAD_DIR . $nama_file_tersimpan;

if (!is_dir(UPLOAD_DIR)) {
    mkdir(UPLOAD_DIR, 0755, true);
}

// Tentukan status dokumen berdasarkan role pengguna
$status = ($_SESSION['role'] === 'admin') ? 'disetujui' : 'menunggu';

if (move_uploaded_file($file['tmp_name'], $target_path)) {
    try {
        $sql = "INSERT INTO documents (judul_dokumen, kategori, keterangan, nama_file_asli, nama_file_tersimpan, ukuran_file, tipe_file, user_id_pengunggah, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$judul, $kategori, $keterangan, $nama_file_asli, $nama_file_tersimpan, $file['size'], $file['type'], $_SESSION['user_id'], $status]);

        $message = ($_SESSION['role'] === 'admin') ? 'Dokumen berhasil diunggah dan langsung disetujui.' : 'Dokumen berhasil dikirim dan sedang menunggu persetujuan Admin.';
        echo json_encode(['status' => 'success', 'message' => $message]);
    } catch (PDOException $e) {
        unlink($target_path);
        echo json_encode(['status' => 'error', 'message' => 'Gagal menyimpan data ke database: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Gagal memindahkan file yang diunggah.']);
}
