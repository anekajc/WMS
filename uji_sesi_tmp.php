<?php
// Skrip uji lokal sementara: buat sesi login user 'sa' dan cetak nama + nilai cookie-nya.
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$u = App\Model\User::where('username', 'sa')->first();
$store = $app['session']->driver();
$store->start();
$store->put('login_web_' . sha1(Illuminate\Auth\SessionGuard::class), $u->getAuthIdentifier());
$store->put('_token', Illuminate\Support\Str::random(40));
$store->save();

$name = config('session.cookie');
$val = $app['encrypter']->encrypt(
    Illuminate\Cookie\CookieValuePrefix::create($name, $app['encrypter']->getKey()) . $store->getId(),
    false
);
echo json_encode(['name' => $name, 'value' => $val]);
