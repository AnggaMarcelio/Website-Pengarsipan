// js/admin_script.js
document.addEventListener('DOMContentLoaded', () => {
    // Elemen utama
    const docTableContainer = document.getElementById('document-table-container');
    const searchInput = document.getElementById('searchInput');
    
    // Elemen Modal Unggah
    const uploadForm = document.getElementById('uploadForm');
    const uploadModal = document.getElementById('uploadModal');
    const showUploadModalBtn = document.getElementById('showUploadModalBtn');
    const closeUploadBtn = uploadModal.querySelector('.close-button');

    // Elemen Modal Hapus
    const deleteModal = document.getElementById('deleteConfirmModal');
    const cancelDeleteBtn = document.getElementById('cancelDeleteBtn');
    const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
    let docIdToDelete = null;

    // --- FUNGSI-FUNGSI UTAMA ---

    async function loadDocuments(searchTerm = '') {
        docTableContainer.innerHTML = '<p>Memuat data arsip...</p>';
        try {
            const response = await fetch(`php/get_documents.php?search=${encodeURIComponent(searchTerm)}`);
            if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
            
            const data = await response.json();
            renderTable(data.documents, data.role);
        } catch (error) {
            docTableContainer.innerHTML = `<p style="color: red;">Gagal memuat dokumen: ${error.message}</p>`;
        }
    }

    function renderTable(documents, role) {
        let tableHTML = `<table><thead><tr>
            <th>Judul Dokumen</th><th>Kategori</th><th>Ukuran</th><th>Tgl Unggah</th><th>Aksi</th>
        </tr></thead><tbody>`;
        
        if (documents.length > 0) {
            documents.forEach(doc => {
                const fileSize = doc.ukuran_file > 1024*1024 
                    ? `${(doc.ukuran_file / (1024*1024)).toFixed(2)} MB`
                    : `${(doc.ukuran_file / 1024).toFixed(2)} KB`;
                
                let actionLinks = `<a href="php/download.php?id=${doc.id}" class="action-btn download" title="Unduh file">Unduh</a>`;
                if (role === 'admin') {
                    actionLinks += ` <button class="action-btn delete" data-id="${doc.id}" data-title="${escapeHtml(doc.judul_dokumen)}" title="Hapus dokumen">Hapus</button>`;
                }

                tableHTML += `<tr>
                    <td>${escapeHtml(doc.judul_dokumen)}</td>
                    <td>${escapeHtml(doc.kategori)}</td>
                    <td>${fileSize}</td>
                    <td>${new Date(doc.tanggal_unggah).toLocaleDateString('id-ID')}</td>
                    <td class="action-links">${actionLinks}</td>
                </tr>`;
            });
        } else {
            tableHTML += `<tr><td colspan="5" style="text-align: center;">Tidak ada dokumen ditemukan.</td></tr>`;
        }
        tableHTML += `</tbody></table>`;
        docTableContainer.innerHTML = tableHTML;
    }

    function escapeHtml(unsafe) {
        return unsafe.replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/"/g, "&quot;").replace(/'/g, "&#039;");
    }

    // --- EVENT LISTENERS ---

    // Pencarian
    let searchTimeout;
    searchInput.addEventListener('input', () => {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            loadDocuments(searchInput.value.trim());
        }, 300);
    });

    // Modal Unggah
    showUploadModalBtn.onclick = () => { uploadModal.style.display = 'block'; };
    closeUploadBtn.onclick = () => { uploadModal.style.display = 'none'; };
    window.addEventListener('click', (event) => {
        if (event.target == uploadModal) uploadModal.style.display = 'none';
        if (event.target == deleteModal) deleteModal.style.display = 'none';
    });

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
            alert('Terjadi kesalahan teknis saat mengunggah.');
        } finally {
            submitButton.textContent = 'Unggah Sekarang';
            submitButton.disabled = false;
        }
    });
    
    // Modal Hapus (Event Delegation)
    docTableContainer.addEventListener('click', (e) => {
        if (e.target.classList.contains('delete')) {
            docIdToDelete = e.target.dataset.id;
            document.getElementById('deleteDocTitle').textContent = e.target.dataset.title;
            deleteModal.style.display = 'block';
        }
    });

    cancelDeleteBtn.onclick = () => {
        deleteModal.style.display = 'none';
        docIdToDelete = null;
    };

    confirmDeleteBtn.onclick = async () => {
        if (docIdToDelete) {
            try {
                const response = await fetch(`php/delete_document.php?id=${docIdToDelete}`);
                const result = await response.json();
                alert(result.message);
                if (result.status === 'success') {
                    deleteModal.style.display = 'none';
                    loadDocuments(searchInput.value.trim());
                }
            } catch (error) {
                alert('Gagal menghapus dokumen.');
            }
        }
    };

    // Inisialisasi: Muat dokumen saat halaman pertama kali dibuka
    loadDocuments();
});
