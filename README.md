# AKADEMIX

AKADEMIX adalah aplikasi sistem informasi sekolah berbasis native PHP dengan pola MVC dan database MySQL `akademix`.
Aanjai
Fitur inti:

- `Admin / TU`: CRUD guru, siswa, orang tua, mata pelajaran, kelas, penempatan siswa, jadwal, tahun ajaran & semester.
- `Guru`: input materi, tugas, absensi, nilai harian/tugas/rapor.
- `Siswa`: lihat materi semua tahun, kumpulkan tugas, lihat status tugas, kehadiran, dan rapor.
- `Orang Tua`: pantau rapor, kehadiran, dan tugas anak.

## Struktur

```text
app/
  config/
  controllers/
  core/
  models/
  views/
database/
  schema.sql
public/
  assets/css/app.css
  index.php
```

## Cara Menjalankan

1. Pastikan project berada di `D:\laragon\www\akademix`.
2. Pastikan database `akademix` sudah ada di MySQL Laragon.
3. Import file [database/schema.sql](database/schema.sql) ke database `akademix`.
4. Sesuaikan kredensial DB di [app/config/config.php](app/config/config.php) jika `root` tanpa password tidak sesuai.
5. Jalankan Laragon dengan Nginx port `8080`.
6. Buka `http://localhost:8080/akademix/public`.

## Akun Demo

Semua akun demo menggunakan password: `password`

- `admin` → Admin / TU
- `guru1` → Guru
- `siswa1` → Siswa
- `ortu1` → Orang Tua

## Catatan Teknis

- Routing menggunakan front controller `public/index.php`.
- URL utama dibangun dari `app.config.app.base_url`.
- Untuk deployment yang memakai rewrite Nginx, router sudah tetap bisa membaca path setelah `index.php`.
