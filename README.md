# Website Pengarsipan Dokumen BSB

Selamat datang di repositori Website Pengarsipan Dokumen BSB. Aplikasi ini adalah sistem manajemen dokumen berbasis web yang dirancang untuk memudahkan proses pengarsipan, pencarian, dan pengelolaan dokumen secara digital.

## 📜 Tentang Proyek

Proyek ini dibangun sebagai solusi modern untuk menggantikan sistem pengarsipan manual. Dengan antarmuka yang ramah pengguna, aplikasi ini memungkinkan admin dan pengguna untuk mengelola dokumen dengan efisien, aman, dan terpusat. Sistem ini memisahkan hak akses antara admin dan pengguna biasa untuk menjaga integritas dan keamanan data.

## ✨ Fitur Utama

Aplikasi ini dilengkapi dengan berbagai fitur untuk menunjang kebutuhan manajemen dokumen:

-   🔐 **Sistem Otentikasi**: Proses registrasi dan login yang aman untuk pengguna dan admin.
-   👤 **Manajemen Pengguna (Admin)**: Admin dapat menambah, mengubah, dan menghapus akun pengguna.
-   🗂️ **Manajemen Arsip Lengkap**:
    -   **Unggah Dokumen**: Pengguna dapat mengunggah dokumen dalam format seperti `.pdf` dan `.docx`.
    -   **Pratinjau**: Lihat pratinjau dokumen langsung di browser tanpa perlu mengunduh.
    -   **Unduh**: Unduh dokumen ke perangkat lokal.
    -   **Hapus**: Hapus dokumen yang sudah tidak diperlukan.
-   📈 **Dashboard Interaktif**:
    -   **Admin**: Menampilkan ringkasan jumlah pengguna dan total dokumen yang diarsipkan.
    -   **Pengguna**: Menampilkan jumlah dokumen yang telah diunggah oleh pengguna tersebut.
-   ⚙️ **Pengaturan Akun**: Pengguna dapat mengubah informasi akun dan kata sandi mereka sendiri.
-   🔍 **Pencarian & Filter**: (Fitur yang dapat dikembangkan) Memudahkan pencarian dokumen berdasarkan nama, tanggal, atau kategori.

## 💻 Teknologi yang Digunakan

-   **Backend**: PHP (dengan PDO untuk koneksi database)
-   **Frontend**: HTML, CSS, JavaScript
-   **Database**: MySQL / MariaDB
-   **Server**: Direkomendasikan menggunakan XAMPP atau WAMP

## 🚀 Panduan Instalasi (Getting Started)

Ikuti langkah-langkah berikut untuk menjalankan proyek ini di lingkungan lokal Anda.

### Prasyarat

-   Pastikan Anda telah menginstal **XAMPP** atau tumpukan AMP (Apache, MySQL, PHP) lainnya.

### Langkah-langkah Instalasi

1.  **Clone Repositori**
    ```bash
    git clone https://github.com/AnggaMarcelio/Website-Pengarsipan.git
    ```

2.  **Pindahkan Folder Proyek**
    -   Pindahkan folder `Website-Pengarsipan` ke dalam direktori `htdocs` di dalam folder instalasi XAMPP Anda. (Contoh: `C:/xampp/htdocs/`)

3.  **Setup Database**
    -   Buka **phpMyAdmin** dari control panel XAMPP Anda (`http://localhost/phpmyadmin`).
    -   Buat database baru dengan nama `bsb_arc_db`.
    -   Impor file database `.sql` yang ada ke dalam database `bsb_arc_db`.
    > **Catatan**: Anda perlu menyediakan file `bsb_arc_db.sql` agar orang lain dapat membuat struktur tabel yang sama.

4.  **Konfigurasi Koneksi**
    -   File konfigurasi database terletak di `php/config.php`. Secara default, konfigurasi sudah disesuaikan untuk lingkungan XAMPP standar.
    ```php
    define('DB_HOST', 'localhost');
    define('DB_USER', 'root');
    define('DB_PASS', '');
    define('DB_NAME', 'bsb_arc_db');
    ```

5.  **Jalankan Aplikasi**
    -   Buka browser Anda dan akses proyek melalui URL: `http://localhost/Website-Pengarsipan/login.php`


