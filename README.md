# 🌟 KUNCUPMEKAR - Website Resmi PAUD Kuncup Mekar

![WordPress](https://img.shields.io/badge/CMS-WordPress-blue)
![Status](https://img.shields.io/badge/Status-Aktif-brightgreen)
![Versi](https://img.shields.io/badge/Versi-2.0-orange)

## 📋 Daftar Isi
- [Tentang KUNCUPMEKAR](#-tentang-kuncupmekar)
- [Fitur Website](#-fitur-website)
- [Teknologi yang Digunakan](#-teknologi-yang-digunakan)
- [Panduan Pengguna](#-panduan-pengguna)
- [Struktur Website](#-struktur-website)
- [Optimasi Performa](#-optimasi-performa)
- [Pemeliharaan](#-pemeliharaan)
- [FAQ](#-faq)

## � Tentang KUNCUPMEKAR

Selamat datang di repositori website resmi **PAUD Kuncup Mekar**!

PAUD Kuncup Mekar adalah lembaga pendidikan anak usia dini yang berkomitmen untuk memberikan pendidikan berkualitas dengan pendekatan yang menyenangkan dan berpusat pada anak. Website ini merupakan platform digital resmi kami untuk berbagi informasi, kegiatan, dan berinteraksi dengan orang tua serta masyarakat.

> "Menanamkan benih pendidikan sejak dini untuk masa depan yang cerah"

## 🎯 Fitur Website

Website KUNCUPMEKAR dilengkapi dengan berbagai fitur untuk memudahkan pengunjung mendapatkan informasi dan berinteraksi dengan sekolah:

- **📰 Berita & Pengumuman**: Informasi terbaru tentang kegiatan dan pengumuman penting
- **� Pendaftaran Online**: Sistem pendaftaran siswa baru secara online
- **📸 Galeri Kegiatan**: Dokumentasi foto dan video kegiatan sekolah
- **👨‍👩‍👧‍� Profil Guru & Staf**: Informasi tentang tenaga pendidik dan staf
- **� Program Pembelajaran**: Detail kurikulum dan program pendidikan
- **� Kalender Akademik**: Jadwal kegiatan dan hari penting sekolah
- **📞 Kontak & Lokasi**: Informasi kontak dan peta lokasi sekolah
- **💬 Forum Diskusi**: Wadah komunikasi antara orang tua dan sekolah
- **📱 Responsif**: Tampilan optimal di semua perangkat (desktop, tablet, mobile)

## 💻 Teknologi yang Digunakan

Website KUNCUPMEKAR dibangun dengan teknologi modern untuk memastikan performa, keamanan, dan kemudahan pengelolaan:

- **CMS**: WordPress
- **Bahasa Pemrograman**: PHP, JavaScript, HTML5, CSS3
- **Database**: MySQL
- **Framework CSS**: Bootstrap
- **Plugin Utama**:
  - TP Education (Manajemen Pendidikan)
  - Visual Form Builder (Formulir Pendaftaran)
  - WP Super Cache (Optimasi Performa)
  - Yoast SEO (Optimasi Mesin Pencari)
  - Wordfence Security (Keamanan Website)

## 📘 Panduan Pengguna

### Untuk Pengunjung

1. **Menjelajahi Website**:
   - Gunakan menu navigasi di bagian atas untuk mengakses berbagai halaman
   - Gunakan fitur pencarian untuk menemukan informasi spesifik

2. **Pendaftaran Siswa Baru**:
   - Kunjungi halaman "Pendaftaran"
   - Isi formulir pendaftaran dengan lengkap
   - Unggah dokumen yang diperlukan
   - Kirim formulir dan tunggu konfirmasi via email

3. **Melihat Galeri**:
   - Kunjungi halaman "Galeri"
   - Pilih album kegiatan yang ingin dilihat
   - Klik pada gambar untuk melihat ukuran penuh

### Untuk Administrator

1. **Login ke Dashboard**:
   - Akses `/wp-admin`
   - Masukkan username dan password

2. **Mengelola Konten**:
   - Gunakan menu "Posts" untuk mengelola berita dan pengumuman
   - Gunakan menu "Pages" untuk mengelola halaman statis
   - Gunakan menu "TP Education" untuk mengelola konten pendidikan

3. **Mengelola Media**:
   - Gunakan menu "Media" untuk mengunggah dan mengelola gambar/video
   - Organisasikan media ke dalam folder untuk kemudahan akses

## 🏗️ Struktur Website

Website KUNCUPMEKAR memiliki struktur sebagai berikut:

```
KUNCUPMEKAR/
├── wp-admin/            # Area administrasi WordPress
├── wp-content/          # Konten website
│   ├── themes/          # Tema website
│   │   └── kids-education/  # Tema utama
│   ├── plugins/         # Plugin yang digunakan
│   ├── uploads/         # Media yang diunggah
│   └── cache/           # File cache
├── wp-includes/         # File inti WordPress
├── index.php            # File utama
├── wp-config.php        # Konfigurasi WordPress
└── .htaccess            # Konfigurasi server
```

## ⚡ Optimasi Performa

Website KUNCUPMEKAR telah dioptimalkan untuk kecepatan dan efisiensi maksimal:

### 1. Optimasi Pemuatan

- **Sistem Pemuatan Cepat**: Menggunakan `wp-fast-load.php` untuk bootstrap yang efisien
- **Lazy Loading**: Memuat gambar hanya saat diperlukan
- **Minifikasi**: CSS dan JavaScript yang dikompresi

### 2. Optimasi Cache

- **Cache Halaman**: Menyimpan halaman HTML untuk pengunjung berikutnya
- **Cache Objek**: Menyimpan hasil kueri database
- **Cache Browser**: Mengoptimalkan penyimpanan aset di browser

### 3. Optimasi Database

- **Kueri Efisien**: Struktur kueri yang dioptimalkan
- **Pemeliharaan Otomatis**: Pembersihan dan optimasi database terjadwal

### 4. Optimasi Server

- **Kompresi GZIP**: Transfer data lebih cepat
- **Konfigurasi .htaccess**: Pengaturan server yang dioptimalkan
- **Header Cache**: Penyimpanan aset statis lebih lama

## � Pemeliharaan

Untuk memastikan website tetap optimal, lakukan pemeliharaan rutin berikut:

1. **Update Berkala**:
   - WordPress core (minimal sebulan sekali)
   - Plugin dan tema (segera setelah update tersedia)
   - PHP ke versi terbaru yang stabil (setiap 6 bulan)

2. **Backup Rutin**:
   - Backup database (mingguan)
   - Backup file website (mingguan)
   - Simpan backup di lokasi terpisah

3. **Pemeliharaan Database**:
   - Bersihkan post revisions (bulanan)
   - Bersihkan spam comments (mingguan)
   - Optimasi tabel database (bulanan)

4. **Keamanan**:
   - Scan malware (mingguan)
   - Periksa log keamanan (mingguan)
   - Update password (setiap 3 bulan)

## ❓ FAQ

### Bagaimana cara mengakses dashboard admin?
Akses melalui URL: `https://paud-kuncupmekar.sch.id/wp-admin` dan masukkan kredensial yang telah diberikan.

### Bagaimana cara menambahkan berita baru?
Login ke dashboard admin, pilih "Posts" > "Add New", isi judul dan konten, tambahkan kategori dan gambar, lalu klik "Publish".

### Bagaimana cara mengubah informasi kontak?
Login ke dashboard admin, pilih "Pages", cari halaman "Kontak", klik "Edit", ubah informasi yang diperlukan, lalu klik "Update".

### Bagaimana cara melihat pendaftar baru?
Login ke dashboard admin, pilih "TP Education" > "Pendaftaran" untuk melihat daftar pendaftar baru.

### Bagaimana jika lupa password?
Klik "Lost your password?" di halaman login, masukkan email admin, lalu ikuti instruksi yang dikirim ke email tersebut.

### Bagaimana cara mengoptimalkan gambar sebelum diunggah?
Gunakan tools seperti TinyPNG atau ImageOptim untuk mengompres gambar sebelum diunggah ke website.

---

<p align="center">
  <b>PAUD Kuncup Mekar</b><br>
  Menanamkan benih pendidikan sejak dini untuk masa depan yang cerah<br>
  <a href="https://paud-kuncupmekar.sch.id">https://paud-kuncupmekar.sch.id</a>
</p>
