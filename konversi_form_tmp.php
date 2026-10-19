<?php
// Skrip sementara (hapus setelah dipakai): halaman form/filter ajcwms.
//   - <div class="col-12 text-left"><h1>Judul</h1> -> @section('page-title'), h1 dikomentari Blade;
//   - kartu form ditambah kelas wms-form-kartu (gaya kartu form newSML). Isi form tidak disentuh.
$tulis = in_array('--tulis', $argv, true);
$files = array_values(array_filter(array_slice($argv, 1), fn ($a) => $a !== '--tulis'));
foreach ($files as $f) {
    $path = __DIR__ . '/resources/views/' . $f . '.blade.php';
    $src = file_get_contents($path);
    $crlf = strpos($src, "\r\n") !== false;
    $s = str_replace("\r\n", "\n", $src);
    $lap = [];
    $judul = null;
    $s = preg_replace_callback('~(<div class="col-12 text-left">\s*)<h1>\s*([^<]+?)\s*</h1>~', function ($m) use (&$judul, &$lap) {
        $judul = $m[2];
        $lap[] = "judul($m[2])";
        return $m[1] . '{{-- <h1>' . $m[2] . '</h1> - judul pindah ke header (page-title) --}}';
    }, $s, 1);
    $s = preg_replace_callback('~<div class="card( bg-light mb-3)?"( style="[^"]*")?\s*>~', function ($m) use (&$lap) {
        $lap[] = 'kartu-form';
        return '<div class="card wms-form-kartu' . ($m[1] ?? '') . '"' . ($m[2] ?? '') . ' >';
    }, $s);
    if ($judul !== null && strpos($s, "@section('page-title'") === false) {
        $s = preg_replace("~(@extends\('newmaster'\)\n)~", "$1@section('page-title', '" . str_replace("'", "\\'", $judul) . "')\n", $s, 1);
    }
    echo str_pad($f, 24) . ': ' . implode(', ', $lap) . "\n";
    if ($tulis) { file_put_contents($path, $crlf ? str_replace("\n", "\r\n", $s) : $s); }
}
