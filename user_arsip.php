<?php
require_once 'php/config.php';

// Proteksi Halaman: Hanya user yang sudah login yang bisa akses
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Arsip Dokumen - BSB ARC</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <?php include 'templates/user_header.php'; ?>

    <div class="container">
        <main>
            <h2>Arsip Dokumen</h2>
            <?php include 'templates/message.php'; ?>

            <div class="actions">
                <input type="text" id="searchInput" placeholder="Cari dokumen...">

                <select id="sortDropdown">
                    <option value="date_desc">Urutkan: Terbaru</option>
                    <option value="date_asc">Urutkan: Terlama</option>
                    <option value="name_asc">Urutkan: Nama (A-Z)</option>
                    <option value="name_desc">Urutkan: Nama (Z-A)</option>
                </select>

                <!-- Tombol Unggah BARU untuk User -->
                <button id="showUploadModalBtn" class="btn btn-primary">Unggah Dokumen</button>
            </div>

            <div id="document-table-container">
                <p>Memuat data arsip...</p>
            </div>
        </main>
    </div>

    <!-- Modal Unggah BARU untuk User -->
    <div id="uploadModal" class="modal">
        <div class="modal-content">
            <span class="close-button">&times;</span>
            <h3>Unggah Dokumen Baru</h3>
            <p>Dokumen yang Anda unggah akan ditinjau oleh Admin sebelum ditampilkan untuk umum.</p>
            <form id="uploadForm" enctype="multipart/form-data">
                <div class="form-group"><label for="judul">Judul Dokumen</label><input type="text" id="judul" name="judul" required></div>
                <div class="form-group"><label for="kategori">Kategori</label><select id="kategori" name="kategori" required>
                        <option value="" disabled selected>-- Pilih --</option>
                        <option value="Laporan Keuangan">Laporan Keuangan</option>
                        <option value="Regulasi Internal">Regulasi Internal</option>
                        <option value="Lainnya">Lainnya</option>
                    </select></div>
                <div class="form-group"><label for="keterangan">Deskripsi</label><textarea id="keterangan" name="keterangan"></textarea></div>
                <div class="form-group"><label for="dokumen">File</label><input type="file" id="dokumen" name="dokumen" required></div>
                <button type="submit" class="btn btn-primary" style="width: 100%;">Kirim untuk Persetujuan</button>
            </form>
        </div>
    </div>

    <!-- Modal Lihat Detail (Sama seperti sebelumnya) -->
    <div id="viewDetailModal" class="modal">
        <div class="modal-content">
            <span class="close-button">&times;</span>
            <h3>Detail Dokumen</h3>
            <div id="detailContent">
                <p><strong>Judul:</strong> <span id="detailJudul"></span></p>
                <p><strong>Kategori:</strong> <span id="detailKategori"></span></p>
                <p><strong>Deskripsi:</strong> <span id="detailDeskripsi"></span></p>
                <p><strong>Ukuran:</strong> <span id="detailUkuran"></span></p>
                <p><strong>Tanggal:</strong> <span id="detailTanggal"></span></p>
                <p><strong>File Asli:</strong> <span id="detailNamaFile"></span></p>
            </div>
            <div class="modal-actions"><a href="#" id="detailDownloadBtn" class="btn btn-primary" download>Unduh File</a></div>
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