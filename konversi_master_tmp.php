<?php
// Skrip sementara (hapus setelah dipakai): kartu halaman master (card-header kosong berisi tab yang
// sudah dikomentari) -> card wms-kartu; card-header lama dibungkus komentar Blade.
foreach (array_slice($argv, 1) as $f) {
    $path = __DIR__ . '/resources/views/' . $f . '.blade.php';
    $src = file_get_contents($path);
    $baru = preg_replace('~<div class="card">(\s*)(<div class="card-header">\s*<div class="row">\s*<!--.*?-->\s*</div>\s*</div>)~s',
        "<div class=\"card wms-kartu\">$1{{-- card-header lama (isi tab-nya sudah dikomentari) tidak ditampilkan:\n    $2 --}}", $src, 1, $n);
    echo "$f: $n\n";
    if ($n === 1 && strlen($baru) > strlen($src)) { file_put_contents($path, $baru); }
}
