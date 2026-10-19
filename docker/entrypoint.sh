#!/bin/sh
# Dijalankan setiap container start, sebelum supervisord (php-fpm + nginx).
set -e

cd /var/www/html

if [ ! -f .env ]; then
    echo "[entrypoint] PERINGATAN: /var/www/html/.env tidak ada - mount file .env (lihat docs/deploy-docker.md)." >&2
fi

# Folder storage bisa berasal dari volume kosong - pastikan strukturnya ada & bisa ditulis.
mkdir -p storage/app/public storage/framework/cache/data storage/framework/sessions \
         storage/framework/views storage/logs bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

# Cache dibuat ulang dari .env yang sedang dipakai (bukan dari saat build image).
php artisan config:clear >/dev/null 2>&1 || true
php artisan config:cache
php artisan route:cache
php artisan view:cache

exec "$@"
