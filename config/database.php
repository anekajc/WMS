<?php

// Ditambahkan 2026-10-19 (deploy Docker/Linux): di Linux driver 'sqlsrv' memakai PDO_DBLIB (FreeTDS)
// karena ekstensi sqlsrv tidak ada. PDO_DBLIB memasang batas waktu query bawaan 30 detik, sehingga
// stored procedure berat (mis. sp_dashboardopnameharian) gagal "Adaptive Server connection timed out".
// Batasnya dinaikkan ke DB_QUERY_TIMEOUT (bawaan 300 detik = max_execution_time di Docker). Hanya
// dipasang bila driver yang dipakai memang dblib - di Windows (sqlsrv) tidak ada opsi tambahan.
// 1001 = PDO::DBLIB_ATTR_QUERY_TIMEOUT (ditulis angka supaya file ini tetap aman tanpa ekstensi dblib).
$opsiDblib = (! in_array('sqlsrv', \PDO::getAvailableDrivers(), true) && in_array('dblib', \PDO::getAvailableDrivers(), true))
    ? [1001 => (int) env('DB_QUERY_TIMEOUT', 300)]
    : [];

return [

    /*
    |--------------------------------------------------------------------------
    | Default Database Connection Name
    |--------------------------------------------------------------------------
    |
    | Here you may specify which of the database connections below you wish
    | to use as your default connection for all database work. Of course
    | you may use many connections at once using the Database library.
    |
    */

    // 'default' => env('DB_CONNECTION', 'mysql'),
    'default' => env('DB_CONNECTION', 'sqlsrv'),

    /*
    |--------------------------------------------------------------------------
    | Database Connections
    |--------------------------------------------------------------------------
    |
    | Here are each of the database connections setup for your application.
    | Of course, examples of configuring each database platform that is
    | supported by Laravel is shown below to make development simple.
    |
    |
    | All database work in Laravel is done through the PHP PDO facilities
    | so make sure you have the driver for your particular database of
    | choice installed on your machine before you begin development.
    |
    */

    'connections' => [

        /*
        | Semua koneksi ajcwms menunjuk ke database yang sama (DBSMLBARU), sama seperti
        | newSML. Nama koneksi lama (sqlsrv, sqlite, SPL, SML, SPLSIG) dipertahankan karena
        | dipakai model & DB::connection() di controller. Nilai diambil dari .env.
        */

        'sqlsrv' => [
            'driver' => 'sqlsrv',
            'url' => env('DB_URL'),
            'host' => env('DB_HOST', '36.88.190.218'),
            'port' => env('DB_PORT', '1433'),
            'database' => env('DB_DATABASE', 'DBSMLBARU'),
            'username' => env('DB_USERNAME', 'sa'),
            'password' => env('DB_PASSWORD', 'Anekajc1a9'),
            'charset' => env('DB_CHARSET', 'utf8'),
            'prefix' => '',
            'prefix_indexes' => true,
            'encrypt' => env('DB_ENCRYPT', 'no'),
            'trust_server_certificate' => env('DB_TRUST_SERVER_CERTIFICATE', 'true'),
            'options' => $opsiDblib,
        ],

        'sqlite' => [
            'driver' => 'sqlsrv',
            'url' => env('DB_URL'),
            'host' => env('DB_HOST', '36.88.190.218'),
            'port' => env('DB_PORT', '1433'),
            'database' => env('DB_DATABASE', 'DBSMLBARU'),
            'username' => env('DB_USERNAME', 'sa'),
            'password' => env('DB_PASSWORD', 'Anekajc1a9'),
            'charset' => env('DB_CHARSET', 'utf8'),
            'prefix' => '',
            'prefix_indexes' => true,
            'encrypt' => env('DB_ENCRYPT', 'no'),
            'trust_server_certificate' => env('DB_TRUST_SERVER_CERTIFICATE', 'true'),
            'options' => $opsiDblib,
        ],

        'SPL' => [
            'driver' => 'sqlsrv',
            'url' => env('DB_URL'),
            'host' => env('DB_HOST', '36.88.190.218'),
            'port' => env('DB_PORT', '1433'),
            'database' => env('DB_DATABASE', 'DBSMLBARU'),
            'username' => env('DB_USERNAME', 'sa'),
            'password' => env('DB_PASSWORD', 'Anekajc1a9'),
            'charset' => env('DB_CHARSET', 'utf8'),
            'prefix' => '',
            'prefix_indexes' => true,
            'encrypt' => env('DB_ENCRYPT', 'no'),
            'trust_server_certificate' => env('DB_TRUST_SERVER_CERTIFICATE', 'true'),
            'options' => $opsiDblib,
        ],

        'SML' => [
            'driver' => 'sqlsrv',
            'url' => env('DB_URL'),
            'host' => env('DB_HOST', '36.88.190.218'),
            'port' => env('DB_PORT', '1433'),
            'database' => env('DB_DATABASE', 'DBSMLBARU'),
            'username' => env('DB_USERNAME', 'sa'),
            'password' => env('DB_PASSWORD', 'Anekajc1a9'),
            'charset' => env('DB_CHARSET', 'utf8'),
            'prefix' => '',
            'prefix_indexes' => true,
            'encrypt' => env('DB_ENCRYPT', 'no'),
            'trust_server_certificate' => env('DB_TRUST_SERVER_CERTIFICATE', 'true'),
            'options' => $opsiDblib,
        ],

        'SPLSIG' => [
            'driver' => 'sqlsrv',
            'url' => env('DB_URL'),
            'host' => env('DB_HOST', '36.88.190.218'),
            'port' => env('DB_PORT', '1433'),
            'database' => env('DB_DATABASE', 'DBSMLBARU'),
            'username' => env('DB_USERNAME', 'sa'),
            'password' => env('DB_PASSWORD', 'Anekajc1a9'),
            'charset' => env('DB_CHARSET', 'utf8'),
            'prefix' => '',
            'prefix_indexes' => true,
            'encrypt' => env('DB_ENCRYPT', 'no'),
            'trust_server_certificate' => env('DB_TRUST_SERVER_CERTIFICATE', 'true'),
            'options' => $opsiDblib,
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Migration Repository Table
    |--------------------------------------------------------------------------
    |
    | This table keeps track of all the migrations that have already run for
    | your application. Using this information, we can determine which of
    | the migrations on disk haven't actually been run in the database.
    |
    */

    'migrations' => [
        'table' => 'migrations',
        'update_date_on_publish' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Redis Databases
    |--------------------------------------------------------------------------
    |
    | Redis is an open source, fast, and advanced key-value store that also
    | provides a richer set of commands than a typical key-value systems
    | such as APC or Memcached. Laravel makes it easy to dig right in.
    |
    */

    'redis' => [

        'client' => env('REDIS_CLIENT', 'phpredis'),

        'default' => [
            'host' => env('REDIS_HOST', '127.0.0.1'),
            'password' => env('REDIS_PASSWORD', null),
            'port' => env('REDIS_PORT', 6379),
            'database' => 0,
        ],

    ],

];
