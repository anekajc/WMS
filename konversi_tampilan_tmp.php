<?php
// Skrip sementara (hapus setelah dipakai): ubah markup halaman ajcwms ke pola menu newpo.
//   php konversi_tampilan_tmp.php [--tulis] file1 file2 ...
// Tanpa --tulis hanya melaporkan apa yang akan diubah.
// Yang diubah HANYA markup tampilan:
//   1. blok tab lama (card > card-header > nav-tabs) -> tab-card + custom-tabs, card isi -> wms-kartu;
//   2. tabel utama (di luar modal) class "table table-bordered table-striped" -> "data-table"
//      (+ po-aksi-hover bila kolom pertamanya Actions dan bukan halaman scanner);
//   3. judul <h1> di awal konten -> @section('page-title') (h1 lama dibungkus komentar Blade).
// id, onclick, isi tabel, dan seluruh JS halaman tidak disentuh.

$tulis = in_array('--tulis', $argv, true);
$files = array_values(array_filter(array_slice($argv, 1), fn ($a) => $a !== '--tulis'));
$scanner = ['newputaway', 'newpicking', 'ubahkemasanpicking', 'newopnamegudang', 'transferlokasisite', 'opnamegdg', 'opnamegdginspeksi'];

foreach ($files as $f) {
    $path = __DIR__ . '/resources/views/' . $f . '.blade.php';
    $src = file_get_contents($path);
    if ($src === false || $src === '') { echo "$f: GAGAL BACA\n"; continue; }
    $crlf = strpos($src, "\r\n") !== false;
    $s = str_replace("\r\n", "\n", $src);
    $lap = [];

    // Batas bagian utama = sebelum modal pertama.
    $posModal = strpos($s, 'class="modal');
    if ($posModal === false) { $posModal = strlen($s); }
    $utama = substr($s, 0, $posModal);
    $sisa = substr($s, $posModal);

    // 1. Blok tab.
    $reTab = '~<div class="card">\s*<div class="card-header">\s*<div class="row">\s*<nav style="width: 100%;">\s*'
        . '<div class="nav nav-tabs col-12" id="nav-tab" role="tablist">(.*?)</div>\s*</nav>\s*</div>\s*</div>\s*'
        . '<div class="card-body"\s*>~s';
    $utama = preg_replace_callback($reTab, function ($m) use (&$lap) {
        $links = str_replace(' style="color: black;"', '', $m[1]);
        $links = rtrim($links);
        $lap[] = 'tab(' . substr_count($m[1], 'data-toggle="tab"') . ')';
        return "<div class=\"card mb-3 tab-card\">\n    <div class=\"card-body\">\n"
            . "      <div class=\"nav nav-tabs border-0 custom-tabs\" id=\"nav-tab\" role=\"tablist\">" . $links . "\n      </div>\n"
            . "    </div>\n  </div>\n  <div class=\"card wms-kartu\">\n    <div class=\"card-body\">";
    }, $utama, -1, $nTab);

    // 2. Tabel utama.
    $utama = preg_replace_callback('~<table(\s+id="([A-Za-z0-9_]+)")?(\s+)class="table table-bordered table-striped"([^>]*)>(.{0,600}?)<th[^>]*>\s*([^<]*)~s',
        function ($m) use (&$lap, $f, $scanner) {
            $aksi = preg_match('~^(actions?|aksi)$~i', trim($m[6])) && !in_array($f, $scanner, true);
            $kelas = 'data-table' . ($aksi ? ' po-aksi-hover' : '');
            $lap[] = 'tabel#' . ($m[2] !== '' ? $m[2] : '?') . ($aksi ? '+hover' : '');
            return '<table' . $m[1] . $m[3] . 'class="' . $kelas . '"' . $m[4] . '>' . $m[5] . substr($m[0], strlen('<table' . $m[1] . $m[3] . 'class="table table-bordered table-striped"' . $m[4] . '>' . $m[5]));
        }, $utama);
    // tabel dengan urutan atribut class sebelum id
    $utama = preg_replace_callback('~<table class="table table-bordered table-striped"(\s+id="([A-Za-z0-9_]+)")([^>]*)>~',
        function ($m) use (&$lap) {
            $lap[] = 'tabel#' . $m[2] . '(class-dulu)';
            return '<table class="data-table"' . $m[1] . $m[3] . '>';
        }, $utama);

    // 3. Judul h1 di awal konten.
    $judul = null;
    $utama = preg_replace_callback('~<div class="container-fluid">\s*<h1>\s*([^<]+?)\s*</h1>~', function ($m) use (&$judul, &$lap) {
        $judul = $m[1];
        $lap[] = 'judul(' . $m[1] . ')';
        return "<div class=\"container-fluid\">\n{{-- <h1>" . $m[1] . "</h1> - judul pindah ke header (page-title) --}}";
    }, $utama, 1);

    $baru = $utama . $sisa;
    if ($judul !== null && strpos($baru, "@section('page-title'") === false) {
        $baru = preg_replace("~(@extends\('newmaster'\)\n)~", "$1@section('page-title', '" . str_replace("'", "\\'", $judul) . "')\n", $baru, 1);
    }

    $sisaTabel = preg_match_all('~class="table table-bordered table-striped"~', $utama);
    $sisaCardHeader = substr_count($utama, 'card-header');
    echo str_pad($f, 30) . ': ' . implode(', ', $lap) . " | sisa tabel-lama di utama: $sisaTabel, card-header: $sisaCardHeader\n";

    if ($tulis && $baru !== $s) {
        $hasil = $crlf ? str_replace("\n", "\r\n", $baru) : $baru;
        if (strlen($hasil) < strlen($src) * 0.9) { echo "  !! hasil terlalu pendek, tidak ditulis\n"; continue; }
        file_put_contents($path, $hasil);
    }
}
