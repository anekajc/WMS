<?php

/*
| Disamakan dengan Laravel 5.6:
| - bcrypt rounds 10 (bawaan Laravel 13 adalah 12);
| - verify false: hash yang bukan bcrypt cukup dianggap password salah (Laravel 13
|   bawaannya melempar error);
| - rehash_on_login false: password tidak di-hash ulang otomatis saat login.
*/

return [

    'driver' => 'bcrypt',

    'bcrypt' => [
        'rounds' => env('BCRYPT_ROUNDS', 10),
        'verify' => false,
        'limit' => null,
    ],

    'argon' => [
        'memory' => 1024,
        'threads' => 2,
        'time' => 2,
        'verify' => false,
    ],

    'rehash_on_login' => false,

];
