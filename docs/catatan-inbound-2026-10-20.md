# Perbaikan menu In Bound ajcwms — 20 Oktober 2026

Menu yang diperiksa: Penerimaan Barang, QC LPB, Put Away, Retur Jual, Retur Srt Jln,
Terima Ubah Kemasan, Transfer Lokasi In, Retur Serah Sample.

## Yang harus dilakukan saat menerapkan di server klien

1. Deploy file kode di bawah (lewat GitHub seperti biasa).
2. **Pada waktu yang sama**, jalankan sekali di database ajcwms (DBSMLBARU):
   `docs/update-db-DBSMLBARU/2026-10-20_akses_returserahsample_019.sql`

   Langkah 2 wajib. Kalau kode sudah ter-deploy tapi skrip belum dijalankan, user
   mochtar dan iskandar tidak bisa membuka Retur Serah Sample, riduan tidak bisa
   tambah/koreksi/hapus/cetak, dan agus tidak bisa cetak. Skrip aman dijalankan ulang.

## Perubahan

Baris kode lama tidak dihapus: dijadikan komentar bertanda `Dinonaktifkan 2026-10-20`,
lalu baris pengganti ditambahkan di bawahnya.

### 1. Kecepatan (hasil data tidak berubah)

Banyak aksi dulu mengambil **seluruh isi view** dari database lalu menyaringnya di PHP.
Sekarang penyaringan (nomor bukti, gudang, periode) dilakukan di query database.
Hasilnya sudah dibandingkan dengan cara lama memakai data asli DBSMLBARU: isinya sama.

| Aksi | Sebelum | Sesudah |
|---|---|---|
| Klik detail Retur Srt Jln | 97 dtk | 0,2 dtk |
| Klik detail QC LPB | 47 dtk | 0,3 dtk |
| Klik detail Terima Ubah Kemasan | 9 dtk | 0,4 dtk |
| Klik detail / scan Put Away | 8 dtk | 0,3–0,8 dtk |
| Buka halaman Put Away | 7,3 dtk | 1,5 dtk |
| Buka halaman Terima Ubah Kemasan | 8,1 dtk | 1,5 dtk |
| Buka halaman Retur Srt Jln | 5 dtk | 2,1 dtk |

File: `NewPOController`, `NewQCLPBController`, `NewPutAwayController`, `NewReturJualController`,
`NewReturSuratJalanController`, `TerimaUbahKemasanController`, `TransferLokasiInController`,
`ReturSerahSampleController`.

Penerimaan Barang (`newpo.blade.php`): blok komentar HTML yang tetap diproses Blade
(±860 KB data tak terlihat yang ikut terkirim ke browser) diganti komentar Blade.
Tampilan tidak berubah.

### 2. Tampilan

- Tombol + di Penerimaan Barang diperbesar (sama dengan tombol di master Barang).
- Tombol keranjang (`bi-bag-plus-fill`) di Retur Jual, Retur Srt Jln, Retur Serah Sample,
  Terima Ubah Kemasan, Transfer Lokasi In, dan Put Away diganti tombol + gaya master.
  Fungsi yang dipanggil tetap sama.

### 3. Bug yang diperbaiki

| Menu | Masalah | Perbaikan |
|---|---|---|
| Retur Serah Sample | Hak akses diperiksa dengan kode menu `016` (Retur Srt Jln), dan setelah simpan dengan `014` (Retur Jual). Kode menu di sidebar adalah `019`. | `ReturSerahSampleController` memakai `019`, ditambah skrip SQL di atas. |
| Retur Srt Jln | Setelah simpan, hak tambah/koreksi/cetak berpindah ke hak menu Retur Jual (`014`). | `NewReturSuratJalanController::loadAll` memakai `016`. |
| Retur Jual | Error JS setiap qty di form tambah diubah (`input_add_qntPrint` sudah tidak ada). | Diberi pengaman, `newreturjual.blade.php`. |
| Put Away | Setelah edit/hapus koreksi: error JS + memuat daftar yang tidak ada di halaman scan. | `loadAll()` dilewati bila tabel tidak ada, `newputaway.blade.php`. |

## Hasil pengujian

- Ke-8 halaman dibuka di Chrome: tanpa error JS dan tanpa error server. Modal
  detail/tambah/koreksi terbuka dengan data.
- Tambah, edit, dan hapus dijalankan dari form asli. Permintaannya dicegat lalu diputar
  ke controller asli di dalam transaksi database yang di-**rollback** (tidak ada data
  yang tersimpan). Semuanya berhasil: Penerimaan Barang (tambah LPB, edit, hapus item),
  QC LPB, Put Away (scan, edit, hapus), Retur Jual (tambah, edit, hapus), Retur Srt Jln
  (tambah, edit, hapus), Retur Serah Sample (tambah, edit, hapus), Transfer Lokasi In
  (edit, hapus), Terima Ubah Kemasan (edit, hapus, tambah — lihat bagian 4).
- Semua 29 stored procedure yang dipanggil ada di DBSMLBARU, dan jumlah parameternya cocok.

### 4. Terima Ubah Kemasan (diperbaiki atas permintaan user, 2026-10-20)

**Tambah.** Baris terima selalu ditulis `SP_InsertOutUKI` ke KMBJ milik PR
(`TempOutstandingPO.NOBATCH`). Dulu `SP_IsiNobukti` mengembalikan nomor KMBJ itu, tetapi di
DBSMLBARU cabang tersebut dikomentari. Akibatnya keluar nomor baru dan simpan gagal
(`Violation of UNIQUE KEY IX_dbUBAHKEMASAN`) tanpa pesan apa pun di layar.

- Route baru `getnobuktiterimaubahkemasan` → `TerimaUbahKemasanController::getNoBuktiTerima`.
  Kalau PR sudah punya KMBJ, nomor itu yang dipakai. Kalau belum, cara lama (`SP_IsiNobukti`).
  Route `getnobuktiubahkemasan` milik menu Out Bound tidak diubah.
- `spAdd` memakai KMBJ milik PR. Kalau KMBJ itu ada di **periode terkunci** (`dblockperiode`),
  simpan ditolak (`3`) dengan pesan, karena stok bulan yang sudah ditutup tidak boleh berubah.
  Pesan yang sama muncul begitu tombol + diklik.
- Kalau simpan gagal di server, sekarang muncul pesan (dulu diam).
- SP database **tidak** diubah, karena `SP_IsiNobukti` juga dipakai SML desktop dan newSML.

Kondisi data: tabel PR ubah kemasan (`DBPRUBAHKEMASAN`) hanya terisi Februari–April 2024.
Penerimaan sesudahnya lewat Finalisasi UK (menu Out Bound), yang membuat KMBJ keluar+masuk
sekaligus. Satu-satunya outstanding yang tersisa, `SML/PRU/00001/0424` (sisa 5 PSG, KMBJ
`SML/KMBJ/00004/0424`), ada di periode terkunci dan sekarang ditolak dengan pesan.
Kalau sisa ini perlu diselesaikan, lakukan lewat kantor/SML desktop.

**Hapus item.** Sejak versi lama tidak pernah menghapus (sisa debug: JS `return` setelah
`console.log(res)`, dan controller `return $check2`). Keduanya dikomentari, jadi hapus
sekarang berjalan dengan pengaman yang memang sudah disiapkan: stok di lokasi receiving
(`<gudang>LREC`) harus cukup. Kalau barang sudah di-put-away, muncul pesan "Stok receiving
tidak mencukupi". Perbandingan stok diganti dari `(int)` ke `(float)` supaya qty desimal
tidak terpotong.

Diuji dalam transaksi rollback: hapus item berhasil; nomor bukti PR = KMBJ miliknya;
PR periode terkunci ditolak; hapus melebihi stok receiving ditolak. Dengan kunci April 2024
dibuka sementara di dalam transaksi, terima 1 PSG masuk ke `SML/KMBJ/00004/0424` tanpa
membuat header baru, dan outstanding turun 5 → 4. Setelah rollback, data kembali utuh.

## Hak akses Retur Serah Sample — sudah dijalankan di DBSMLBARU

`2026-10-20_akses_returserahsample_019.sql` dijalankan 2026-10-20. Hasilnya: hak `016` dan
`019` identik untuk semua user. Di server klien yang databasenya lain, skrip tetap harus
dijalankan bersamaan dengan deploy kode.

## Tetap seperti versi lama (bukan bug)

1. **Koreksi QC LPB:** hanya tampilan, karena tombol Submit-nya sudah dimatikan sejak versi lama.
2. **Retur Jual:** koreksi hanya untuk dokumen di periode kerja user, dan dokumen yang sudah
   diotorisasi ditolak.
