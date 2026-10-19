# ajcwms - image untuk deploy di VPS (php-fpm + nginx dalam satu container, diatur supervisord).
# Pola sama dengan newSML/Dockerfile; bedanya ajcwms dilayani dari ROOT folder proyek (bukan
# public/) karena semua view memanggil aset dengan awalan "public/..." dan "img/..." - lihat
# docker/nginx.conf. Panduan: docs/deploy-docker.md
FROM php:8.3-fpm-bookworm

ENV DEBIAN_FRONTEND=noninteractive \
    COMPOSER_ALLOW_SUPERUSER=1 \
    # wkhtmltopdf (laravel-snappy) berjalan tanpa layar X
    QT_QPA_PLATFORM=offscreen

# Paket sistem: nginx, supervisor, library ekstensi PHP, FreeTDS (SQL Server), wkhtmltopdf.
RUN apt-get update && apt-get install -y --no-install-recommends \
        nginx \
        supervisor \
        curl \
        unzip \
        git \
        libzip-dev \
        libpng-dev \
        libjpeg62-turbo-dev \
        libfreetype6-dev \
        libicu-dev \
        libonig-dev \
        freetds-dev \
        freetds-bin \
        unixodbc-dev \
        wkhtmltopdf \
        fonts-dejavu-core \
    && rm -rf /var/lib/apt/lists/*

# SQL Server lewat PDO_DBLIB (FreeTDS). Driver 'sqlsrv' di config/database.php otomatis memakai
# DSN dblib bila ekstensi sqlsrv tidak ada (Illuminate\Database\Connectors\SqlServerConnector),
# sama seperti newSML. TDS 7.0 untuk kompatibilitas SQL Server lama.
RUN docker-php-ext-configure pdo_dblib \
    && docker-php-ext-install pdo_dblib \
    && printf "\n[global]\n\ttds version = 7.0\n" >> /etc/freetds/freetds.conf

# Ekstensi PHP lain: gd/zip (maatwebsite/excel - PhpSpreadsheet), intl, bcmath, dst.
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j"$(nproc)" \
        gd \
        zip \
        intl \
        mbstring \
        bcmath \
        exif \
        pcntl \
    && docker-php-ext-enable opcache

# Setelan PHP produksi. Halaman ajcwms ada yang berat (mis. Barang ±10.000 baris, stored
# procedure dashboard opname), jadi batas memori & waktu dinaikkan - samakan dengan
# fastcgi_read_timeout di docker/nginx.conf.
RUN cp "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini" \
    && { \
        echo 'memory_limit=512M'; \
        echo 'max_execution_time=300'; \
        echo 'upload_max_filesize=20M'; \
        echo 'post_max_size=20M'; \
        echo 'opcache.memory_consumption=128'; \
        echo 'opcache.interned_strings_buffer=16'; \
        echo 'opcache.max_accelerated_files=20000'; \
        echo 'opcache.validate_timestamps=0'; \
    } > "$PHP_INI_DIR/conf.d/zz-ajcwms.ini"

# laravel-snappy memanggil /usr/local/bin/wkhtmltopdf (config/snappy.php).
RUN ln -sf /usr/bin/wkhtmltopdf /usr/local/bin/wkhtmltopdf \
    && ln -sf /usr/bin/wkhtmltoimage /usr/local/bin/wkhtmltoimage

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Dependensi dulu (layer cache): hanya berubah bila composer.json/lock berubah.
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist --no-interaction

COPY . .

RUN composer dump-autoload --no-dev --optimize --classmap-authoritative \
    && composer check-platform-reqs --no-dev \
    && mkdir -p storage/app/public storage/framework/cache/data storage/framework/sessions \
                storage/framework/views storage/logs bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

COPY docker/nginx.conf /etc/nginx/nginx.conf
COPY docker/supervisord.conf /etc/supervisord.conf
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
# Buang CR (bila file ter-checkout dengan akhir baris Windows) supaya shebang tetap jalan di Linux.
RUN sed -i 's/\r$//' /usr/local/bin/entrypoint.sh && chmod +x /usr/local/bin/entrypoint.sh

EXPOSE 8080

# config/route/view cache dibuat saat container start (setelah .env tersedia), bukan saat build.
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisord.conf"]
