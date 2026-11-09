<div align="center">

# Poin Sekolah

**Sistem pencatatan poin pelanggaran, reward, dan tindak lanjut siswa.**

Kelola data siswa dan kelas, pantau riwayat poin, serta catat perubahan data dalam satu aplikasi.

![PHP 8.3+](https://img.shields.io/badge/PHP-8.3%2B-777BB4?logo=php&logoColor=white)
![Laravel 13](https://img.shields.io/badge/Laravel-13-FF2D20?logo=laravel&logoColor=white)
![Vite](https://img.shields.io/badge/Vite-8-646CFF?logo=vite&logoColor=white)

</div>

## Tentang Aplikasi

Poin Sekolah digunakan untuk mengelola data siswa dan mencatat perilaku positif maupun pelanggaran secara terstruktur. Transaksi terhubung dengan siswa, aturan poin, dan petugas pencatat sehingga riwayat serta ringkasan poin dapat ditinjau kembali.

## Fitur

- Dashboard dengan jumlah siswa aktif, pelanggaran hari ini, dan reward hari ini.
- Pengelolaan kelas, data siswa, akun admin/guru, dan aturan poin.
- Pencatatan reward dan pelanggaran serta riwayat transaksi dengan filter jenis, siswa, kelas, dan tanggal.
- Catatan tindak lanjut siswa dan ringkasan poin pribadi siswa.
- Riwayat perubahan data siswa yang mencatat waktu dan pelakunya. Perubahan password hanya dicatat sebagai event; nilai password tidak disimpan dalam log.
- Import data siswa dari CSV yang dapat dibuka di Excel. Data kelas dipasangkan berdasarkan nama kelas dan tingkat (`class_name` + `grade`), sehingga nama kelas yang sama pada tingkat berbeda tidak tertukar.
- Export data siswa dan export transaksi poin berdasarkan filter yang sedang dipilih.
- Pembatasan satu sesi aktif per akun. Login dari perangkat lain menggantikan sesi sebelumnya; login diblokir selama 60 detik setelah tiga password salah berturut-turut.

## Hak Akses

| Peran | Akses utama |
| --- | --- |
| Admin | Mengelola kelas, siswa, pengguna, aturan poin, impor/ekspor data, transaksi, dan riwayat perubahan siswa. |
| Guru | Melihat data siswa, mencatat transaksi poin, dan menambahkan tindak lanjut. |
| Siswa | Melihat ringkasan dan riwayat poin sendiri serta mengganti password akunnya. |

## Teknologi

- PHP 8.3 atau lebih baru
- Laravel 13
- SQLite secara default; koneksi database dapat diubah melalui `.env`
- Vite 8 dan Tailwind CSS 4

## Persiapan Lokal

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

`Buka alamat lokal yang ditampilkan oleh `php artisan serve`. Untuk pengembangan frontend dengan hot reload, jalankan `npm run dev` di terminal terpisah.

Konfigurasi `.env.example` menggunakan SQLite. Untuk database lain, atur `DB_CONNECTION` dan variabel `DB_*` yang sesuai di `.env` sebelum menjalankan migrasi.

## Akun Demo

Seeder demo menyediakan akun berikut:

| Peran | Email | Password |
| --- | --- | --- |
| Admin | `fathi@gmail.com` | `password` |
| Guru | `purmatasri@gmail.com` | `gurubk` |
| Siswa | `amatmat@gmail.com` | `muridrajin` |

Gunakan akun demo hanya untuk pengembangan lokal. Ganti kredensial sebelum aplikasi digunakan di lingkungan bersama atau produksi.

## Import Data Siswa

Buka menu **Siswa** sebagai admin, lalu unduh template CSV dari tombol **Download Template**. Template memiliki satu baris contoh; ganti atau hapus baris itu sebelum mengimpor. CSV dapat dibuka dan disimpan melalui Excel.

Kolom template:

| Kolom | Keterangan |
| --- | --- |
| `nis` | Nomor induk siswa yang unik. |
| `name` | Nama siswa. |
| `email` | Email untuk login; harus unik. |
| `class_name` | Nama kelas sesuai data menu Kelas. |
| `grade` | Tingkat kelas. Digunakan bersama `class_name` untuk memilih kelas yang tepat. |
| `gender` | `L` atau `P`. |
| `status` | `active` atau `inactive`. |
| `password` | Wajib untuk siswa baru. Saat memperbarui siswa berdasarkan NIS, boleh dikosongkan agar password tidak berubah. |

Import memperbarui data yang sudah ada berdasarkan NIS. Jika ada baris tidak valid, seluruh file ditolak agar tidak terjadi import sebagian. Untuk akun siswa baru, pastikan nama kelas dan tingkat cocok dengan data kelas yang tersedia.

## Export Data

- **Data siswa:** unduh seluruh data siswa dari tombol **Export Excel** di menu Siswa. Password tidak pernah disertakan dalam file.
- **Transaksi poin:** unduh dari menu Transaksi Poin sebagai admin. Filter jenis, siswa, kelas, dan tanggal yang aktif akan diterapkan ke hasil export.

File yang diunduh menggunakan format CSV UTF-8 kompatibel Excel, bukan workbook `.xlsx` native.

## Pengujian

Jalankan seluruh pengujian dengan:

```bash
composer test
```
