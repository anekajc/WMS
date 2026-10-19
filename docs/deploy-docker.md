# Deploy ajcwms dengan Docker di VPS

Image berisi PHP 8.3-FPM + nginx (diatur supervisord), driver SQL Server lewat FreeTDS
(`pdo_dblib`, sama dengan newSML), ekstensi gd/zip/intl untuk export Excel, dan wkhtmltopdf
untuk cetak PDF. Container mendengarkan port **8080**.

ajcwms dilayani dari **root folder proyek** (seperti Apache lama: `index.php` di root), karena
semua view memanggil aset `public/...` dan `img/...`. nginx hanya melayani file di `public/`
dan `img/`; `.env`, `app/`, `storage/`, `vendor/` tidak bisa diunduh.

## File

| File | Isi |
|---|---|
| `Dockerfile` | build image |
| `docker/nginx.conf` | web server (root proyek, hanya `index.php` yang dijalankan) |
| `docker/supervisord.conf` | menjalankan php-fpm + nginx |
| `docker/entrypoint.sh` | tiap start: siapkan `storage/`, lalu `config:cache`, `route:cache`, `view:cache` dari env yang aktif |
| `compose.yaml` | jalankan di VPS dengan docker compose |
| `.dockerignore` | `.env`, `vendor/`, `.git`, log, sesi tidak ikut image |

## Variabel lingkungan (.env) minimal

```
APP_NAME=ajcwms
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:...            # sama dengan .env lama supaya sesi/enkripsi tetap valid
APP_URL=https://domain-ajcwms

DB_CONNECTION=sqlsrv
DB_HOST=36.88.190.218
DB_PORT=1433
DB_DATABASE=DBSMLBARU
DB_USERNAME=...
DB_PASSWORD=...

SESSION_DRIVER=file
SESSION_LIFETIME=30
```

Config di-cache saat container **start**, jadi setiap mengubah env cukup restart container
(tidak perlu build ulang).

## Cara A - platform deploy (Coolify / Dokploy / sejenis, seperti newSML)

1. Buat aplikasi dari repo GitHub ajcwms, build pack **Dockerfile**.
2. Port aplikasi: **8080**.
3. Isi environment variables seperti di atas (tidak perlu file `.env`).
4. Tambah persistent storage: path container `/var/www/html/storage`
   (supaya sesi login dan log tidak hilang saat redeploy).
5. Deploy.

## Cara B - docker compose langsung di VPS

```bash
git clone <repo-ajcwms> ajcwms && cd ajcwms
cp .env.example .env    # lalu isi sesuai bagian di atas
docker compose up -d --build
```

Aplikasi tersedia di `http://IP-VPS:8081` (ubah port kiri di `compose.yaml` bila bentrok).
Untuk HTTPS pasang reverse proxy (nginx/Caddy/Traefik) ke port tersebut - `trustProxies('*')`
sudah aktif di `bootstrap/app.php`, jadi URL https dikenali.

Update versi:

```bash
git pull && docker compose up -d --build
```

Log aplikasi: `docker compose logs -f ajcwms` (nginx/php) dan volume `ajcwms-storage`
(`storage/logs/laravel.log`).

## Catatan

- Koneksi ke SQL Server memakai TDS 7.0 (`/etc/freetds/freetds.conf`), sama dengan newSML.
- Batas PHP: memory 512M, waktu 300 detik (halaman berat seperti Barang/Dashboard Opname).
- Jangan jalankan `php artisan config:cache` di repo lokal lalu commit `bootstrap/cache/*.php`;
  cache selalu dibuat di container.
