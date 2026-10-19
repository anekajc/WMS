<?php
// Skrip sementara (hapus setelah dipakai): halaman report ajcwms -> pola menu newpo.
//   - <h3>Judul</h3> -> @section('page-title') (h3 lama dikomentari Blade);
//   - tombol Filter (dan Export to Excel bila ada) dipindah ke kanan bar tab - onclick sama;
//   - blok tab card-header -> tab-card + custom-tabs, kartu isi -> wms-kartu.
$tulis = in_array('--tulis', $argv, true);
$files = array_values(array_filter(array_slice($argv, 1), fn ($a) => $a !== '--tulis'));

foreach ($files as $f) {
    $path = __DIR__ . '/resources/views/' . $f . '.blade.php';
    $src = file_get_contents($path);
    $crlf = strpos($src, "\r\n") !== false;
    $s = str_replace("\r\n", "\n", $src);
    $lap = [];
    $judul = null;
    $tombol = [];

    $s = preg_replace_callback('~<h3>\s*([^<]+?)\s*</h3>\s*(<button[^>]*onclick="buttonOpenFilter\(\)"[^>]*>.*?</button>)~s',
        function ($m) use (&$judul, &$tombol, &$lap) {
            $judul = $m[1];
            $tombol[] = preg_replace('~\s*style="[^"]*"~', '', $m[2], 1);
            $lap[] = 'judul+filter';
            return "{{-- <h3>" . $m[1] . "</h3> - judul pindah ke header (page-title); tombol Filter pindah ke bar tab --}}";
        }, $s, 1);

    $s = preg_replace_callback('~<div class="row">\s*<div class="col-12 text-right">\s*(<button[^>]*exportTableToExcel[^>]*>[^<]*</button>)\s*(<!--.*?-->)?\s*</div>\s*</div>~s',
        function ($m) use (&$tombol, &$lap) {
            $tombol[] = $m[1];
            $lap[] = 'export';
            return "{{-- tombol Export to Excel pindah ke bar tab --}}";
        }, $s, 1);

    $s = preg_replace_callback('~<div class="card mt-[0-9]">\s*<div class="card-header">\s*<div class="row">\s*<nav style="width: 100%;">\s*'
        . '<div class="nav nav-tabs col-12" id="nav-tab" role="tablist">(.*?)</div>\s*</nav>\s*</div>\s*</div>\s*<div class="card-body"\s*>~s',
        function ($m) use (&$tombol, &$lap) {
            $links = rtrim(str_replace(' style="color: black;"', '', $m[1]));
            $lap[] = 'tab';
            return "<div class=\"card mb-3 tab-card\">\n    <div class=\"card-body wms-tab-bar\">\n"
                . "      <div class=\"nav nav-tabs border-0 custom-tabs\" id=\"nav-tab\" role=\"tablist\">" . $links . "\n      </div>\n"
                . "      <div class=\"wms-tab-aksi\">\n        " . implode("\n        ", $tombol) . "\n      </div>\n"
                . "    </div>\n  </div>\n  <div class=\"card wms-kartu\">\n    <div class=\"card-body\">";
        }, $s, 1);

    if ($judul !== null && strpos($s, "@section('page-title'") === false) {
        $s = preg_replace("~(@extends\('newmaster'\)\n)~", "$1@section('page-title', '" . str_replace("'", "\\'", $judul) . "')\n", $s, 1);
    }
    $ok = in_array('tab', $lap, true) && in_array('judul+filter', $lap, true);
    echo str_pad($f, 22) . ': ' . implode(', ', $lap) . ' | tombol=' . count($tombol) . ($ok ? '' : '  !! TIDAK LENGKAP') . "\n";
    if ($tulis && $ok) {
        file_put_contents($path, $crlf ? str_replace("\n", "\r\n", $s) : $s);
    }
}
