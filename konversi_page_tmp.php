<?php
// Skrip sementara (hapus setelah dipakai): di halaman master, sesudah setiap handler
// table.on('order', ...) ditambah handler yang sama untuk event 'page' (pindah halaman tabel),
// supaya sorotan baris + pilihan ikut direset - sama seperti saat cari/urut. Kode lama tidak diubah.
foreach (array_slice($argv, 1) as $f) {
    $path = __DIR__ . '/resources/views/' . $f . '.blade.php';
    $src = file_get_contents($path);
    $baru = preg_replace_callback("~( *)table\.on\('order', function \(\) \{(.*?)\} \);~s", function ($m) {
        return $m[0] . "\n" . $m[1] . "// Ditambahkan 2026-10-19: tabel sekarang berhalaman - pindah halaman juga mereset pilihan baris.\n"
            . $m[1] . "table.on('page', function () {" . $m[2] . "} );";
    }, $src, -1, $n);
    echo "$f: $n\n";
    if ($n > 0 && strlen($baru) > strlen($src)) { file_put_contents($path, $baru); }
}
