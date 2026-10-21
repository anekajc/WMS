# Perbaikan menu Out Bound ajcwms — 21 Oktober 2026

Menu yang diperiksa (15): Picking List SO, Picking List Non SO, Picking List Booking, Picking,
QC Picking, Packing, SPB (Surat Jalan), Retur Beli, Transfer Barang, Serah Sample, Serah Konsi,
Transfer Gudang Out, Permintaan UK, Picking UK, Finalisasi UK.

## Yang harus dilakukan saat menerapkan di server klien

Cukup deploy file kode (lewat GitHub seperti biasa). **Tidak ada perubahan database.**

## Perubahan

Baris kode lama tidak dihapus: dijadikan komentar bertanda `Dinonaktifkan 2026-10-21`,
lalu baris pengganti ditambahkan di bawahnya.

### 1. Kecepatan (hasil data tidak berubah)

Polanya sama dengan In Bound: dulu **seluruh isi view** diambil dari database lalu disaring di PHP.
Sekarang penyaringan (nomor bukti, periode, kode barang) dilakukan di query database.
Hasilnya sudah dibandingkan dengan cara lama memakai data asli DBSMLBARU: isinya sama.
(Satu-satunya perbedaan: urutan barang di dalam satu nomor Picking, yang memang tidak pernah
diurutkan sejak versi lama.)

| Aksi | Sebelum | Sesudah |
|---|---|---|
| Buka Picking List SO | ±100 dtk | ±15 dtk |
| Buka Picking List Booking | ±115 dtk | ±14 dtk |
| Buka Picking | 46 dtk | 2 dtk |
| Buka QC Picking | 69 dtk | 2 dtk |
| Buka Finalisasi UK | 22 dtk | 3 dtk |
| Buka Permintaan UK | 10 dtk | 2 dtk |
| Buka Picking UK | 6 dtk | 2 dtk |
| Klik detail Packing / SPB | 160–245 dtk (seluruh view) | < 1 dtk |
| Klik detail Finalisasi UK | 28 dtk | < 1 dtk |
| Scan barang di Picking | ±5 dtk | ±2,7 dtk |
| Scan barang di Picking UK | ±5,7 dtk | ±1,3 dtk |

Sisa ±15 dtk di Picking List SO/Booking berasal dari stored procedure `sp_sosiapkirim` /
`sp_sosiapkirimBooking` (7–8 dtk). SP itu tidak diubah karena dipakai bersama aplikasi lain.

**Tambahan (permintaan user, masih lemot saat dibuka):** `loadAll` Picking List SO dan Booking
dulu satu permintaan sinkron (`async:false`) yang mengambil tiga bagian berurutan: SP outstanding
(±8 dtk), daftar Picking List (±3–4 dtk), transaksi per periode (±2 dtk). Layar membeku ±15 dtk.
Sekarang controller `loadAll` menerima `?bagian=outstanding|penerimaan|periode` (tanpa parameter =
semua, seperti dulu), dan halaman mengambil ketiganya **bersamaan** tanpa membekukan layar
(tanda "Memuat data..." di samping tombol Add PL). Kode gambar tabel tidak diubah (dipindah ke
`loadAllTampil()`). Isi gabungan tiga bagian sudah dibandingkan: sama persis dengan permintaan lama.
Waktu muat ≈ bagian terlama, **±8 dtk** (dulu ±15 dtk). `getMenuL0()` di `loadAll` (hasilnya tidak
dipakai) dinonaktifkan. Butuh server yang melayani beberapa permintaan sekaligus (php-fpm, sudah).

Controller: `NewPermintaanPersiapanController`, `NewPermintaanPersiapanNonSOController`,
`NewPermintaanPersiapanBooController`, `NewPickingController`, `QCPickingController`,
`PackingController`, `PersiapanSpbController`, `NewReturBeliController`,
`NewTransferBarangController`, `NewPenyerahanSampleController`, `NewPenyerahanKonsiController`,
`TransferLokasiOutController`, `PermintaanUbahKemasanController`, `UbahKemasanPickingController`,
`UbahKemasanFinalisasiController`, `UbahKemasanController` (dipakai detail/koreksi Finalisasi UK
dan Picking UK).

Query lokasi stok saat scan (Picking, Picking UK): saringan kode barang dipasang di dalam
subquery stok. Dulu stok semua barang setahun dihitung dulu, baru disaring ke barang yang di-scan.

### 2. Tampilan

- Tombol keranjang (`bi-bag-plus-fill`) di QC Picking, Packing, SPB, Retur Beli, Transfer Barang,
  Serah Sample, Serah Konsi, Transfer Gudang Out, dan Finalisasi UK diganti tombol **+** yang lebih
  besar (sama dengan In Bound). Fungsi yang dipanggil tetap sama.
- **Permintaan UK, modal daftar barang**: dulu tiap baris punya tombol +. Sekarang barang dipilih
  dengan **klik baris** (seperti newSML), kolom Actions dihapus. Daftar 19.434 barang dimuat sekali
  per halaman (buka pertama ±7 dtk, berikutnya ±0,6 dtk) dan tampil 10 per halaman dengan pencarian.
  Dulu tiap buka memuat ulang 2,9 MB dan menggambar 19 ribu baris sekaligus.
  Tombol browse "+" di samping Kode Barang diganti ikon kaca pembesar.
- `public/css/wms-tampilan-baru.css`: ditambah gaya baris pemilih (`tr.pick-row`: kursor tangan
  dan sorot saat diarahkan).

Modal lain di In Bound dan Out Bound sudah diperiksa satu per satu. Tidak ada modal pemilih lain;
tombol di modal lain adalah aksi asli (edit, hapus, cetak, buka qty reject), jadi dibiarkan.

### 3. Bug yang diperbaiki

| Menu | Masalah | Perbaikan |
|---|---|---|
| Transfer Barang | Kalau SP gagal menulis (TFL tidak punya Permintaan Transfer, gudang asal/tujuan NULL), layar tetap "TRF telah ditambah" padahal tidak ada yang tersimpan. | Controller mengecek hasilnya; kalau tidak tersimpan, layar menampilkan pesan gagal. SP tidak diubah. |
| Picking | `loadAll()` setelah edit/hapus koreksi akan error JS karena tabel daftar ada di dalam komentar HTML sejak versi lama. | `loadAll()` dilewati bila tabel tidak ada (sama dengan Put Away). |
| Picking List SO, Picking List Booking | Tombol "Add PL" memeriksa hak **hapus**, bukan hak tambah (Non SO sudah benar). | Memeriksa hak tambah (`akses_istambah`), atas permintaan user. |

## Hasil pengujian

- 15 halaman Out Bound + 8 halaman In Bound dibuka di Chrome: tanpa error JS, tanpa error server.
  Modal detail/tambah/koreksi terbuka dengan data.
- Tambah, koreksi, dan hapus dijalankan dari form asli. Permintaannya dicegat lalu diputar ke
  controller asli di dalam transaksi database yang di-**rollback** (tidak ada yang tersimpan;
  sudah dicek di database):
  - Berhasil menulis: Picking List SO, Non SO, Booking (tambah + hapus item), Picking (scan →
    simpan), QC Picking, Packing (tambah), SPB (tambah, edit, hapus item), Serah Konsi (tambah, edit,
    hapus), Permintaan UK (tambah item lewat pemilih barang baru).
  - Ditolak dengan pesan yang benar: Retur Beli, Serah Sample, Transfer Gudang Out → "Stok gudang
    tidak mencukupi" (dokumen outstanding yang tersedia untuk uji sudah lama, stoknya habis);
    hapus Packing yang sudah dibuat SPB → "Item telah diproses SPB"; koreksi SPB yang sudah
    diotorisasi → "Nobukti sudah diotorisasi".
  - Tidak bisa diuji karena tidak ada data outstanding/periode ini: tambah Finalisasi UK dan
    Picking UK, koreksi Retur Beli / Serah Sample / Transfer Gudang Out / Transfer Barang.

## Tetap seperti versi lama (dicatat, tidak diubah)

1. **Picking List Booking** memeriksa hak akses dengan kode menu `021` (Picking List SO), bukan
   `0212`. Tidak membuat menu error, karena semua user yang melihat menu Booking juga punya hak `021`.
2. **Packing**: tombol edit item di koreksi memang dimatikan sejak versi lama; yang ada hanya hapus.
3. **Transfer Barang**: 8 TFL yang tampil adalah sisa 2024–2025 tanpa Permintaan Transfer, jadi
   tidak bisa disimpan (sekarang muncul pesan). Nomor TRF terakhir dibuat Januari 2024; alur TFL
   sekarang lewat Transfer Gudang Out (FLK), yang dipakai rutin.

## Animasi memuat data (permintaan user, 21 Okt 2026)

Animasi "Memuat data..." disalin dari newSML (chip gelap + lingkaran berputar, lapisan putih
transparan; muncul setelah ±0,15 dtk supaya pemuatan cepat tidak berkedip).

- `public/css/wms-tampilan-baru.css`: kelas `.wms-memuat`, `.wms-proses-overlay`,
  `.wms-loading-chip`, `.wms-loading-spin` (hanya menambah).
- `public/js/wms-tampilan-baru.js`: fungsi bersama `wmsMemuat(true|false)` (lapisan di atas area
  tab/tabel, untuk pemuatan async) dan `wmsProses(teks, kerja)` (lapisan seluruh layar untuk
  pemuatan sinkron; digambar dulu baru pekerjaannya dijalankan). Bisa dipakai menu lain.
- Dipasang di halaman yang memuat data lewat AJAX saat dibuka: Picking List SO dan Booking
  (`wmsMemuat`, juga saat memuat ulang setelah simpan/hapus) dan Picking List Non SO (`wmsProses`).
  Menu lain datanya sudah ikut halaman dari server, jadi tidak ada jeda memuat terpisah.
