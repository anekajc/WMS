<?php
// Skrip sementara (hapus setelah dipakai): pola judul "col-6 <h1|h3>Judul</..> + col-6 tombol Add".
//   - judul -> @section('page-title'), <hN> lama dibungkus komentar Blade;
//   - tombol aktif (bukan yang sudah dikomentari) dipindah:
//       halaman bertab (ada tab-card)  -> kanan bar tab (.wms-tab-aksi);
//       halaman tanpa tab             -> diberi data-wms-toolbar="<id tabel>" (JS memindah ke toolbar tabel).
//     Elemen tombol sama persis (onclick tetap), hanya style tinggi 60px lama dilepas.
//   php konversi_judul_tmp.php [--tulis] file[:idTabel] ...
$tulis = in_array('--tulis', $argv, true);
$args = array_values(array_filter(array_slice($argv, 1), fn ($a) => $a !== '--tulis'));

foreach ($args as $arg) {
    [$f, $idTabel] = array_pad(explode(':', $arg, 2), 2, 'tabel');
    $path = __DIR__ . '/resources/views/' . $f . '.blade.php';
    $src = file_get_contents($path);
    $crlf = strpos($src, "\r\n") !== false;
    $s = str_replace("\r\n", "\n", $src);
    $judul = null;
    $tombol = [];

    $s = preg_replace_callback('~(<div class="col-6 text-left">\s*)<h([123])>\s*([^<]+?)\s*</h\2>(\s*</div>\s*<div class="col-6 text-right">)(.*?)(</div>)~s',
        function ($m) use (&$judul, &$tombol) {
            $judul = $m[3];
            $kanan = $m[5];
            // tombol aktif = di luar komentar HTML
            $tanpaKomentar = preg_replace('~<!--.*?-->~s', '', $kanan);
            preg_match_all('~<button\b[^>]*>.*?</button>~s', $tanpaKomentar, $b);
            foreach ($b[0] as $btn) {
                $tombol[] = $btn;
                $kanan = str_replace($btn, '{{-- tombol dipindah ke bar tab / toolbar tabel --}}', $kanan);
            }
            return $m[1] . '{{-- <h' . $m[2] . '>' . $m[3] . '</h' . $m[2] . '> - judul pindah ke header (page-title) --}}' . $m[4] . $kanan . $m[6];
        }, $s, 1);

    if ($judul === null) { echo str_pad($f, 30) . ": !! pola judul tidak ditemukan\n"; continue; }

    $bersih = array_map(function ($btn) {
        $btn = preg_replace('~\s*style="height: 60px; "~', '', $btn, 1);
        return $btn;
    }, $tombol);

    $ket = '';
    if ($bersih) {
        if (strpos($s, 'class="card mb-3 tab-card"') !== false) {
            $s = preg_replace_callback('~(<div class="card mb-3 tab-card">\s*<div class="card-body)(">\s*<div class="nav nav-tabs border-0 custom-tabs"[^>]*>.*?\n      </div>)~s',
                function ($m) use ($bersih) {
                    return $m[1] . ' wms-tab-bar' . $m[2] . "\n      <div class=\"wms-tab-aksi\">\n        " . implode("\n        ", $bersih) . "\n      </div>";
                }, $s, 1, $n);
            $ket = $n ? 'tombol->bar tab' : '!! tab-card tidak cocok';
            if (!$n) { echo str_pad($f, 30) . ": $ket\n"; continue; }
        } else {
            $ditandai = array_map(fn ($btn) => preg_replace('~^<button\b~', '<button data-wms-toolbar="' . $idTabel . '"', $btn), $bersih);
            // tombol tetap di tempat semula (kolom kanan) tapi ditandai; JS memindahnya ke toolbar tabel.
            $s = str_replace('{{-- tombol dipindah ke bar tab / toolbar tabel --}}', '', $s);
            $s = preg_replace('~(<div class="col-6 text-right">)~', "$1\n      " . implode("\n      ", $ditandai), $s, 1);
            $ket = 'tombol->toolbar #' . $idTabel;
        }
    }
    if (strpos($s, "@section('page-title'") === false) {
        $s = preg_replace("~(@extends\('newmaster'\)\n)~", "$1@section('page-title', '" . str_replace("'", "\\'", $judul) . "')\n", $s, 1);
    }
    echo str_pad($f, 30) . ": judul($judul) tombol=" . count($bersih) . " $ket\n";
    if ($tulis) { file_put_contents($path, $crlf ? str_replace("\n", "\r\n", $s) : $s); }
}
