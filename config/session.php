<?php

use Illuminate\Support\Str;

/*
| Nilai disamakan dengan config/session.php versi Laravel 5.6 (driver file, ditutup saat
| browser ditutup, nama cookie "<app>_session", same_site null, serialisasi PHP) supaya
| perilaku login/logout user tidak berubah setelah upgrade ke Laravel 13.
*/

return [

    'driver' => env('SESSION_DRIVER', 'file'),

    'lifetime' => (int) env('SESSION_LIFETIME', 720),

    'expire_on_close' => true,

    'encrypt' => false,

    'files' => storage_path('framework/sessions'),

    'connection' => env('SESSION_CONNECTION'),

    'table' => env('SESSION_TABLE', 'sessions'),

    'store' => env('SESSION_STORE'),

    'lottery' => [2, 100],

    'cookie' => env(
        'SESSION_COOKIE',
        Str::slug((string) env('APP_NAME', 'laravel'), '_').'_session'
    ),

    'path' => '/',

    'domain' => env('SESSION_DOMAIN', null),

    'secure' => env('SESSION_SECURE_COOKIE', false),

    'http_only' => true,

    'same_site' => null,

    'partitioned' => false,

    'serialization' => 'php',

];
