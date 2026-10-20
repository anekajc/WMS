-- =====================================================================================
-- 2026-10-20  Hak akses menu Retur Serah Sample pindah ke kode menunya sendiri (019)
-- Database : DBSMLBARU
-- Tabel    : new_aksesmenu
--
-- LATAR BELAKANG
--   Sejak versi lama, ReturSerahSampleController memeriksa hak akses dengan kode menu
--   '016' (milik Retur Srt Jln) di index(), dan '014' (Retur Jual) di loadAll().
--   Padahal di sidebar (new_menu) Retur Serah Sample = KODEMENU '019'.
--   Mulai 2026-10-20 controller memakai '019' (lihat ReturSerahSampleController.php).
--
-- SUPAYA HAK USER TIDAK BERUBAH
--   Hak yang selama ini berlaku (baris L1 = '016') disalin ke baris L1 = '019' untuk user
--   yang barisnya berbeda. Kondisi sebelum skrip ini (cek 2026-10-20 di DBSMLBARU):
--
--   user      016 (berlaku)        019 (sebelum)
--   iskandar  buka, cetak          tidak bisa buka
--   mochtar   buka, tambah, koreksi, hapus, cetak   (tidak ada baris 019)
--   riduan    buka, tambah, koreksi, hapus, cetak   buka saja
--   agus      tambah, koreksi, hapus, cetak         tanpa cetak
--   syaidi    semua                                 tanpa IsOtorisasi2 & IsBatal
--
--   (urutan kolom hak: ISTAMBAH ISKOREKSI ISHAPUS ISCETAK ISEXPORT IsOtorisasi1..5 IsBatal pembatalan)
--
-- STATUS
--   Sudah dijalankan di DBSMLBARU (36.88.190.218) pada 2026-10-20 - sukses, hak 016 = 019 semua user.
--
-- CARA PAKAI DI SERVER CLIENT
--   Jalankan sekali di database yang dipakai ajcwms (DBSMLBARU), BERSAMAAN dengan deploy
--   ReturSerahSampleController.php versi 2026-10-20. Skrip aman dijalankan ulang:
--   hanya menyentuh user di daftar di bawah dan hanya menyalin dari baris '016'.
-- =====================================================================================

set nocount on;

declare @user table (username varchar(50));
insert into @user values ('iskandar'), ('mochtar'), ('riduan'), ('agus'), ('syaidi');

begin tran;

-- 1. User yang sudah punya baris 019: samakan isinya dengan baris 016.
update b set
    b.HASACCESS    = a.HASACCESS,
    b.ISTAMBAH     = a.ISTAMBAH,
    b.ISKOREKSI    = a.ISKOREKSI,
    b.ISHAPUS      = a.ISHAPUS,
    b.ISCETAK      = a.ISCETAK,
    b.ISEXPORT     = a.ISEXPORT,
    b.IsOtorisasi1 = a.IsOtorisasi1,
    b.IsOtorisasi2 = a.IsOtorisasi2,
    b.IsOtorisasi3 = a.IsOtorisasi3,
    b.IsOtorisasi4 = a.IsOtorisasi4,
    b.IsOtorisasi5 = a.IsOtorisasi5,
    b.IsBatal      = a.IsBatal,
    b.pembatalan   = a.pembatalan
from new_aksesmenu b
join new_aksesmenu a on a.USERID = b.USERID and a.L1 = '016'
join new_users u on u.id = b.USERID
join @user x on x.username = u.username
where b.L1 = '019';

-- 2. User yang belum punya baris 019 (mis. mochtar): buat dari baris 016.
insert into new_aksesmenu (USERID, L1, HASACCESS, ISTAMBAH, ISKOREKSI, ISHAPUS, ISCETAK, ISEXPORT,
    IsOtorisasi1, IsOtorisasi2, IsOtorisasi3, IsOtorisasi4, IsOtorisasi5, IsBatal, pembatalan)
select a.USERID, '019', a.HASACCESS, a.ISTAMBAH, a.ISKOREKSI, a.ISHAPUS, a.ISCETAK, a.ISEXPORT,
    a.IsOtorisasi1, a.IsOtorisasi2, a.IsOtorisasi3, a.IsOtorisasi4, a.IsOtorisasi5, a.IsBatal, a.pembatalan
from new_aksesmenu a
join new_users u on u.id = a.USERID
join @user x on x.username = u.username
where a.L1 = '016'
  and not exists (select 1 from new_aksesmenu b where b.USERID = a.USERID and b.L1 = '019');

commit;

-- Periksa: kolom r16 dan r19 harus sama untuk kelima user.
select u.username, a.HASACCESS h16, b.HASACCESS h19,
  cast(a.ISTAMBAH as char(1)) + cast(a.ISKOREKSI as char(1)) + cast(a.ISHAPUS as char(1)) + cast(a.ISCETAK as char(1)) r16,
  cast(b.ISTAMBAH as char(1)) + cast(b.ISKOREKSI as char(1)) + cast(b.ISHAPUS as char(1)) + cast(b.ISCETAK as char(1)) r19
from new_users u
join @user x on x.username = u.username
left join new_aksesmenu a on a.USERID = u.id and a.L1 = '016'
left join new_aksesmenu b on b.USERID = u.id and b.L1 = '019'
order by u.username;
