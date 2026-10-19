<?php

/*
|--------------------------------------------------------------------------
| Router `php artisan serve` untuk ajcwms (khusus pengujian lokal, PHP 8.3)
|--------------------------------------------------------------------------
|
| Laravel otomatis memakai file ini (base_path('server.php')) saat `php artisan serve`.
| Di produksi ajcwms dipasang di root folder (Apache + index.php/.htaccess di root),
| sehingga semua view memanggil aset dengan awalan "public/..." dan "img/...". Bawaan
| `artisan serve` memakai folder public/ sebagai root, jadi aset itu 404. Router ini
| meniru Apache produksi: file statis di /public/ dan /img/ (relatif ke folder ajcwms)
| dikirim langsung, request lain diteruskan ke Laravel. Tidak berjalan di Apache.
|
| Cara pakai tetap: php artisan serve  ->  http://127.0.0.1:8000/login
*/

if (PHP_SAPI !== 'cli-server') {
    http_response_code(404);
    exit;
}

$root = __DIR__;
$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '');

// File statis. Hanya folder aset (bukan .env, app/, config/, dst).
$file = null;
if (preg_match('~^/(public|img)/~', $uri) && is_file($root.$uri)) {
    $file = $root.$uri;
} elseif ($uri !== '/' && is_file($root.'/public'.$uri)) {
    // Aset yang dipanggil tanpa awalan public/ (perilaku bawaan artisan serve).
    $file = $root.'/public'.$uri;
}

if ($file !== null && strtolower(pathinfo($file, PATHINFO_EXTENSION)) !== 'php') {
    $mime = [
        'css' => 'text/css', 'js' => 'application/javascript', 'map' => 'application/json',
        'json' => 'application/json', 'png' => 'image/png', 'jpg' => 'image/jpeg', 'jpeg' => 'image/jpeg',
        'gif' => 'image/gif', 'svg' => 'image/svg+xml', 'ico' => 'image/x-icon', 'webp' => 'image/webp',
        'woff' => 'font/woff', 'woff2' => 'font/woff2', 'ttf' => 'font/ttf', 'eot' => 'application/vnd.ms-fontobject',
        'otf' => 'font/otf', 'html' => 'text/html', 'txt' => 'text/plain', 'pdf' => 'application/pdf',
    ][strtolower(pathinfo($file, PATHINFO_EXTENSION))] ?? (mime_content_type($file) ?: 'application/octet-stream');
    header('Content-Type: '.$mime);
    header('Content-Length: '.filesize($file));
    readfile($file);
    return true;
}

// Samakan dengan Apache: aplikasi berjalan lewat /index.php di root folder.
$_SERVER['SCRIPT_NAME'] = '/index.php';
$_SERVER['PHP_SELF'] = '/index.php';
$_SERVER['SCRIPT_FILENAME'] = $root.'/index.php';

file_put_contents('php://stdout', '['.date('D M j H:i:s Y').'] '.$_SERVER['REQUEST_METHOD'].' '.$uri."\n");

require $root.'/public/index.php';
