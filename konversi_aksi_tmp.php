<?php
// Skrip sementara (hapus setelah dipakai): tambahkan po-aksi-hover ke tabel utama berkelas "data-table"
// yang kolom pertamanya Actions (bukan halaman scanner). Bagian utama = sebelum modal pertama.
$tulis = in_array('--tulis', $argv, true);
$files = array_values(array_filter(array_slice($argv, 1), fn ($a) => $a !== '--tulis'));
$scanner = ['newputaway', 'newpicking', 'ubahkemasanpicking', 'newopnamegudang', 'transferlokasisite', 'opnamegdg', 'opnamegdginspeksi'];

foreach ($files as $f) {
    $path = __DIR__ . '/resources/views/' . $f . '.blade.php';
    $src = file_get_contents($path);
    $posModal = strpos($src, 'class="modal');
    if ($posModal === false) { $posModal = strlen($src); }
    $utama = substr($src, 0, $posModal);
    $lap = [];
    $utama = preg_replace_callback('~(<table\b[^>]*class=")data-table("[^>]*>)(.*?)<th(?=[\s>])[^>]*>\s*([^<]*)~s',
        function ($m) use (&$lap, $f, $scanner) {
            $aksi = preg_match('~^(actions?|aksi)$~i', trim($m[4])) && !in_array($f, $scanner, true);
            $lap[] = trim($m[4]) . ($aksi ? '=>hover' : '');
            return $m[1] . 'data-table' . ($aksi ? ' po-aksi-hover' : '') . $m[2] . $m[3] . substr($m[0], strlen($m[1] . 'data-table' . $m[2] . $m[3]));
        }, $utama);
    echo str_pad($f, 30) . ': ' . implode(', ', $lap) . "\n";
    if ($tulis) {
        $hasil = $utama . substr($src, $posModal);
        if (strlen($hasil) >= strlen($src)) { file_put_contents($path, $hasil); }
    }
}
