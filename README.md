<div align="center">

# Poin Sekolah

**Aplikasi pencatatan poin pelanggaran dan penghargaan siswa.**

Kelola data siswa, pantau riwayat poin, dan dukung tindak lanjut pembinaan dalam satu aplikasi.

![PHP 8.3+](https://img.shields.io/badge/PHP-8.3%2B-777BB4?logo=php&logoColor=white)
![Laravel 13](https://img.shields.io/badge/Laravel-13-FF2D20?logo=laravel&logoColor=white)
![Vite](https://img.shields.io/badge/Vite-8-646CFF?logo=vite&logoColor=white)

</div>

## Tentang Aplikasi

Poin Sekolah membantu sekolah mencatat perilaku positif maupun pelanggaran secara terstruktur. Setiap transaksi terhubung ke siswa dan aturan poin, sehingga staf dapat melihat riwayat serta ringkasan poin dengan lebih mudah.

### Fitur

- Ringkasan dashboard untuk siswa aktif, pelanggaran hari ini, dan reward hari ini.
- Pengelolaan kelas, data siswa, akun pengguna, serta aturan reward dan pelanggaran.
- Pencatatan dan riwayat transaksi poin siswa.
- Catatan tindak lanjut untuk siswa.
- Ringkasan poin pribadi siswa, termasuk status pelanggaran dan level reward.
- Hak akses yang dibedakan berdasarkan peran pengguna.

### Peran Pengguna

| Peran | Akses utama |
| --- | --- |
| Admin | Mengelola kelas, siswa, akun, aturan poin, transaksi, dan tindak lanjut. |
| Guru | Melihat data siswa, mencatat transaksi poin, dan membuat tindak lanjut. |
| Siswa | Melihat ringkasan dan riwayat poin miliknya sendiri. |

## Teknologi

- PHP 8.3 atau lebih baru
- Laravel 13
- SQLite secara default; koneksi database dapat diubah melalui `.env`
- Vite 8 dan Tailwind CSS 4

## Menjalankan Secara Lokal

Pastikan PHP, Composer, Node.js, dan npm sudah tersedia. Dari terminal:

```bash
git clone <url-repository-anda>
cd point-sekolah
composer install
cp .env.example .env
php artisan key:generate
touch database/database.sqlite
php artisan migrate --seed
npm install
npm run build
php artisan serve
```

Buka alamat yang ditampilkan oleh `php artisan serve` di browser. Untuk pengembangan frontend dengan hot reload, jalankan `npm run dev` di terminal terpisah.

Konfigurasi awal menggunakan SQLite. Jika memakai MySQL, ubah `DB_CONNECTION` dan pengaturan `DB_*` di `.env`, lalu jalankan migrasi.

## Akun Demo

Seeder demo membuat akun berikut:

| Peran | Email | Kata sandi |
| --- | --- | --- |
| Admin | `fathi@gmail.com` | `password` |
| Guru | `purmatasri@gmail.com` | `gurubk` |
| Siswa | `amatmat@gmail.com` | `muridrajin` |

Akun ini hanya untuk pengembangan lokal. Ganti atau hapus kredensial demo sebelum aplikasi digunakan di lingkungan bersama atau produksi.

## Pengujian

Jalankan seluruh pengujian dengan:

```bash
composer test
```
