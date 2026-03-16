# Laravel Project Setup Guide

Dokumen ini menjelaskan **langkah-langkah menjalankan project Laravel ini setelah dicopy atau di-clone ke komputer lain**.

---

## 1. Prasyarat

Pastikan environment sudah memenuhi kebutuhan berikut:

- PHP >= 8.1  
- Composer  
- Node.js & npm  
- Database (MySQL / MariaDB / sesuai konfigurasi)
- Git (opsional, tapi sangat disarankan)
- Docker & Docker Compose (jika menggunakan Docker)

---

## 2. File yang Tidak Ikut Dicopy

Secara default, file/folder berikut **tidak disertakan** dan harus digenerate ulang:

```
vendor/
node_modules/
.env
```

Tujuan:
- Menghindari konflik sistem
- Menjaga keamanan credential
- Menyesuaikan dependency dengan environment lokal

---

## 3. Instalasi Backend (Laravel)

Masuk ke root project, lalu jalankan:

```bash
composer install
```

Pastikan versi PHP dan extension sudah sesuai jika terjadi error.

---

## 4. Setup Environment (.env)

Buat file `.env` dari template:

```bash
cp .env.example .env
```

Sesuaikan konfigurasi utama:

```env
APP_NAME=Laravel
APP_ENV=local
APP_DEBUG=true

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nama_database
DB_USERNAME=username
DB_PASSWORD=password
```

---

## 5. Generate Application Key

Langkah ini **WAJIB** dilakukan:

```bash
php artisan key:generate
```

Tanpa key, aplikasi akan error (500).

---

## 6. Instalasi Frontend (Vite)

Jika project menggunakan Vite / Tailwind / Vue / React:

```bash
npm install
npm run dev
```

Untuk build production:

```bash
npm run build
```

---

## 7. Setup Database

Jika database belum tersedia:

```bash
php artisan migrate
```

Jika menggunakan seeder:

```bash
php artisan db:seed
```

Pastikan database sudah dibuat terlebih dahulu.

---

## 8. Menjalankan Aplikasi

```bash
php artisan serve
```

Aplikasi dapat diakses di:

```
http://127.0.0.1:8000
```

---

## 9. Menjalankan dengan Docker (Opsional)

Jika menggunakan Docker:

```bash
docker-compose up --build
```

Pastikan file `.env` sudah sesuai dengan konfigurasi Docker.

---

## 10. Catatan Penting

- Jangan pernah meng-commit file `.env`
- Jangan meng-commit `vendor/` dan `node_modules/`
- Selalu gunakan `composer.lock` dan `package-lock.json`
- Bersihkan file non-project sebelum commit

---

## 11. Troubleshooting Singkat

**Application key belum ada**
```bash
php artisan key:generate
```

**Permission storage**
```bash
chmod -R 775 storage bootstrap/cache
```

**Vite tidak berjalan**
```bash
npm install
npm run dev
```

---

## 12. Informasi Tambahan

README ini ditujukan untuk kebutuhan development dan deployment internal.
Silakan sesuaikan jika project masuk tahap production.
