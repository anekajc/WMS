# ajcwms - image untuk deploy di VPS (php-fpm + nginx dalam satu container, diatur supervisord).
# Pola sama dengan newSML/Dockerfile; bedanya ajcwms dilayani dari ROOT folder proyek (bukan
# public/) karena semua view memanggil aset dengan awalan "public/..." dan "img/..." - lihat
# docker/nginx.conf. Panduan: docs/deploy-docker.md
FROM php:8.3-fpm-bookworm

ENV DEBIAN_FRONTEND=noninteractive \
    COMPOSER_ALLOW_SUPERUSER=1 \
    # wkhtmltopdf (laravel-snappy) berjalan tanpa layar X
    QT_QPA_PLATFORM=offscreen

# Paket sistem: nginx, supervisor, library ekstensi PHP, wkhtmltopdf.
RUN apt-get update && apt-get install -y --no-install-recommends \
        nginx \
        supervisor \
        curl \
        gnupg \
        ca-certificates \
        unzip \
        git \
        libzip-dev \
        libpng-dev \
        libjpeg62-turbo-dev \
        libfreetype6-dev \
        libicu-dev \
        libonig-dev \
        unixodbc-dev \
        wkhtmltopdf \
        fonts-dejavu-core \
    && rm -rf /var/lib/apt/lists/*

# SQL Server lewat pdo_sqlsrv (Microsoft ODBC Driver 18) - driver yang SAMA dengan ajcwms di Windows,
# sehingga tipe data hasil query identik (angka dikembalikan sebagai string, mis. "1581.0", ".00").
# Seluruh kode ajcwms ditulis/diuji dengan perilaku itu (mis. pQC === '1'). PDO_DBLIB (FreeTDS, dipakai
# newSML) mengembalikan angka asli (1581, 0) sehingga perbandingan & tampilan bisa berubah - sengaja
# tidak dipakai di sini. Versi ekstensi dipatok supaya build ulang di VPS hasilnya sama.
RUN curl -fsSL https://packages.microsoft.com/keys/microsoft.asc | gpg --dearmor -o /usr/share/keyrings/microsoft-prod.gpg \
    && curl -fsSL https://packages.microsoft.com/config/debian/12/prod.list > /etc/apt/sources.list.d/mssql-release.list \
    && apt-get update \
    && ACCEPT_EULA=Y apt-get install -y --no-install-recommends msodbcsql18 \
    && apt-get install -y --no-install-recommends $PHPIZE_DEPS \
    && pecl install sqlsrv-5.12.0 pdo_sqlsrv-5.12.0 \
    && docker-php-ext-enable sqlsrv pdo_sqlsrv \
    && rm -rf /var/lib/apt/lists/* /tmp/pear

# SQL Server 36.88.190.218 memakai sertifikat/algoritma TLS lama ("legacy sigalg disallowed") yang
# ditolak OpenSSL 3 bawaan Debian 12 - ODBC 18 gagal login walau Encrypt=no. Hanya di container ini,
# TLS 1.0+ dan SECLEVEL=0 diizinkan supaya handshake login ke SQL Server itu berhasil
# (sudah diuji: konek OK, hasil query identik dengan Windows).
RUN sed -i 's/^\[openssl_init\]/[openssl_init]\nssl_conf = ssl_sect/' /etc/ssl/openssl.cnf \
    && printf '\n[ssl_sect]\nsystem_default = system_default_sect\n\n[system_default_sect]\nMinProtocol = TLSv1\nCipherString = DEFAULT@SECLEVEL=0\n' >> /etc/ssl/openssl.cnf

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
