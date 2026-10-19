-- 2026-10-19 (ajcwms): beri user "sa" akses ke halaman Transfer Gudang (/transfergudang).
-- NewTransferGudangController mengecek new_aksesmenu dengan kode menu L1 = '015'; user sa belum
-- punya baris itu sehingga halaman selalu dialihkan ke /home.
-- Hanya MENAMBAH baris (kalau belum ada); hak akses disamakan dengan menu sa yang lain (semua 1).
-- Catatan: kode menu '015' tidak ada di tabel new_menu, jadi menu ini tetap tidak muncul di daftar
-- menu - halaman dibuka lewat alamat /transfergudang.

IF NOT EXISTS (
    SELECT 1 FROM new_aksesmenu
    WHERE USERID = (SELECT id FROM new_users WHERE username = 'sa') AND L1 = '015'
)
INSERT INTO new_aksesmenu
    (USERID, L1, HASACCESS, ISTAMBAH, ISKOREKSI, ISHAPUS, ISCETAK, ISEXPORT,
     IsOtorisasi1, IsOtorisasi2, IsOtorisasi3, IsOtorisasi4, IsOtorisasi5, IsBatal, pembatalan)
SELECT id, '015', 1, 1, 1, 1, 1, 1,
       1, 1, 1, 1, 1, 1, 1
FROM new_users
WHERE username = 'sa';
