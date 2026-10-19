<?php
// Skrip sementara (hapus setelah dipakai): karakter "}" sisa di luar <script> (antara </script> dan
// @endsection) tampil sebagai teks di pojok halaman - dibungkus komentar Blade. Bukan kode JS.
foreach (array_slice($argv, 1) as $f) {
    $path = __DIR__ . '/resources/views/' . $f . '.blade.php';
    $src = file_get_contents($path);
    $baru = preg_replace('~(</script>[ \t\r\n]*)\}([ \t\r\n]*@endsection[ \t\r\n]*)$~',
        '$1{{-- } - karakter sisa di luar <script> (dulu tampil sebagai teks di halaman) --}}$2', $src, 1, $n);
    echo str_pad($f, 36) . ": $n\n";
    if ($n === 1) { file_put_contents($path, $baru); }
}
