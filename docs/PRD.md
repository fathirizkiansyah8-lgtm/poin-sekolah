# Product Requirements Document

## Informasi Dokumen

| Item | Nilai |
| --- | --- |
| Produk | Poin Sekolah |
| Jenis dokumen | PRD baseline berdasarkan fitur yang sudah diimplementasikan |
| Versi | 1.0 |
| Tanggal | 9 November 2026 |
| Status | Draft untuk validasi pemilik produk |

Dokumen ini menggambarkan perilaku aplikasi yang ditemukan pada kode saat ini. Bagian **Belum Termasuk** dan **Keputusan Produk** memisahkan fitur yang belum ada dan kebijakan yang masih perlu disepakati; bagian tersebut bukan klaim bahwa fitur sudah tersedia.

## Ringkasan Produk

Poin Sekolah adalah aplikasi web untuk mengelola data siswa dan kelas, mencatat poin pelanggaran maupun reward, memantau tindak lanjut, dan memberikan siswa akses untuk melihat ringkasan serta riwayat poin miliknya.

Pencatatan poin mengacu pada aturan aktif agar nilai poin, jenis transaksi, siswa, dan petugas tercatat bersama. Sistem menyediakan ringkasan poin dan histori perubahan data siswa untuk mendukung pemantauan.

## Masalah dan Tujuan

### Masalah

- Data pelanggaran, reward, dan tindak lanjut perlu dicatat dengan format yang konsisten.
- Petugas membutuhkan cara untuk meninjau riwayat poin berdasarkan siswa, kelas, jenis, dan tanggal.
- Siswa perlu melihat ringkasan dan riwayat poinnya tanpa mengakses data siswa lain.
- Perubahan data siswa perlu memiliki jejak pelaku, waktu, dan nilai yang berubah.

### Tujuan Produk

1. Memusatkan pengelolaan kelas, siswa, akun petugas, aturan poin, transaksi poin, dan tindak lanjut.
2. Membatasi akses sesuai peran admin, guru, dan siswa.
3. Menyajikan jumlah poin pelanggaran dan reward secara terpisah serta menghitung nilai bersih.
4. Menyediakan histori transaksi dan perubahan data yang dapat ditinjau kembali.
5. Mendukung pengelolaan data siswa secara individual maupun melalui CSV.

## Pengguna dan Hak Akses

| Peran | Kebutuhan dan akses saat ini |
| --- | --- |
| Admin | Mengelola kelas, data siswa, akun admin/guru, dan aturan poin; melihat data siswa dan histori perubahan; mengelola transaksi serta tindak lanjut; menggunakan impor dan ekspor. |
| Guru | Melihat daftar dan detail siswa; mencatat atau menghapus transaksi poin; menambahkan tindak lanjut; melihat dashboard dan daftar transaksi. |
| Siswa | Melihat ringkasan poin, riwayat transaksi dan follow-up miliknya; mengganti password sendiri. |

Sesi siswa yang berhasil login diarahkan ke halaman **Poin Saya**. Pembatasan role dilakukan pada route server, bukan hanya dengan menyembunyikan menu.

## Ruang Lingkup

### Kebutuhan Fungsional

#### FR-01 — Autentikasi dan sesi

- Pengguna masuk menggunakan email dan password.
- Sistem membatasi login gagal berturut-turut dan memberi jeda 60 detik setelah tiga percobaan gagal.
- Satu akun hanya mempertahankan satu sesi aplikasi aktif. Login baru menggantikan sesi sebelumnya; sesi lama ditolak pada request berikutnya.
- Logout mengakhiri sesi dan mengosongkan token sesi aktif akun.

**Kriteria penerimaan:** pengguna yang valid masuk ke area sesuai perannya; kredensial salah tidak membuat sesi terautentikasi; setelah batas percobaan, login ditolak sementara; sesi lama tidak dapat melanjutkan penggunaan setelah login baru.

#### FR-02 — Pengelolaan kelas, siswa, pengguna, dan aturan poin

- Admin dapat membuat, melihat, mengubah, dan menghapus kelas. Kelas yang masih memiliki siswa tidak dapat dihapus.
- Admin dapat membuat, melihat, mengubah, dan menghapus data siswa. Data siswa mencakup akun pengguna, NIS unik, kelas, gender, dan status aktif/nonaktif.
- Admin dapat mengelola akun admin dan guru, termasuk nama, email, username, NIP, role, status, dan password.
- Admin dapat mengelola aturan poin dengan kode unik, jenis pelanggaran/reward, kategori, nama, nilai poin, deskripsi, dan status aktif.
- Aturan poin yang sudah digunakan pada transaksi tidak dapat dihapus.

**Kriteria penerimaan:** data yang melanggar validasi keunikan atau nilai yang diperbolehkan ditolak; kelas dan aturan yang masih direferensikan transaksi/siswa dilindungi dari penghapusan sesuai aturan aplikasi.

#### FR-03 — Impor dan ekspor data siswa

- Admin dapat mengunduh template CSV dan mengimpor CSV dengan kolom `nis`, `name`, `email`, `class_name`, `grade`, `gender`, `status`, dan `password`.
- Kelas dicocokkan berdasarkan kombinasi nama kelas dan tingkat.
- Baris dengan NIS yang sudah ada memperbarui siswa; password boleh kosong saat memperbarui.
- File dengan kesalahan validasi ditolak seluruhnya, bukan diterapkan sebagian.
- Admin dapat mengekspor data siswa ke CSV. Password tidak diekspor.

**Kriteria penerimaan:** header wajib dan keunikan NIS/email diperiksa; kombinasi kelas yang tidak ditemukan ditolak; file valid dapat membuat atau memperbarui siswa; satu baris tidak valid membatalkan impor file.

#### FR-04 — Pencatatan transaksi poin

- Admin dan guru dapat mencatat transaksi untuk siswa aktif sebagai pelanggaran atau reward.
- Transaksi harus menggunakan aturan poin aktif dengan jenis yang sama. Nilai poin transaksi diambil dari aturan tersebut.
- Transaksi menyimpan siswa, aturan, petugas pencatat, jenis, nilai poin, waktu kejadian, dan catatan opsional.
- Bukti opsional menerima JPG, JPEG, PNG, atau PDF dengan ukuran maksimum 2 MB.
- Admin dan guru dapat melihat transaksi, memfilter berdasarkan jenis, siswa, kelas, serta rentang tanggal, membuka detail, dan menghapus transaksi.
- Admin dapat mengekspor hasil transaksi sesuai filter aktif ke CSV.

**Kriteria penerimaan:** transaksi dengan aturan nonaktif atau jenis yang tidak sesuai tidak dapat disimpan; lampiran dengan format atau ukuran di luar batas ditolak; filter diterapkan pada daftar dan hasil ekspor.

#### FR-05 — Ringkasan dan riwayat poin

- Sistem menghitung total pelanggaran dan reward secara terpisah dari transaksi siswa.
- Nilai bersih dihitung sebagai total reward dikurangi total pelanggaran.
- Tingkat pelanggaran saat ini: 25 poin = Pembinaan, 50 = SP 1, 75 = SP 2, dan 100 = Tidak Lanjut Intensif; di bawah 25 = Aman.
- Tingkat reward saat ini: 25 poin = Bronze, 50 = Silver, dan 100 = Gold; di bawah 25 = Starter.
- Admin dan guru dapat melihat ringkasan di detail siswa; siswa hanya dapat melihat ringkasan dan riwayat miliknya sendiri.

**Kriteria penerimaan:** perubahan transaksi tercermin pada jumlah dan tingkat ringkasan; pengguna siswa tidak dapat meminta ringkasan siswa lain melalui halaman Poin Saya.

#### FR-06 — Tindak lanjut siswa

- Admin dan guru dapat mencatat tindak lanjut pada profil siswa dengan jenis `coaching`, `warning`, `parent_call`, `appreciation`, `certificate`, atau `other`.
- Tindak lanjut menyimpan tanggal dan catatan wajib; transaksi poin terkait bersifat opsional.
- Siswa dapat melihat riwayat tindak lanjut miliknya.

**Kriteria penerimaan:** catatan tanpa tanggal, tanpa jenis valid, atau tanpa isi ditolak; tindak lanjut tersimpan dengan petugas yang sedang login.

#### FR-07 — Histori perubahan data siswa

- Sistem mencatat perubahan data profil siswa yang dilakukan melalui pembaruan satuan maupun impor.
- Histori menyimpan siswa, pelaku, nama dan role pelaku sebagai snapshot, perubahan dalam JSON, serta waktu pencatatan.
- Perubahan nama, email, NIS, kelas, gender, dan status menyimpan nilai lama dan baru. Perubahan password hanya dicatat sebagai event bahwa password berubah; nilai password tidak masuk histori.
- Histori perubahan ditampilkan kepada admin di halaman detail siswa.

**Kriteria penerimaan:** perubahan yang benar-benar terjadi menghasilkan entri histori; pembaruan tanpa perubahan tidak menghasilkan entri; password mentah maupun hash tidak ditampilkan dalam histori.

#### FR-08 — Dashboard

- Dashboard admin dan guru menampilkan jumlah siswa aktif, jumlah pelanggaran hari ini, dan jumlah reward hari ini.
- Akun siswa yang membuka dashboard diarahkan ke halaman Poin Saya.

**Kriteria penerimaan:** angka dashboard dihitung dari data aktif dan transaksi dengan tanggal hari ini.

#### FR-09 — Penggantian password siswa

- Siswa dapat mengganti password dengan memasukkan password saat ini dan password baru beserta konfirmasi.
- Password baru minimal 8 karakter.

**Kriteria penerimaan:** password saat ini yang salah atau konfirmasi yang tidak cocok ditolak; perubahan password berhasil hanya setelah validasi lulus.

### Kebutuhan Nonfungsional

- **Keamanan:** password disimpan menggunakan hashing; route dan operasi dilindungi autentikasi serta role; sesi aktif divalidasi pada setiap request yang dilindungi.
- **Validasi data:** input, relasi kelas, aturan poin, format file bukti, dan CSV divalidasi di server.
- **Privasi:** siswa hanya mengakses poin dan tindak lanjut miliknya; histori perubahan profil dibatasi untuk admin.
- **Integritas:** transaksi menyimpan referensi aturan dan petugas; histori perubahan menyimpan snapshot pelaku agar identitas tetap dapat dibaca bila akun pelaku dihapus.
- **Ekspor:** file siswa dan transaksi berupa CSV UTF-8 yang dapat dibuka di Excel, bukan file workbook XLSX native.
- **Teknologi saat ini:** PHP 8.4, Laravel 13, Vite 8, dan Tailwind CSS 4. SQLite disediakan sebagai konfigurasi awal menurut README; database aktual mengikuti konfigurasi lingkungan.

## Data Utama

| Entitas | Kegunaan |
| --- | --- |
| `users` | Identitas login, role, status, serta token sesi aktif. |
| `school_classes` | Data nama kelas, tingkat, jurusan, dan tahun ajaran. |
| `students` | Profil siswa, NIS, gender, status, dan hubungan ke akun serta kelas. |
| `point_rules` | Definisi poin pelanggaran atau reward. |
| `point_transactions` | Catatan transaksi poin siswa dan petugas pencatat. |
| `follow_ups` | Catatan tindak lanjut pada siswa dan opsional pada transaksi. |
| `student_changes` | Histori perubahan profil siswa dan aktor perubahan. |
| Tabel infrastruktur Laravel | `sessions`, `password_reset_tokens`, tabel cache, dan tabel antrean pekerjaan. |

Diagram relasi: [ERD PNG](../erd-web-point-sekolah.png) · [Sumber ERD SVG](../erd-web-point-sekolah.svg).

## Di Luar Cakupan Saat Ini

Fitur berikut tidak ditemukan pada implementasi yang ditinjau dan tidak dianggap tersedia dalam PRD baseline ini:

- Notifikasi otomatis kepada orang tua/wali atau kanal komunikasi eksternal.
- Banding atau alur persetujuan berjenjang atas transaksi.
- Penggunaan beberapa sesi/perangkat secara bersamaan untuk satu akun.
- Pengaturan ambang status poin melalui antarmuka admin; ambang saat ini ditetapkan di kode.
- Ekspor workbook XLSX native.
- Pemulihan password melalui email.

## Keputusan Produk yang Perlu Divalidasi

1. Apakah ambang Pembinaan/SP dan level reward harus tetap seperti aturan kode saat ini atau dapat diubah admin?
2. Apakah guru boleh menghapus transaksi, atau penghapusan harus dibatasi untuk admin dan/atau memiliki alasan serta histori audit?
3. Berapa lama bukti transaksi dan histori perubahan perlu disimpan?
4. Apakah diperlukan persetujuan, koreksi, atau pembatalan transaksi tanpa menghapus histori?
5. Apakah orang tua/wali perlu akun atau notifikasi, dan data apa yang boleh mereka lihat?
6. Apa kebijakan akhir untuk akun nonaktif yang masih memiliki sesi atau histori terkait?

## Indikator Keberhasilan

Indikator operasional berikut dapat digunakan untuk evaluasi; target numeriknya perlu ditetapkan pemilik produk:

- Persentase transaksi yang memiliki siswa, aturan, waktu kejadian, dan petugas yang valid.
- Keberhasilan impor CSV untuk file valid dan penolakan utuh untuk file tidak valid.
- Konsistensi ringkasan poin dengan penjumlahan transaksi terkait.
- Jumlah insiden akses lintas peran atau akses siswa ke data siswa lain.
- Proporsi perubahan profil siswa yang memiliki histori pelaku dan waktu.

## Referensi Implementasi

- [README proyek](../README.md)
- [Definisi route dan pembatasan role](../routes/web.php)
- [Migration data siswa](../database/migrations/2026_09_03_131050_create_students_table.php)
- [Migration histori perubahan siswa](../database/migrations/2026_11_04_192742_create_student_changes_table.php)
- [ERD PNG](../erd-web-point-sekolah.png)