<?php
require_once 'php/config.php';

// Proteksi Halaman Admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Manajemen Arsip - BSB ARC</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <?php include 'templates/admin_header.php'; ?>

    <div class="container">
        <main>
            <h2>Manajemen Arsip Dokumen</h2>
            <?php include 'templates/message.php'; ?>

            <div class="actions">
                <input type="text" id="searchInput" placeholder="Cari dokumen...">

                <select id="sortDropdown">
                    <option value="date_desc">Urutkan: Terbaru</option>
                    <option value="date_asc">Urutkan: Terlama</option>
                    <option value="name_asc">Urutkan: Nama (A-Z)</option>
                    <option value="name_desc">Urutkan: Nama (Z-A)</option>
                    <option value="size_desc">Urutkan: Ukuran (Terbesar)</option>
                    <option value="size_asc">Urutkan: Ukuran (Terkecil)</option>
                </select>

                <button id="showUploadModalBtn" class="btn btn-primary">Unggah Dokumen</button>
            </div>

            <div id="document-table-container">
                <p>Memuat data arsip...</p>
            </div>
        </main>
    </div>

    <!-- [FIX] Modal Unggah yang sebelumnya hilang, sekarang lengkap -->
    <div id="uploadModal" class="modal">
        <div class="modal-content">
            <span class="close-button">&times;</span>
            <h3>Formulir Unggah Dokumen</h3>
            <form id="uploadForm" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="judul">Judul Dokumen (Wajib)</label>
                    <input type="text" id="judul" name="judul" required>
                </div>
                <div class="form-group">
                    <label for="kategori">Pilihan Kategori (Wajib)</label>
                    <select id="kategori" name="kategori" required>
                        <option value="" disabled selected>-- Pilih Kategori --</option>
                        <option value="Laporan Keuangan">Laporan Keuangan</option>
                        <option value="Surat Keputusan Direksi">Surat Keputusan Direksi</option>
                        <option value="Dokumen Kredit">Dokumen Kredit</option>
                        <option value="Regulasi Internal">Regulasi Internal</option>
                        <option value="Lainnya">Lainnya</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="keterangan">Deskripsi/Catatan (Opsional)</label>
                    <textarea id="keterangan" name="keterangan"></textarea>
                </div>
                <div class="form-group">
                    <label for="dokumen">Pilih File (Wajib)</label>
                    <input type="file" id="dokumen" name="dokumen" required>
                </div>
                <button type="submit" class="btn btn-primary" style="width: 100%;">Unggah Sekarang</button>
            </form>
        </div>
    </div>

    <!-- [FIX] Modal Konfirmasi Hapus yang sebelumnya hilang, sekarang lengkap -->
    <div id="deleteConfirmModal" class="modal">
        <div class="modal-content">
            <h3>Konfirmasi Penghapusan</h3>
            <p>Apakah Anda yakin ingin menghapus dokumen "<strong id="deleteDocTitle"></strong>"? Tindakan ini tidak dapat dibatalkan.</p>
            <div class="modal-actions">
                <button id="cancelDeleteBtn" class="btn btn-secondary">Batal</button>
                <button id="confirmDeleteBtn" class="btn btn-danger">Ya, Hapus</button>
            </div>
        </div>
    </div>

    <!-- Modal Lihat Detail Dokumen -->
    <div id="viewDetailModal" class="modal">
        <div class="modal-content">
            <span class="close-button">&times;</span>
            <h3>Detail Dokumen</h3>
            <div id="detailContent">
                <p><strong>Judul:</strong> <span id="detailJudul"></span></p>
                <p><strong>Kategori:</strong> <span id="detailKategori"></span></p>
                <p><strong>Deskripsi:</strong> <span id="detailDeskripsi"></span></p>
                <p><strong>Ukuran File:</strong> <span id="detailUkuran"></span></p>
                <p><strong>Tanggal Unggah:</strong> <span id="detailTanggal"></span></p>
                <p><strong>Nama File Asli:</strong> <span id="detailNamaFile"></span></p>
            </div>
            <div class="modal-actions">
                <a href="#" id="detailDownloadBtn" class="btn btn-primary" download>Unduh File</a>
            </div>
        </div>
    </div>

    <div id="previewModal" class="modal modal-preview">
        <div class="modal-content">
            <span class="close-button">&times;</span>
            <h3 id="previewTitle">Pratinjau Dokumen</h3>
            <div id="previewContent" class="preview-container">
                <!-- Konten pratinjau (gambar/PDF) akan dimuat di sini -->
            </div>
        </div>
    </div>

    <script src="js/arsip_script.js"></script>
</body>

</html>