# Deploy Quoros ke Vercel + Database TiDB Cloud

Dokumen ini menjelaskan langkah demi langkah memindahkan Quoros dari shared hosting
ke **Vercel** (container/FrankenPHP) dengan database di **TiDB Cloud**, serta
penyimpanan file di **Cloudinary**.

---

## 1. Ringkasan arsitektur

| Komponen | Sebelumnya | Sesudah |
|---|---|---|
| Web server | Apache (shared hosting) | Vercel Container + FrankenPHP (Caddy) |
| PHP | versi hosting | PHP 8.4 (image `dunglas/frankenphp`) |
| Database | MySQL host | TiDB Cloud Serverless (protokol MySQL, port **4000**, TLS wajib) |
| Gambar (cover/karakter/profil) | Cloudinary | Cloudinary (tidak berubah) |
| File chapter (PDF/EPUB/DOCX) | disk lokal `storage/app/public` | Cloudinary (`resource_type=raw`) |
| Session & cache | file | tabel di TiDB (`database`) |
| Queue | sync | sync (tidak ada worker) |
| Cron | tidak ada | tidak ada — pakai `publishScheduledDue()` saat request |

**Konsekuensi penting dari Vercel:** container bersifat *stateless*. Setiap request
bisa ditangani instance berbeda, dan filesystem hanya sementara. Karena itu **tidak
boleh ada data yang ditulis ke disk lokal** — semua session, cache, dan file
harus lewat TiDB atau Cloudinary.

---

## 2. Prasyarat

1. **Akun Vercel** (gratis) — https://vercel.com
2. **Docker Desktop** terpasang dan berjalan di komputer (Vercel build image
   container dari Dockerfile, dan `vercel dev` juga butuh Docker).
3. **Vercel CLI**:
   ```powershell
   npm install -g vercel
   ```
4. **Akun TiDB Cloud** — https://tidbcloud.com
5. **Node.js 22** dan **PHP 8.3+ / Composer 2** di lokal (untuk `npm run build`,
   migrasi, dan dump database).
6. Kredensial **Cloudinary** yang sudah dipakai sekarang.

> Catatan: Vercel Services (container runtime) dapat memerlukan paket berbayar.
> Cek halaman *Vercel Services* di dashboard akunmu sebelum mulai. Jika tidak
> tersedia, jalur alternatifnya adalah runtime komunitas `vercel-php`, tetapi
> konfigurasi di repo ini disiapkan untuk jalur container.

---

## 3. File yang sudah disiapkan di repo ini

| File | Fungsi |
|---|---|
| [Dockerfile.vercel](file:///c:/laragon/www/quoros/Dockerfile.vercel) | Recipe image: PHP + ekstensi, build aset Vite, `composer install --no-dev`, `storage:link` |
| [Caddyfile](file:///c:/laragon/www/quoros/Caddyfile) | Doc root ke `public/`, semua route ke `index.php`, ikut port `$PORT` Vercel |
| [vercel.json](file:///c:/laragon/www/quoros/vercel.json) | Mendeklarasikan service container `app` + rewrite semua request ke sana |
| [.dockerignore](file:///c:/laragon/www/quoros/.dockerignore) | Mengecilkan konteks build & memastikan `.env` tidak ikut ke image |
| [.vercelignore](file:///c:/laragon/www/quoros/.vercelignore) | Mengecilkan yang diunggah ke Vercel |
| [CloudinaryService.php](file:///c:/laragon/www/quoros/app/Services/CloudinaryService.php) | Tambahan `uploadChapterFile()` & `deleteChapterFile()` (resource `raw`) |
| [ChapterController.php](file:///c:/laragon/www/quoros/app/Http/Controllers/ChapterController.php) | Upload/hapus file chapter lewat Cloudinary, bukan disk lokal |
| [storage/app/public/.gitignore](file:///c:/laragon/www/quoros/storage/app/public/.gitignore) | Mengecualikan `logo/` dan `banners/` supaya aset statis ini ikut ter-commit (kalau tidak, deploy lewat Git akan kehilangan logo) |

**Penting:** `.env.production` yang berisi kredensial InfinityFree **tidak dipakai**
di Vercel dan tidak ikut terunggah (dikecualikan `.dockerignore`/`.vercelignore`).
Konfigurasi Vercel murni berasal dari *Environment Variables* di dashboard.

---

## 4. Langkah A — Siapkan TiDB Cloud

1. Login ke https://tidbcloud.com → **Create Cluster** → pilih tipe
   **Starter** / **Serverless** (tersedia paket gratis).
2. Pilih region **terdekat dengan pengguna**, misal `ap-southeast-1` (Singapore).
3. Tunggu cluster berstatus **Active**, lalu klik **Connect**.
   Pilih tipe koneksi **MySQL client / Laravel / General**, dan catat:
   - `Host` → contoh `gateway01.ap-southeast-1.prod.aws.tidbcloud.com`
   - `Port` → **4000**
   - `User` → contoh `3Yk9aBcD.root`
   - `Password` → klik *Generate password* lalu simpan
4. Buat database aplikasi. Cara termudah lewat **SQL Editor** di dashboard:
   ```sql
   CREATE DATABASE IF NOT EXISTS quoros
     CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
   ```
5. **TLS wajib.** TiDB Cloud menolak koneksi tanpa TLS.
   - Sertifikat TiDB Cloud diterbitkan oleh **Let's Encrypt (ISRG Root X1)**,
     dan sudah tersedia di image FrankenPHP (Debian) pada path
     `/etc/ssl/certs/ca-certificates.crt`.
   - Jadi cukup set env `MYSQL_ATTR_SSL_CA=/etc/ssl/certs/ca-certificates.crt`
     (dibaca oleh [config/database.php](file:///c:/laragon/www/quoros/config/database.php#L62-L64)).
   - *Jika* nanti muncul error SSL, unduh CA dari halaman **Connect** TiDB Cloud
     dan lihat bagian Troubleshooting di dokumen ini.

---

## 5. Langkah B — Migrasi skema & pindahkan data

### B.1 Jalankan migrasi ke TiDB

Laravel memuat file `.env.{APP_ENV}` jika ada, jadi kita tidak perlu mengubah
`.env` lokal. Buat file **`.env.tidb`** di root proyek (khusus lokal, tidak
diunggah ke Vercel):

```dotenv
APP_NAME=quoros
APP_ENV=production
APP_KEY=base64:ISI_DENGAN_APP_KEY_PRODUKSI
APP_DEBUG=false
APP_URL=https://quoros.xo.je

DB_CONNECTION=mysql
DB_HOST=gateway01.ap-southeast-1.prod.aws.tidbcloud.com
DB_PORT=4000
DB_DATABASE=quoros
DB_USERNAME=3Yk9aBcD.root
DB_PASSWORD=password_tidb_kamu
MYSQL_ATTR_SSL_CA=C:\laragon\etc\ssl\isrgrootx1.pem

SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=sync
LOG_CHANNEL=stack

CLOUDINARY_CLOUD_NAME=dwndwprdr
CLOUDINARY_KEY=648897635468459
CLOUDINARY_SECRET=dLq8zl-DGWSQ27i1zuhbHjZXtjc
CLOUDINARY_SECURE=true
```

Dua catatan soal `MYSQL_ATTR_SSL_CA` di lokal:
- Di Windows, PHP butuh file CA dalam bentuk `.pem`. Kalau tidak punya,
  unduh dari halaman **Connect** TiDB Cloud (biasanya `isrgrootx1.pem`),
  atau biarkan kosong dulu untuk sekadar mencoba.
- Di server Vercel, path-nya **berbeda** (`/etc/ssl/certs/ca-certificates.crt`).

Lalu jalankan:

```powershell
php artisan migrate --env=tidb --force
```

### B.2 Pindahkan data lama (opsional)

Jika ingin membawa isi database lokal:

```powershell
# 1. Dump isi tabel lokal TANPA struktur (struktur sudah dibuat oleh migrate)
mysqldump -h 127.0.0.1 -P 3306 -u root quoros_db `
  --no-create-info --skip-triggers --single-transaction `
  --set-gtid-purged=OFF --skip-comments > data.sql
```

> Jika perintah `mysqldump` error karena opsi tidak dikenal (mis. versi MariaDB),
> hapus opsi yang bermasalah (`--column-statistics=0` hanya ada di MySQL 8 client).

```powershell
# 2. Import ke TiDB Cloud (butuh mysql client 8+)
mysql --ssl-mode=VERIFY_IDENTITY `
  -h gateway01.ap-southeast-1.prod.aws.tidbcloud.com -P 4000 `
  -u 3Yk9aBcD.root -p quoros < data.sql
```

Kalau `--ssl-mode=VERIFY_IDENTITY` gagal, tambahkan `--ssl-ca=isrgrootx1.pem`,
atau sementara pakai `--ssl-mode=REQUIRED`.

**Jangan** mengimpor tabel `sessions`, `cache`, dan `jobs` dari lokal — biarkan
kosong agar tidak ada session lama yang menggantung.

### B.3 Perhatikan data `file_path` chapter lama

Chapter lama menyimpan path relatif (mis. `chapters/bab-1.pdf`) yang menunjuk ke
disk hosting lama. File fisiknya **tidak ikut pindah**. Di halaman baca, tombol
unduh sudah menangani dua format (URL Cloudinary penuh maupun path lokal), tetapi
file lama tetap 404 sampai diunggah ulang ke Cloudinary lewat
*Chapter → Edit → ganti file*.

---

## 6. Langkah C — Set Environment Variables di Vercel

### Cara 1 — Dashboard

Vercel → Project → **Settings → Environment Variables**.
Tambahkan satu per satu, centang environment **Production** (dan **Preview** bila mau).

### Cara 2 — CLI

```powershell
vercel env add APP_KEY production
vercel env add DB_HOST production
# ...ulangi untuk setiap variabel
```

### Daftar variabel

| Variable | Nilai | Keterangan |
|---|---|---|
| `APP_NAME` | `quoros` | |
| `APP_ENV` | `production` | |
| `APP_KEY` | `base64:...` | Buat dengan `php artisan key:generate --show`. **Simpan stabil** — kalau berubah, semua cookie/session lama batal. |
| `APP_DEBUG` | `false` | **Wajib false.** |
| `APP_URL` | `https://quoros.xo.je` | Ganti dengan domain Vercel kalau belum pakai domain custom. |
| `LOG_CHANNEL` | `stderr` | Supaya log muncul di *Vercel Runtime Logs*. |
| `LOG_LEVEL` | `error` | |
| `DB_CONNECTION` | `mysql` | |
| `DB_HOST` | `gateway01.<region>.prod.aws.tidbcloud.com` | Dari Langkah A. |
| `DB_PORT` | `4000` | **Bukan 3306.** |
| `DB_DATABASE` | `quoros` | |
| `DB_USERNAME` | `<user>.root` | |
| `DB_PASSWORD` | `<password>` | |
| `MYSQL_ATTR_SSL_CA` | `/etc/ssl/certs/ca-certificates.crt` | Path CA di dalam container Debian. |
| `SESSION_DRIVER` | `database` | Wajib: `array`/`file` tidak bisa dipakai lintas instance. |
| `CACHE_STORE` | `database` | Sama seperti di atas. |
| `QUEUE_CONNECTION` | `sync` | Tidak ada worker di Vercel. |
| `FILESYSTEM_DISK` | `local` | Tidak ada upload yang memakai disk default. |
| `SESSION_SECURE_COOKIE` | `true` | Opsional, memperketat cookie. |
| `CLOUDINARY_CLOUD_NAME` | `dwndwprdr` | |
| `CLOUDINARY_KEY` | `648897635468459` | |
| `CLOUDINARY_SECRET` | `...` | |
| `CLOUDINARY_SECURE` | `true` | |
| `CLOUDINARY_URL` | `cloudinary://key:secret@cloud` | Opsional, dipakai SDK sebagai fallback. |

> Jangan set `APP_CONFIG_CACHE`/`APP_ROUTES_CACHE` ke path di luar `/app` —
> biarkan Laravel pakai default.

---

## 7. Langkah D — Login & hubungkan project

```powershell
vercel login
vercel link
```

`vercel link` akan membuat folder `.vercel/` (berisi `project.json`). Folder ini
sudah masuk `.dockerignore` dan `.vercelignore`, jadi tidak ikut ke image.

---

## 8. Langkah E — Uji lokal (opsional tapi disarankan)

Docker Desktop harus berjalan.

```powershell
set -a; . .\.env.tidb; set +a     # PowerShell: lihat catatan di bawah
vercel dev -L --listen 8080
```

Di **PowerShell**, `set -a; source ...` bukan sintaks yang valid. Gunakan:

```powershell
Get-Content .env.tidb | ForEach-Object {
  if ($_ -match '^\s*([A-Z_]+)\s*=\s*(.*)$') {
    [System.Environment]::SetEnvironmentVariable($matches[1], $matches[2])
  }
}
vercel dev -L --listen 8080
```

Uji:
```powershell
curl http://localhost:8080/up
```

---

## 9. Langkah F — Deploy

```powershell
vercel deploy --prod
```

Tanpa `--prod` akan menghasilkan Preview Deployment (bagus untuk uji coba).
Setelah selesai, Vercel memberi URL, misal `https://quoros-xxxx.vercel.app`.

Jika memakai Git: import repo di dashboard Vercel. Pastikan `Dockerfile.vercel`,
`Caddyfile`, dan `vercel.json` berada di root project.

---

## 10. Langkah G — Verifikasi setelah deploy

1. **Health check**
   ```powershell
   curl https://<domain-vercel>/up
   ```
   `bootstrap/app.php` mendaftarkan `/up` sebagai health route Laravel.
2. **Halaman utama** terbuka dan logo tampil
   (logo diambil dari `storage/app/public/logo`, disajikan lewat symlink
   `public/storage` yang dibuat di Dockerfile).
3. **Login** berhasil dan tetap login saat refresh → artinya
   `SESSION_DRIVER=database` bekerja.
4. **Upload cover/karakter** → cek file muncul di Cloudinary.
5. **Upload file chapter** → cek file muncul di Cloudinary pada folder
   `chapters/` dengan tipe `raw`, lalu tombol *Unduh File Chapter* membuka URL
   `res.cloudinary.com`.
6. **Log runtime**
   ```powershell
   vercel logs https://<domain-vercel>
   ```
7. **Domain custom** (opsional): Settings → Domains → tambahkan
   `quoros.xo.je`, lalu ubah `APP_URL` dan redeploy.

---

## 11. Batasan Vercel yang harus diketahui

| Batasan | Dampak pada Quoros |
|---|---|
| **Body request maksimal 4,5 MB** | Upload file chapter > ~4,5 MB akan gagal sebelum sampai ke Laravel. Fitur **bulk upload** (`max:51200` = 50MB) praktis tidak bisa dipakai lewat Vercel. Kalau perlu, upload langsung ke Cloudinary dari browser memakai *unsigned upload preset*. |
| **Filesystem tidak persisten** | Semua file harus ke Cloudinary. Jangan tambahkan `->store()` baru ke disk. `storage/framework/views` hanya untuk cache Blade sementara. |
| **Tidak ada cron & queue worker** | `QUEUE_CONNECTION=sync` (job jalan saat request). Publikasi chapter terjadwal sudah ditangani `Chapter::publishScheduledDue()` yang dipanggil saat halaman dibuka. |
| **Instance tidak selalu sama** | Session/cache wajib di TiDB. Jangan pakai driver `file` atau `array`. |
| **`config:cache` tidak dijalankan saat build** | Nilai env dibaca saat runtime. Ini memang yang diinginkan karena Vercel menyuntikkan env saat container start. |
| **Koneksi TiDB terbatas** | Setiap request membuka koneksi ke TiDB. Kalau traffic naik dan muncul error "too many connections", pertimbangkan TiDB *connection pooling* atau naikkan limit cluster. |

---

## 12. Troubleshooting

**HTTP 502 / container gagal start**
Cek `vercel logs`. Pastikan `Caddyfile` tetap memakai `:{$PORT:80}` — Vercel
menentukan port lewat env `PORT`.

**"No application encryption key has been set"**
`APP_KEY` belum ada di environment Vercel. Tambahkan lewat
`vercel env add APP_KEY`, lalu **redeploy** (env baru hanya terbaca oleh
deployment baru).

**Error SSL saat konek ke TiDB**
1. Pastikan `MYSQL_ATTR_SSL_CA=/etc/ssl/certs/ca-certificates.crt` sudah di-set.
2. Pastikan `DB_PORT=4000`, bukan 3306.
3. Kalau tetap gagal, unduh CA dari halaman **Connect** TiDB Cloud, taruh di repo
   (mis. `certs/tidb-ca.pem`), lalu tambahkan baris berikut di
   `Dockerfile.vercel` (stage `runtime`, sebelum `USER www-data`):
   ```dockerfile
   COPY --chown=www-data:www-data certs/tidb-ca.pem /etc/ssl/certs/tidb-ca.pem
   ```
   dan ubah env menjadi `MYSQL_ATTR_SSL_CA=/etc/ssl/certs/tidb-ca.pem`.

**"SQLSTATE[HY000] [1045] Access denied"**
Username TiDB harus lengkap dengan prefix, mis. `3Yk9aBcD.root`, bukan hanya
`root`.

**Session hilang / selalu logout**
`SESSION_DRIVER` masih `file` atau `array`. Set ke `database` di Vercel.

**Gambar 404 (`/storage/logo/quorosLogo.png`)**
Symlink `public/storage` belum terbentuk. Pastikan `php artisan storage:link`
ada di stage `dependencies` pada `Dockerfile.vercel` dan folder
`storage/app/public` tidak dikecualikan oleh `.dockerignore`.

**Aset CSS/JS tidak termuat**
Manifest Vite tidak ada. Pastikan `COPY --from=assets /app/public/build
./public/build` ada di stage `dependencies`.

**Upload chapter gagal dengan "Upload file chapter gagal ke Cloudinary"**
Cek `CLOUDINARY_CLOUD_NAME`, `CLOUDINARY_KEY`, `CLOUDINARY_SECRET` di env Vercel.
Cloudinary akun gratis membatasi ukuran file `raw` — file yang sangat besar bisa
ditolak.

---

## 13. Alur deploy berikutnya (setelah setup awal)

```powershell
vercel deploy --prod
```

Atau push ke branch yang terhubung dengan project Vercel. **Tidak perlu** upload
`vendor/` lagi — `composer install --no-dev` sudah dijalankan di dalam image.
Jika mengubah `composer.json`/`composer.lock` atau `package.json`, cukup deploy
ulang; Docker akan rebuild layer terkait.
