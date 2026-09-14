# Quoros

Aplikasi Laravel + Vite untuk portal novel / reading platform.

## Yang harus diinstall dulu

Sebelum menjalankan project ini, install tool berikut di komputer Anda:

- PHP 8.3
- Composer
- Node.js 18 atau 20 LTS
- npm
- MySQL 8 / MariaDB
- Git
- Jika pakai Windows, Laragon sangat disarankan

### Ekstensi PHP yang wajib aktif
Pastikan ekstensi ini aktif di PHP Anda:

- pdo_mysql
- mbstring
- tokenizer
- xml
- ctype
- json
- curl
- fileinfo
- zip
- gd / imagick (untuk upload gambar, kalau ada fitur upload gambar)

Kalau pakai Laragon, buka menu PHP -> Extensions dan aktifkan yang relevan.

## Library / dependency yang dipakai project ini

Project ini sudah mencantumkan dependency di file `composer.json` dan `package.json`.

### PHP packages
- `laravel/framework`
- `laravel/tinker`
- `cloudinary/cloudinary_php`
- `phpoffice/phpword`
- `smalot/pdfparser`

### Frontend packages
- `tailwindcss`
- `vite`
- `laravel-vite-plugin`
- `alpinejs`
- `concurrently`
- `cropperjs`

Semua package di atas akan terinstall otomatis kalau Anda menjalankan `composer install` dan `npm install`.

## Setup cepat

1. Clone project

```bash
git clone <repo-url>
cd quoros
```

2. Install dependency PHP

```bash
composer install
```

3. Install dependency frontend

```bash
npm install
```

4. Copy file environment

```bash
copy .env.example .env
```

Kalau Linux / Mac:

```bash
cp .env.example .env
```

5. Atur database di `.env`

Edit file `.env` lalu cek bagian ini:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=quoros_db
DB_USERNAME=root
DB_PASSWORD=
```

Pastikan database MySQL sudah dibuat dulu, misalnya:

```sql
CREATE DATABASE quoros_db;
```

6. Generate app key

```bash
php artisan key:generate
```

7. Jalankan aplikasi

Untuk mode development:

```bash
php artisan serve
```

Lalu buka:

```text
http://localhost:8000
```

Kalau pakai Laragon, biasanya cukup buka folder project di web root dan aktifkan site-nya.

## Build frontend

Untuk produksi / build asset frontend:

```bash
npm run build
```

Untuk mode development frontend:

```bash
npm run dev
```

## Catatan penting

- `composer install` = install library backend PHP.
- `npm install` = install library frontend JS.
- `php artisan serve` = menjalankan aplikasi Laravel.
- `npm run build` = compile asset frontend untuk production.

## Optional konfigurasi tambahan

Project ini juga memakai fitur Cloudinary dan Discord. Kalau mau mengaktifkan fitur tersebut, isi variabel di `.env` sesuai token / akun Anda. Jika tidak perlu, Anda bisa biarkan value default untuk percobaan lokal.

## Troubleshooting singkat

### PHP extension tidak ditemukan
- Pastikan PHP 8.3 yang dipakai benar.
- Cek `php -m` untuk melihat ext yang aktif.

### Composer error
- Pastikan Composer sudah terinstall dan PATH-nya sudah benar.

### MySQL gagal terkoneksi
- Pastikan MySQL sudah berjalan.
- Pastikan database dan username/password di `.env` benar.

### Frontend tidak muncul / asset kosong
- Jalankan `npm install`
- Lalu `npm run build`

## License

Project ini memakai lisensi sesuai file yang ada di repository.
