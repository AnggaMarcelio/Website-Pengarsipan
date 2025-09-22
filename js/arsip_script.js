document.addEventListener('DOMContentLoaded', () => {
    // --- ELEMEN UNIVERSAL ---
    const docTableContainer = document.getElementById('document-table-container');
    const searchInput = document.getElementById('searchInput');
    const sortDropdown = document.getElementById('sortDropdown');
    const showUploadModalBtn = document.getElementById('showUploadModalBtn');
    const uploadModal = document.getElementById('uploadModal');
    
    // Elemen untuk Modal Lihat Detail
    const viewDetailModal = document.getElementById('viewDetailModal');
    const closeDetailBtn = viewDetailModal ? viewDetailModal.querySelector('.close-button') : null;
    
    // Elemen untuk Modal Pratinjau
    const previewModal = document.getElementById('previewModal');
    const previewContent = document.getElementById('previewContent');
    const previewTitle = document.getElementById('previewTitle');
    const closePreviewBtn = previewModal ? previewModal.querySelector('.close-button') : null;

    // --- ELEMEN KHUSUS ADMIN ---
    const deleteModal = document.getElementById('deleteConfirmModal');

    let documentsData = [];
    let docIdToAction = null;
    let searchTimeout;

    async function loadDocuments() {
        const searchTerm = searchInput ? searchInput.value.trim() : '';
        const sortOption = sortDropdown ? sortDropdown.value : 'date_desc';
        
        if (!docTableContainer) return;

        docTableContainer.innerHTML = '<p>Memuat data arsip...</p>';
        try {
            const response = await fetch(`php/get_documents.php?search=${encodeURIComponent(searchTerm)}&sort=${sortOption}`);
            if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
            
            const data = await response.json();
            if (data.status === 'error') throw new Error(data.message);

            documentsData = data.documents;
            renderTable(documentsData, data.role);
        } catch (error) {
            docTableContainer.innerHTML = `<p style="color: red;">Gagal memuat dokumen: ${error.message}</p>`;
        }
    }

    function renderTable(documents, role) {
        let tableHTML = `<table><thead><tr>
            <th>Judul Dokumen</th>
            <th>Kategori</th>
            <th>Ukuran</th>
            <th>Tgl Unggah</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr></thead><tbody>`;
        
        if (documents && documents.length > 0) {
            documents.forEach((doc, index) => {
                const fileSize = formatFileSize(doc.ukuran_file);
                const uploadDate = new Date(doc.tanggal_unggah).toLocaleString('id-ID', {
                    day: '2-digit', month: '2-digit', year: 'numeric',
                    hour: '2-digit', minute: '2-digit'
                }).replace('.', ':');

                let statusBadge = '';
                if (doc.status === 'menunggu') statusBadge = '<span class="status-badge waiting">Menunggu</span>';
                else if (doc.status === 'disetujui') statusBadge = '<span class="status-badge approved">Disetujui</span>';
                else if (doc.status === 'ditolak') statusBadge = '<span class="status-badge rejected">Ditolak</span>';
                
                // [DIUBAH] Sekarang ada tombol "Detail" dan "Pratinjau"
                let actionLinks = `<button class="action-btn view" data-index="${index}">Detail</button>
                                   <button class="action-btn preview" data-index="${index}">Pratinjau</button>`;
                
                if (doc.status === 'disetujui') {
                    actionLinks += `<a href="php/download.php?id=${doc.id}" class="action-btn download">Unduh</a>`;
                }

                if (role === 'admin') {
                    if (doc.status === 'menunggu') {
                        actionLinks += `<button class="action-btn approve" data-id="${doc.id}">Setujui</button>
                                        <button class="action-btn reject" data-id="${doc.id}">Tolak</button>`;
                    } else {
                        actionLinks += `<button class="action-btn delete" data-id="${doc.id}" data-title="${escapeHtml(doc.judul_dokumen)}">Hapus</button>`;
                    }
                }

                tableHTML += `<tr>
                    <td>${escapeHtml(doc.judul_dokumen)}</td>
                    <td>${escapeHtml(doc.kategori)}</td>
                    <td>${fileSize}</td>
                    <td>${uploadDate}</td>
                    <td>${statusBadge}</td>
                    <td class="action-links">${actionLinks}</td>
                </tr>`;
            });
        } else {
            tableHTML += `<tr><td colspan="6" style="text-align: center;">Tidak ada dokumen ditemukan.</td></tr>`;
        }
        tableHTML += `</tbody></table>`;
        docTableContainer.innerHTML = tableHTML;
    }

    function formatFileSize(bytes) {
        if (!bytes || bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }

    function escapeHtml(unsafe) {
        return unsafe ? String(unsafe).replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/"/g, "&quot;").replace(/'/g, "&#039;") : "";
    }

    // --- EVENT LISTENERS ---
    if (searchInput) {
        searchInput.addEventListener('input', () => {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(loadDocuments, 300);
        });
    }
    if (sortDropdown) {
        sortDropdown.addEventListener('change', loadDocuments);
    }

    if (docTableContainer) {
        docTableContainer.addEventListener('click', async (e) => {
            const target = e.target.closest('.action-btn');
            if (!target) return;

            // Logika untuk tombol Detail (yang lama, sekarang aktif kembali)
            if (target.classList.contains('view') && viewDetailModal) {
                const docIndex = target.dataset.index;
                const doc = documentsData[docIndex];
                viewDetailModal.querySelector('#detailJudul').textContent = doc.judul_dokumen;
                viewDetailModal.querySelector('#detailKategori').textContent = doc.kategori;
                viewDetailModal.querySelector('#detailDeskripsi').textContent = doc.keterangan || '-';
                viewDetailModal.querySelector('#detailUkuran').textContent = formatFileSize(doc.ukuran_file);
                viewDetailModal.querySelector('#detailTanggal').textContent = new Date(doc.tanggal_unggah).toLocaleString('id-ID');
                viewDetailModal.querySelector('#detailNamaFile').textContent = doc.nama_file_asli;
                viewDetailModal.querySelector('#detailDownloadBtn').href = `php/download.php?id=${doc.id}`;
                viewDetailModal.style.display = 'block';
            }

            // Logika untuk tombol Pratinjau
            if (target.classList.contains('preview')) {
                const docIndex = target.dataset.index;
                const doc = documentsData[docIndex];
                const fileType = doc.tipe_file;
                const previewUrl = `php/preview.php?id=${doc.id}`;

                if (previewTitle) previewTitle.textContent = escapeHtml(doc.judul_dokumen);
                if (previewContent) previewContent.innerHTML = '';

                if (fileType.startsWith('image/')) {
                    const img = document.createElement('img');
                    img.src = previewUrl;
                    img.style.maxWidth = '100%';
                    img.style.height = 'auto';
                    if (previewContent) previewContent.appendChild(img);
                } else if (fileType === 'application/pdf') {
                    const embed = document.createElement('embed');
                    embed.src = previewUrl;
                    embed.type = 'application/pdf';
                    embed.style.width = '100%';
                    embed.style.height = '75vh';
                    if (previewContent) previewContent.appendChild(embed);
                } else {
                    if (previewContent) previewContent.innerHTML = `<p style="text-align:center; padding: 2rem;">Pratinjau tidak tersedia untuk tipe file ini.<br>Silakan unduh dokumen untuk melihat isinya.</p>`;
                }
                
                if (previewModal) previewModal.style.display = 'block';
            }

            if (target.classList.contains('approve') || target.classList.contains('reject')) {
                const docId = target.dataset.id;
                const action = target.classList.contains('approve') ? 'approve_document' : 'reject_document';
                const formData = new FormData();
                formData.append('action', action);
                formData.append('doc_id', docId);
                const response = await fetch('php/admin_actions.php', { method: 'POST', body: formData });
                const result = await response.json();
                alert(result.message);
                if (result.status === 'success') loadDocuments();
            }

            if (target.classList.contains('delete') && deleteModal) {
                docIdToAction = target.dataset.id;
                deleteModal.querySelector('#deleteDocTitle').textContent = target.dataset.title;
                deleteModal.style.display = 'block';
            }
        });
    }

    // Modal Listeners
    if (showUploadModalBtn) {
        showUploadModalBtn.onclick = () => { if (uploadModal) uploadModal.style.display = 'block'; };
    }
    
    if (uploadModal) {
        const closeUploadBtn = uploadModal.querySelector('.close-button');
        if (closeUploadBtn) closeUploadBtn.onclick = () => { uploadModal.style.display = 'none'; };
        
        const uploadForm = document.getElementById('uploadForm');
        if (uploadForm) {
            uploadForm.addEventListener('submit', async (e) => {
                e.preventDefault();
                const formData = new FormData(uploadForm);
                const submitButton = uploadForm.querySelector('button[type="submit"]');
                submitButton.textContent = 'Mengunggah...';
                submitButton.disabled = true;
                try {
                    const response = await fetch('php/upload.php', { method: 'POST', body: formData });
                    const result = await response.json();
                    alert(result.message);
                    if (result.status === 'success') {
                        uploadForm.reset();
                        uploadModal.style.display = 'none';
                        loadDocuments();
                    }
                } catch (error) {
                    alert('Terjadi kesalahan teknis.');
                } finally {
                    submitButton.textContent = 'Kirim untuk Persetujuan';
                    submitButton.disabled = false;
                }
            });
        }
    }

    if (closeDetailBtn) {
        closeDetailBtn.onclick = () => { viewDetailModal.style.display = 'none'; };
    }

    if (closePreviewBtn) {
        closePreviewBtn.onclick = () => { previewModal.style.display = 'none'; };
    }

    if (deleteModal) {
        const cancelDeleteBtn = document.getElementById('cancelDeleteBtn');
        const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
        const closeDeleteBtn = deleteModal.querySelector('.close-button');
        if(cancelDeleteBtn) cancelDeleteBtn.onclick = () => { deleteModal.style.display = 'none'; docIdToAction = null; };
        if(closeDeleteBtn) closeDeleteBtn.onclick = () => { deleteModal.style.display = 'none'; docIdToAction = null; };
        if(confirmDeleteBtn) confirmDeleteBtn.onclick = async () => {
            if (docIdToAction) {
                try {
                    const response = await fetch(`php/delete_document.php?id=${docIdToAction}`);
                    const result = await response.json();
                    alert(result.message);
                    if (result.status === 'success') {
                        deleteModal.style.display = 'none';
                        loadDocuments();
                    }
                } catch (error) {
                    alert('Gagal menghapus dokumen.');
                }
            }
        };
    }

    window.onclick = (event) => {
        if (uploadModal && event.target == uploadModal) uploadModal.style.display = 'none';
        if (viewDetailModal && event.target == viewDetailModal) viewDetailModal.style.display = 'none';
        if (previewModal && event.target == previewModal) previewModal.style.display = 'none';
        if (deleteModal && event.target == deleteModal) deleteModal.style.display = 'none';
    };

    loadDocuments();
});
