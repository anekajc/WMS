# Perbaikan menu Gudang ajcwms — 22 Oktober 2026

Menu yang diperiksa (7, sesuai tabel `new_menu`): Permintaan Pemakaian Internal (031),
Pemakaian Internal (032), Opname Gudang (033), Permintaan Transfer Gudang/Lokasi (034),
Transfer Lokasi Scan (036), Opname Harian (037), Inspeksi Opname Harian (038).

## Yang harus dilakukan saat menerapkan di server klien

Cukup deploy file kode (lewat GitHub seperti biasa). **Tidak ada perubahan database.**
Ada 1 route baru (`transferlokasisitecekbarang`); container Docker menjalankan `route:cache` tiap
start, jadi route ikut terdaftar saat container di-restart/rebuild seperti deploy biasa.

## Perubahan

Baris kode lama tidak dihapus: dijadikan komentar bertanda `Dinonaktifkan 2026-10-22`,
lalu baris pengganti ditambahkan di bawahnya. Patokan tampilan: menu In Bound dan Out Bound.

### 1. Kecepatan (hasil data tidak berubah)

Diukur dengan data asli DBSMLBARU. Hasil cara baru dibandingkan dengan cara lama: **isi sama**
(satu-satunya beda: urutan nomor bukti dari database, sedangkan tabel di layar diurutkan No. Bukti
oleh DataTables, jadi tampilan sama).

| Aksi | Sebelum | Sesudah |
|---|---|---|
| Transfer Lokasi (Scan): scan lokasi awal | ±8 dtk (kirim 18.934 barang tiap scan) | 0,1 dtk (+ 0,1 dtk saat scan barang) |
| Transfer Lokasi (Scan): buka halaman | +7,8 dtk memuat seluruh view alokasi yang tidak ditampilkan | tidak dimuat |
| Opname Gudang: daftar periode | 6,2 dtk (seluruh view 6.791 baris) | 0,15 dtk |
| Permintaan Transfer Lokasi: daftar periode | 2,1 dtk | 0,25 dtk |
| Permintaan Pemakaian Internal: daftar periode | 1,1 dtk | 0,2 dtk |
| Pemakaian Internal: daftar periode | 0,5 dtk | 0,15 dtk |
| Detail/koreksi per nomor bukti (031–034) | seluruh view | 0,1–0,2 dtk |
| Opname Harian: daftar (3 query → 1 query) | 1,8 dtk (periode 9/2026, 473 baris) | 0,6 dtk |
| Opname Harian: tombol Verifikasi | memuat ulang 3 tabel dulu | 0,7 dtk |
| Pemilih barang (031, 034): buka pertama | 7–8 dtk (19.435 barang, 2,8 MB) | ±0,1 dtk (dimuat di belakang layar saat halaman dibuka) |
| Pemilih barang: buka berikutnya | 7–8 dtk (dimuat ulang tiap klik) | 5 ms |

Catatan Transfer Lokasi (Scan): daftar barang dulu adalah UNION yang bagian keduanya mengambil
**semua** barang aktif non-JS, jadi barang yang lolos = barang aktif dan bukan grup JS. Sudah
dibuktikan di 3 lokasi (18.895 barang, himpunan sama) dan 322 kode uji (data barang identik).
Sekarang dicek per kode (`cekBarang`). Barang yang di-scan sebelum lokasi awal tetap ditolak
seperti dulu.

### 2. Tampilan (disamakan dengan In Bound / Out Bound)

- Pemakaian Internal: tombol keranjang diganti tombol **+**.
- Permintaan Pemakaian Internal dan Permintaan Transfer Lokasi: tombol browse "+" (Kode Barang,
  Lokasi Tujuan, Lokasi Asal) diganti ikon **kaca pembesar**.
- Modal pemilih **barang** dan **lokasi**: kolom Actions dihapus, pilih dengan **klik baris**;
  barang tampil 10 per halaman dengan pencarian.
- Tabel pemilih dulu tampil sempit di tengah modal (lebar dihitung saat modal masih tersembunyi).
  Diperbaiki (`autoWidth: false`) di 031, 034, dan juga di Permintaan UK (Out Bound) yang menjadi
  patokan dan punya masalah yang sama.
- Opname Gudang, Opname Harian, Inspeksi: tombol kolom Actions tetap selalu terlihat (dipakai di
  PDA/layar sentuh), sama dengan halaman scan Out Bound.

### 3. Bug yang diperbaiki

| Menu | Masalah | Perbaikan |
|---|---|---|
| Pemakaian Internal | Edit koreksi: qty baru dibandingkan dengan saldo yang **sudah dikurangi qty lama** item itu, jadi menaikkan qty ditolak walau stok cukup. | Batas = saldo + qty lama item yang sama. Diuji: 100 → 31.450 (= saldo 31.350 + 100) diterima, 31.451 ditolak. |
| Pemakaian Internal | Outstanding `SML/PLN/00007/0825` tidak punya header di `DboutBRG`; simpan tidak menyimpan apa pun tapi layar "PBG telah ditambah". Ini satu-satunya outstanding saat ini. | Controller mengecek hasil simpan; kalau kosong muncul pesan gagal "data dokumen Out tidak lengkap". Data itu perlu dibereskan dari kantor/SML desktop. |
| Pemakaian Internal | Koreksi edit: pesan sukses tidak pernah muncul. Tambah/hapus: pesan sukses walau ditolak. | Pesan mengikuti hasil server. |
| Pemakaian Internal | Hapus item ikut cek stok (bisa ditolak diam-diam). | Hapus tidak cek stok (mengembalikan stok). |
| Opname Harian | Hapus opname yang sudah diverifikasi ditolak SP, tapi layar "Berhasil hapus opname". | Pesan "Opname sudah diverifikasi, tidak bisa dihapus" (syarat sama dengan SP). |
| Opname Gudang | Scan lokasi lalu barang → error JS, item tidak masuk list. | Gudang dari lokasi disimpan; urutan scan bebas, lokasi tidak perlu di-scan ulang per barang. |
| Opname Gudang | `resetInputKoreksi()` tidak ada → error JS; fokus scanner Koreksi salah. | Fungsi ditambahkan, fokus benar. Pesan sukses setelah simpan. |
| Permintaan Transfer Lokasi | Edit Item: cari lokasi selalu "Pilih barang terlebih dahulu". | Kode barang dibaca dari form Edit. |
| Pemakaian Internal, Transfer Lokasi Scan, Opname Harian | Cek stok `(int)` memotong desimal. | `(float)`. Diuji: qty 1,5 tersimpan 1,50. |
| Inspeksi Opname Harian | Urut tanggal terpasang di kolom Selisih. | Kolom Tanggal (diuji: urutan tanggal benar). |

## Hasil pengujian (data asli DBSMLBARU, 22 Okt 2026)

**Controller asli di dalam transaksi yang di-ROLLBACK** (dicek ulang di database: tidak ada data
uji yang tersisa, data asli utuh):

- 031: tambah, edit qty, hapus — berhasil.
- 032: edit naik sampai batas benar (diterima) / lebih (ditolak), hapus item; tambah dari outstanding:
  dokumen tanpa header → pesan gagal; dengan header (sementara di transaksi) → tersimpan 1,50,
  outstanding 30 → 28,5; nomor dobel → nomor di-refresh.
- 033: scan lokasi/barang, tambah, koreksi qty (sesuai hitungan halaman), hapus — berhasil.
- 034: tambah, edit qty, hapus — berhasil.
- 036: scan lokasi benar/salah, scan barang benar/salah, simpan RLB (tersimpan), qty melebihi stok → ditolak.
- 037: tambah, koreksi, hapus sebelum verifikasi (terhapus), verifikasi, masuk tab Non Clear,
  hapus setelah verifikasi → ditolak dengan pesan.
- 038: muncul di Inspeksi, koreksi, verifikasi audit, pindah ke tab Teraudit.

**Chrome** (login user syaidi, periode 9/2026; tidak ada yang disimpan): ketujuh halaman tanpa error JS
dan tanpa error HTTP. Tombol +, kaca pembesar, klik baris barang/lokasi (mode tambah dan edit),
pencarian pemilih, alur scan Opname Gudang & Transfer Lokasi, pesan hapus Opname Harian, urut tanggal
Inspeksi.

## Tetap seperti versi lama (dicatat, tidak diubah)

1. Scanner memakai `oninput` (diproses per karakter), cocok untuk scanner PDA yang mengirim kode sekaligus.
2. Inspeksi Opname Harian: tombol hapus memang dimatikan sejak versi lama.
3. Menu sidebar (`getMenuL0`, ±0,8 dtk per halaman) dipakai semua menu; tidak diubah di sini.
4. Route lama `HitungStokController` di `routes/web.php` menunjuk controller yang tidak ada
   (`php artisan route:list` gagal karenanya). Tidak berhubungan dengan Gudang; tidak diubah.
