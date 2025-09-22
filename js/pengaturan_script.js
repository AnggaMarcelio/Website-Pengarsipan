// js/pengaturan_script.js
document.addEventListener('DOMContentLoaded', () => {
    const profileForm = document.getElementById('profileForm');
    const passwordForm = document.getElementById('passwordForm');
    const toast = document.getElementById('toast');

    // Fungsi untuk menampilkan notifikasi toast
    function showToast(message, isSuccess = true) {
        toast.textContent = message;
        toast.className = 'toast show'; // Hapus kelas lama
        if (isSuccess) {
            toast.classList.add('success');
        } else {
            toast.classList.add('error');
        }

        setTimeout(() => {
            toast.classList.remove('show');
        }, 3000);
    }

    // Event listener untuk form profil
    if (profileForm) {
        profileForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData(profileForm);
            const submitButton = profileForm.querySelector('button[type="submit"]');
            submitButton.disabled = true;
            submitButton.textContent = 'Menyimpan...';

            try {
                const response = await fetch('php/user_actions.php', {
                    method: 'POST',
                    body: formData
                });
                const result = await response.json();
                showToast(result.message, result.status === 'success');
                if (result.status === 'success') {
                    // Jika sukses, refresh halaman agar nama di header terupdate
                    setTimeout(() => window.location.reload(), 1500);
                }
            } catch (error) {
                showToast('Terjadi kesalahan jaringan.', false);
            } finally {
                submitButton.disabled = false;
                submitButton.textContent = 'Simpan Perubahan Profil';
            }
        });
    }

    // Event listener untuk form kata sandi
    if (passwordForm) {
        passwordForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData(passwordForm);
            const submitButton = passwordForm.querySelector('button[type="submit"]');
            submitButton.disabled = true;
            submitButton.textContent = 'Menyimpan...';

            try {
                const response = await fetch('php/user_actions.php', {
                    method: 'POST',
                    body: formData
                });
                const result = await response.json();
                showToast(result.message, result.status === 'success');
                if (result.status === 'success') {
                    passwordForm.reset(); // Kosongkan form jika berhasil
                }
            } catch (error) {
                showToast('Terjadi kesalahan jaringan.', false);
            } finally {
                submitButton.disabled = false;
                submitButton.textContent = 'Simpan Kata Sandi Baru';
            }
        });
    }
});
