# Pemeriksaan semua menu ajcwms — 27 Oktober 2026

Semua menu di sidebar diperiksa (tabel `new_menu` + `new_menureport`): Master (6), In Bound (8),
Out Bound (15), Gudang (7), Dashboard (1: Opname Harian), Report (13), ditambah halaman home.

## Yang harus dilakukan saat menerapkan di server klien

1. Deploy file kode (lewat GitHub seperti biasa). Tidak ada route baru.
2. **Opsional tapi disarankan** (Dashboard Opname Harian jauh lebih cepat), jalankan sekali di database:
   `docs/update-db-DBSMLBARU/2026-10-27_sp_dashboardopnameharian_recompile.sql`
   Tanpa skrip ini menu tetap jalan, hanya lambat seperti sekarang. Skrip aman dijalankan ulang.
   Cadangan definisi sebelum diubah: `2026-10-27_sp_dashboardopnameharian_sebelum.sql`.

**Di DBSMLBARU skrip ini sudah dijalankan 2026-10-27 14:40** (atas persetujuan user), setelah dicek
SP di database sama persis dengan cadangan. Dibandingkan lagi dengan SP lama (salinan sementara dari
cadangan): 13/13 kombinasi hasil identik, total 232 dtk → 39 dtk. Halaman Dashboard terbuka 3,3 dtk.

## Cara pemeriksaan

- Ke-51 halaman (home + 50 menu) dibuka lewat aplikasi asli sebagai user `sa` (akses semua menu):
  semua status 200, tanpa error server.
- Ke-51 halaman dibuka di Chrome (headless), termasuk AJAX yang jalan saat halaman dibuka:
  **tanpa error JavaScript**.
- Pemeriksaan statis semua view menu: fungsi yang dipanggil tombol (`onclick`, `onchange`, dst.)
  harus ada; elemen yang diisi lewat `getElementById` harus ada; URL AJAX harus punya route.
- Ke-16 tombol filter/tampilkan di 13 report dicoba dengan parameter yang sama dengan layar: semua 200.

## Perubahan

Baris lama tidak dihapus: dijadikan komentar bertanda `Dinonaktifkan 2026-10-27` / `Ditambahkan 2026-10-27`.

### 1. Tombol kaca pembesar (permintaan user)

Tombol browse kaca pembesar dulu berada di baris label (kanan atas), terpisah dari kolom input, warna
biru solid. Sekarang menempel rapat di kanan kolom input (`input-group`) dengan warna soft (sama
dengan tombol di tabel). Fungsi klik dan ID input tidak berubah. 11 tombol di 5 halaman:
Permintaan Pemakaian Internal, Permintaan Transfer Gudang (5, termasuk form Edit), Permintaan UK,
Report Kartu Stok (2), Kartu Stock Gudang (2). Gaya: `public/css/wms-tampilan-baru.css` (`.wms-browse`).

### 2. Kecepatan

| Perubahan | Dampak |
|---|---|
| `AppServiceProvider`: nama koneksi lama `SML`, `SPLSIG`, `SPL`, `sqlite` memakai **satu koneksi** yang sama dengan `sqlsrv` (server, DB, user identik). Dulu tiap nama login sendiri ke SQL Server, jadi satu halaman bisa login 3–5 kali. | Tiap halaman cukup 1 kali login ke DB. Saat jaringan lambat, login bisa 5–15 dtk per koneksi, jadi penghematannya besar. Hanya berlaku bila host/port/DB/user sama persis; kalau berbeda, tetap koneksi sendiri. Transaksi hanya dipakai di koneksi utama, jadi aman. |
| Skrip SQL `sp_dashboardopnameharian`: `OPTION (RECOMPILE)` di 6 perintah hitung target. | Buka Dashboard (semua gudang, bulanan) 26 dtk → 2,4 dtk; GMPL semester 69 dtk → 3,7 dtk; GMPL tahunan 51 dtk → 2,7 dtk. Diuji 13 kombinasi gudang/tipe/periode (dengan salinan SP sementara `#sp`): **hasil identik**. Gudang kecil yang sudah cepat (<1 dtk) bertambah ±1 dtk karena kompilasi. |

### 3. Bug yang diperbaiki

| Menu | Masalah | Perbaikan |
|---|---|---|
| Home (beranda setelah login) | Komentar JS di `newmaster.blade.php` berisi teks `@section('page-title')`; Blade menjalankannya sebagai perintah sungguhan, sehingga isi halaman home sebelum baris itu (CSS, header, sidebar) terbuang dari respons. | Ditulis `@@section` (Blade menulis teksnya saja). Home sekarang utuh (44 KB, dulu 11 KB). Halaman menu lain tidak terdampak. |
| User (keamanan) | Tambah, edit, hapus, ganti password user, dan ubah hak akses user (`setpemakaiupdateakses*`) hanya dibatasi tombol di layar. User mana pun yang login bisa memanggil route-nya langsung, misalnya mengganti password user lain atau memberi dirinya akses semua menu. | Hak yang sama dengan layar dicek juga di server (menu 005): tambah = ISTAMBAH, edit & ubah hak akses = ISKOREKSI, hapus = ISHAPUS, ganti password = IsOtorisasi1. Diuji (hanya baca) untuk 23 user × 4 hak: 92/92 sesuai tabel `new_aksesmenu`. User yang punya hak tidak merasakan perbedaan. |
| Picking | Tombol **Batal** di form Edit Item koreksi memanggil `hideShowhide()` yang tidak ada (sejak versi lama): error JS, form tidak tertutup. | Fungsi ditambahkan (sama dengan Put Away). |
| Permintaan UK | Tombol **Edit** item mengisi elemen `#AddEditNamaMerk` yang tidak ada (sejak versi lama): error JS, form Edit Item tidak pernah muncul. | Baris itu hanya dijalankan bila elemennya ada. |

## Dibiarkan seperti versi lama atas keputusan user (2026-10-27)

**Report Kartu Stok (051) selalu kosong di DBSMLBARU.** Layar mengirim kode **lokasi** (sama dengan versi
lama), tetapi SP `Sp_reportkartuStockLokasi` sejak 7 Mei 2025 membaca `vwKartuStock` dan menyaring
**gudang** (`KodeGdg`); baris lama `--vwKartuStockSIG A` masih tersisa sebagai komentar. Kartu Stock
Gudang (059) memakai SP yang sama dengan kode gudang dan berjalan benar, jadi SP tidak boleh sekadar
dikembalikan. Diuji: 3 lokasi dengan 60–122 mutasi → 0 baris; dengan kode gudang → 93 baris.
Hanya ajcwms yang memanggil SP ini.

## Tetap seperti versi lama (dicatat, tidak diubah)

1. Master Barang & Gudang: route `addBarangJadi/editBarangJadi/eraseBarangJadi` dan
   `addGudang/editGudang/eraseGudang` menunjuk method yang tidak ada; form-nya memang sudah
   dikomentari sejak versi lama. Tidak terlihat oleh user.
2. Report Outstanding Receiving dan Outstanding Picking-SPB mengirim seluruh data (±4.700–5.400 baris,
   ±2,8 MB) ke browser lalu dipecah per halaman oleh DataTables — sama dengan versi lama.
3. Report lain 1–5 dtk: waktunya ada di SP report masing-masing (tidak diubah).
4. Dashboard: daftar barang saat kartu diklik dengan pilihan gudang "-Semua" selalu kosong, karena
   `sp_dashboardopnameharianlistbarang` menyaring per gudang (desain SP sejak 2025).
5. Transfer Lokasi (Scan): modal `#formKoreksi` tidak pernah dibuka, jadi tombol di dalamnya (yang
   memanggil fungsi yang tidak ada) tidak terjangkau user.
