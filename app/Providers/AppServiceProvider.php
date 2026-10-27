<?php

namespace App\Providers;

use App\Database\LegacySqlServerConnection;
use Illuminate\Database\Connection;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        Connection::resolverFor('sqlsrv', function ($connection, $database, $prefix, $config) {
            return new LegacySqlServerConnection($connection, $database, $prefix, $config);
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Ditambahkan 2026-10-27 (kecepatan): nama koneksi lama SML, SPLSIG, SPL, sqlite menunjuk ke
        // server & database yang sama dengan 'sqlsrv', tetapi dulu masing-masing membuka koneksi
        // (login + TLS) sendiri, jadi satu halaman bisa login 3-5 kali ke SQL Server. Sekarang
        // memakai satu koneksi PDO yang sama (dibuka saat pertama dipakai). Hanya berlaku bila
        // host/port/database/username sama persis dengan 'sqlsrv'; kalau berbeda, tetap koneksi sendiri.
        $db = $this->app['db'];
        $kunci = fn (array $c) => [$c['driver'] ?? null, $c['host'] ?? null, $c['port'] ?? null, $c['database'] ?? null, $c['username'] ?? null];
        $utama = config('database.connections.sqlsrv');

        foreach (['SML', 'SPLSIG', 'SPL', 'sqlite'] as $nama) {
            $config = config("database.connections.$nama");
            if (! $utama || ! $config || $kunci($config) !== $kunci($utama)) {
                continue;
            }

            $db->extend($nama, function (array $config, string $nama) use ($db) {
                // Sama dengan ConnectionFactory: nama koneksi disimpan di config (dipakai getName()).
                $config += ['prefix' => '', 'name' => $nama];

                return new LegacySqlServerConnection(
                    fn () => $db->connection('sqlsrv')->getPdo(),
                    $config['database'],
                    $config['prefix'] ?? '',
                    $config
                );
            });
        }
    }
}
