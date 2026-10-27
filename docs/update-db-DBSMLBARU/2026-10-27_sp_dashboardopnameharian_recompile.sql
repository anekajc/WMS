-- 2026-10-27 (ajcwms): percepat sp_dashboardopnameharian (menu Dashboard > Opname Harian).
-- Perintah hitung target audit (select @jmlCount = COUNT(*) ...) diberi OPTION (RECOMPILE) di 6 tempat.
-- Tanpa itu SQL Server memakai rencana query umum yang buruk untuk variabel @TahunPeriode/@bulanPeriode,
-- mis. buka halaman (semua gudang, bulanan) 26 dtk -> 2,4 dtk; GMPL semester 69 dtk -> 3,7 dtk.
-- Isi query lain TIDAK diubah. Diuji 13 kombinasi gudang/tipe/periode di DBSMLBARU: hasil identik.
-- Definisi sebelum diubah: 2026-10-27_sp_dashboardopnameharian_sebelum.sql. Aman dijalankan ulang.


ALTER PROCEDURE [dbo].[sp_dashboardopnameharian]
--Declare
@bulan int,
@tahun int,
@gudang varchar(30),
@tipe tinyint,
@semester tinyint



AS

--select @tipe=2,@bulan=4,@tahun=2025,@semester=2,@gudang='GMPL'


declare @jmlCount float,@jmlterhitung Float,@jmlsesuai float,@selisihkurang float,@selisihlebih float,@TGLSEMSTER1 DATE,@TGLSEMESTER2 dATE
declare @bulanPeriode int,@TahunPeriode int
SET @TGLSEMSTER1=CAST('06/01/'+ CAST(@tahun AS VARCHAR(4)) AS DATE) --06/01/2025
SET @TGLSEMESTER2=CAST('01/01/' + CAST(@tahun +1 AS VARCHAR(4)) AS DATE) --01/01/2025


if @gudang<>'-Semua'
begin


if @tipe = 0
begin
	--select @tgl='05/01/2025',@tglakhir='05/30/2025',@gudang='GMPL'
	
	
	
	set @bulanPeriode=case when @bulan=1 then 12 else @bulan-1 end
	set @TahunPeriode=case when @bulan=1 then @tahun-1 else @tahun end

	select @jmlCount = COUNT(*) from (

	select A.KODEBRG,Replace(b.NAMABRG,'#','') NAMABRG,A.KODEGDG,c.NAMA + '-'+a.KODEGDG NMgudang,d.BarcodeLoc,d.NamaLokasi +'  - kode : '+ D.BarcodeLoc NamaLokasi, cast(a.SALDOQNT as numeric(18,2)) SALDOQNT  ,
	 b.PartNumber , isnull(e.NAMAMERK,'') NAMAMERK, b.SAT1
	from (

		  SELECT KODEGDG,BARCODE,KODEBRG,SUM(ISNULL(SALDOQNT,0)) SALDOQNT
		  FROM ( select  KODEGDG,BARCODE,KodeBrg, QntAwal SaldoQnt                        
				  from 	dbStockBrgSIG
				  where  Tahun=@TahunPeriode and  Bulan= case when @TahunPeriode=2024 then 2 else 1 End		
				 and KODEGDG=@gudang 	  			  				
				  union all 
				  
				  select KODEGDG,Barcode,KodeBrg, 
				  SUM(ISNULL(QntDb,0))-SUM(ISNULL(QntCr,0)) SaldoQnt				
				  from 	vwKartuStockSIG 
				  where 	year(Tanggal)=@TahunPeriode and month(Tanggal)=@bulanPeriode				  				  
				  and Tipe not in ('AWL') and Kodegdg=@gudang				  			
				  group by KODEGDG,BARCODE,KodeBrg
				  
				  
				  ) A
		GROUP BY A.KODEBRG,KODEGDG,BARCODE
	)A 
	left outer join DBBARANG b on a.KODEBRG=b.KODEBRG
	left outer join DBGUDANG C on A.KODEGDG=c.KODEGDG
	left outer join dbLokasiSIG d on a.BARCODE=d.BarcodeLoc
	left outer join DBMERK e on b.KodeMerk = e.KODEMERK 
	where b.ISAKTIF = 1 and floor(cast(a.SALDOQNT as numeric(18,2))) <> 0  
	AND a.KODEGDG<>'GTC'
	--and A.KODEBRG='11210012240024'
	) a option (recompile) -- 2026-10-27: rencana query dibuat ulang sesuai nilai parameter




SELECT @jmlterhitung = COUNT(*),@jmlsesuai=SUM(jmlsesuai),@selisihkurang=SUM(selisihkurang),@selisihlebih=SUM(selisihlebih)
 FROM (


select 
KODEBRG ,MAX(B.TANGGAL) TGL,	
	case when max(isnull(A.Selisih,0))=0 then 1 else 0 end jmlsesuai,	
case when max(isnull(A.QNTCR,0))>0 then 1 else 0 end selisihkurang,
case when max(isnull(A.QNTDB,0))>0 then 1 else 0 end selisihlebih	
	from DBKOREKSIOPNDET A
	left outer join DBKOREKSIOPN b on a.NOBUKTI=b.NOBUKTI
	where year(b.TANGGAL)=@tahun and MONTH(b.tanggal)=@bulan and a.KODEGDG=@gudang
	GROUP BY A.KODEBRG--,A.Selisih,A.QNTCR,A.QNTDB
) a


	set @jmlCount= case when @jmlCount=0 then 1 else @jmlCount end

	select @jmlCount targetaudit ,@jmlterhitung terhitung,@jmlCount - ISNULL(@jmlterhitung,0) belumterhitung, 
	cast((@jmlterhitung/@jmlCount) * 100.00 as numeric(18,2)) terhitungpersen,
	cast(((@jmlCount - ISNULL(@jmlterhitung,0))/@jmlCount) * 100.00as numeric(18,2)) belumterhitungpersen
	,cast((@jmlterhitung/@jmlCount) * 100.00 as numeric(18,2)) pencapaianaudit
	,@jmlsesuai sesuai,@selisihkurang selisihkurang,@selisihlebih sesuailebih,
	cast((@selisihkurang/@jmlCount) * 100.00 as numeric(18,2)) selisihkurangpersen,
	cast((@selisihlebih/@jmlCount) * 100.00 as numeric(18,2)) sesuailebihpersen
	,cast(((@selisihkurang + @selisihlebih)/@jmlCount) * 100.00  as numeric(18,2)) firstdifferent

end	
if @tipe = 1
begin
	--select @tgl='05/01/2025',@tglakhir='05/30/2025',@gudang='GMPL'

	select @jmlCount = COUNT(*) from (

	select A.KODEBRG,Replace(b.NAMABRG,'#','') NAMABRG,A.KODEGDG,c.NAMA + '-'+a.KODEGDG NMgudang,d.BarcodeLoc,d.NamaLokasi +'  - kode : '+ D.BarcodeLoc NamaLokasi, cast(a.SALDOQNT as numeric(18,2)) SALDOQNT  ,
	 b.PartNumber , isnull(e.NAMAMERK,'') NAMAMERK, b.SAT1
	from (

		  SELECT KODEGDG,BARCODE,KODEBRG,SUM(ISNULL(SALDOQNT,0)) SALDOQNT
		  FROM ( select  KODEGDG,BARCODE,KodeBrg, QntAwal SaldoQnt                        
				  from 	dbStockBrgSIG
				  where  Tahun=@tahun and  Bulan= case when @tahun=2024 then 2 else 1 End		
				 and KODEGDG=@gudang 	
				   			  				
				  union all 
				  
				  select KODEGDG,Barcode,KodeBrg, 
				  SUM(ISNULL(QntDb,0))-SUM(ISNULL(QntCr,0)) SaldoQnt				
				  from 	vwKartuStockSIG 
				  where 	year(Tanggal)=@tahun 
				--  and Tanggal < CASE WHEN @semester=1 THEN @TGLSEMSTER1 ELSE @TGLSEMESTER2 END	  
				  and Tipe not in ('AWL') and Kodegdg=@gudang				  			
				  group by KODEGDG,BARCODE,KodeBrg
				 
				  
				  ) A
		GROUP BY A.KODEBRG,KODEGDG,BARCODE
	)A 
	left outer join DBBARANG b on a.KODEBRG=b.KODEBRG
	left outer join DBGUDANG C on A.KODEGDG=c.KODEGDG
	left outer join dbLokasiSIG d on a.BARCODE=d.BarcodeLoc
	left outer join DBMERK e on b.KodeMerk = e.KODEMERK 
	where b.ISAKTIF = 1 and floor(cast(a.SALDOQNT as numeric(18,2))) <> 0  
	AND a.KODEGDG<>'GTC'
	--and A.KODEBRG='11210012240024'
	) a option (recompile) -- 2026-10-27: rencana query dibuat ulang sesuai nilai parameter

	
SELECT @jmlterhitung = COUNT(*),@jmlsesuai=SUM(jmlsesuai),@selisihkurang=SUM(selisihkurang),@selisihlebih=SUM(selisihlebih)
 FROM (


select 
KODEBRG ,MAX(B.TANGGAL) TGL,	
	case when isnull(A.Selisih,0)=0 then 1 else 0 end jmlsesuai,	
case when isnull(A.QNTCR,0)>0 then 1 else 0 end selisihkurang,
case when isnull(A.QNTDB,0)>0 then 1 else 0 end selisihlebih	
	from DBKOREKSIOPNDET A
	left outer join DBKOREKSIOPN b on a.NOBUKTI=b.NOBUKTI
	where YEAR(TANGGAL)=@tahun and 
	  month(Tanggal) between case when @semester=1 then 1 else 5 end and case when @semester=1 then 5 else 12 end 	
	  AND a.KODEGDG=@gudang
	GROUP BY A.KODEBRG,A.Selisih,A.QNTCR,A.QNTDB
) a
  
	  
	  
	  
	  
	  
	  
	  



	set @jmlCount= case when @jmlCount=0 then 1 else @jmlCount end

	select @jmlCount targetaudit ,@jmlterhitung terhitung,@jmlCount - ISNULL(@jmlterhitung,0) belumterhitung, 
	cast((@jmlterhitung/@jmlCount) * 100.00 as numeric(18,2)) terhitungpersen,
	cast(((@jmlCount - ISNULL(@jmlterhitung,0))/@jmlCount) * 100.00as numeric(18,2)) belumterhitungpersen
	,cast((@jmlterhitung/@jmlCount) * 100.00 as numeric(18,2)) pencapaianaudit
	,@jmlsesuai sesuai,@selisihkurang selisihkurang,@selisihlebih sesuailebih,
	cast((@selisihkurang/@jmlCount) * 100.00 as numeric(18,2)) selisihkurangpersen,
	cast((@selisihlebih/@jmlCount) * 100.00 as numeric(18,2)) sesuailebihpersen
	,cast(((@selisihkurang + @selisihlebih)/@jmlCount) * 100.00  as numeric(18,2)) firstdifferent

end	
		
if @tipe = 2 
begin
	select @jmlCount = COUNT(*) from (

	select A.KODEBRG,Replace(b.NAMABRG,'#','') NAMABRG,A.KODEGDG,c.NAMA + '-'+a.KODEGDG NMgudang,d.BarcodeLoc,d.NamaLokasi +'  - kode : '+ D.BarcodeLoc NamaLokasi, cast(a.SALDOQNT as numeric(18,2)) SALDOQNT  ,
	 b.PartNumber , isnull(e.NAMAMERK,'') NAMAMERK, b.SAT1
	from (

		  SELECT KODEGDG,BARCODE,KODEBRG,SUM(ISNULL(SALDOQNT,0)) SALDOQNT
		  FROM ( select  KODEGDG,BARCODE,KodeBrg, QntAwal SaldoQnt                        
				  from 	dbStockBrgSIG
				  where  Tahun=@tahun and  Bulan= case when @tahun=2024 then 2 else 1 End		
				 and KODEGDG=@gudang 	  			  				
				  union all 
				  
				  select KODEGDG,Barcode,KodeBrg, 
				  SUM(ISNULL(QntDb,0))-SUM(ISNULL(QntCr,0)) SaldoQnt				
				  from 	vwKartuStockSIG 
				  where 	year(Tanggal)=@tahun	--AND Tanggal <@TGLSEMSTER1	
				  and Tanggal < CASE WHEN @semester=1 THEN @TGLSEMSTER1 ELSE @TGLSEMESTER2 END	  				  
				  and Tipe not in ('AWL') and Kodegdg=@gudang				  			
				  group by KODEGDG,BARCODE,KodeBrg
				  
				  
				  ) A
		GROUP BY A.KODEBRG,KODEGDG,BARCODE
	)A 
	left outer join DBBARANG b on a.KODEBRG=b.KODEBRG
	left outer join DBGUDANG C on A.KODEGDG=c.KODEGDG
	left outer join dbLokasiSIG d on a.BARCODE=d.BarcodeLoc
	left outer join DBMERK e on b.KodeMerk = e.KODEMERK 
	where b.ISAKTIF = 1 and floor(cast(a.SALDOQNT as numeric(18,2))) <> 0  
	AND a.KODEGDG<>'GTC'
	--and A.KODEBRG='11210012240024'
	) a option (recompile) -- 2026-10-27: rencana query dibuat ulang sesuai nilai parameter


	
	
SELECT @jmlterhitung = COUNT(*),@jmlsesuai=SUM(jmlsesuai),@selisihkurang=SUM(selisihkurang),@selisihlebih=SUM(selisihlebih)
 FROM (


select 
KODEBRG ,MAX(B.TANGGAL) TGL,	
	case when isnull(A.Selisih,0)=0 then 1 else 0 end jmlsesuai,	
case when isnull(A.QNTCR,0)>0 then 1 else 0 end selisihkurang,
case when isnull(A.QNTDB,0)>0 then 1 else 0 end selisihlebih	
	from DBKOREKSIOPNDET A
	left outer join DBKOREKSIOPN b on a.NOBUKTI=b.NOBUKTI
	where YEAR(TANGGAL)=@tahun AND a.KODEGDG=@gudang
	GROUP BY A.KODEBRG,A.Selisih,A.QNTCR,A.QNTDB
) a



	set @jmlCount= case when @jmlCount=0 then 1 else @jmlCount end

	select @jmlCount targetaudit ,@jmlterhitung terhitung,@jmlCount - ISNULL(@jmlterhitung,0) belumterhitung, 
	cast((@jmlterhitung/@jmlCount) * 100.00 as numeric(18,2)) terhitungpersen,
	cast(((@jmlCount - ISNULL(@jmlterhitung,0))/@jmlCount) * 100.00as numeric(18,2)) belumterhitungpersen
	,cast((@jmlterhitung/@jmlCount) * 100.00 as numeric(18,2)) pencapaianaudit
	,@jmlsesuai sesuai,@selisihkurang selisihkurang,@selisihlebih sesuailebih,
	cast((@selisihkurang/@jmlCount) * 100.00 as numeric(18,2)) selisihkurangpersen,
	cast((@selisihlebih/@jmlCount) * 100.00 as numeric(18,2)) sesuailebihpersen
	,cast(((@selisihkurang + @selisihlebih)/@jmlCount) * 100.00  as numeric(18,2)) firstdifferent
	
	
end	

end

else ---Semua Gudang
begin


if @tipe = 0
begin
	--select @tgl='05/01/2025',@tglakhir='05/30/2025',@gudang='GMPL'
	
	
	
	set @bulanPeriode=case when @bulan=1 then 12 else @bulan-1 end
	set @TahunPeriode=case when @bulan=1 then @tahun-1 else @tahun end

	select @jmlCount = COUNT(*) from (

	select A.KODEBRG,Replace(b.NAMABRG,'#','') NAMABRG,A.KODEGDG,c.NAMA + '-'+a.KODEGDG NMgudang,d.BarcodeLoc,d.NamaLokasi +'  - kode : '+ D.BarcodeLoc NamaLokasi, cast(a.SALDOQNT as numeric(18,2)) SALDOQNT  ,
	 b.PartNumber , isnull(e.NAMAMERK,'') NAMAMERK, b.SAT1
	from (

		  SELECT KODEGDG,BARCODE,KODEBRG,SUM(ISNULL(SALDOQNT,0)) SALDOQNT
		  FROM ( select  KODEGDG,BARCODE,KodeBrg, QntAwal SaldoQnt                        
				  from 	dbStockBrgSIG
				  where  Tahun=@TahunPeriode and  Bulan= case when @TahunPeriode=2024 then 2 else 1 End		
				-- and KODEGDG=@gudang 	  			  				
				  union all 
				  
				  select KODEGDG,Barcode,KodeBrg, 
				  SUM(ISNULL(QntDb,0))-SUM(ISNULL(QntCr,0)) SaldoQnt				
				  from 	vwKartuStockSIG 
				  where 	year(Tanggal)=@TahunPeriode and month(Tanggal)=@bulanPeriode				  				  
				  and Tipe not in ('AWL') --and Kodegdg=@gudang				  			
				  group by KODEGDG,BARCODE,KodeBrg
				  
				  
				  ) A
		GROUP BY A.KODEBRG,KODEGDG,BARCODE
	)A 
	left outer join DBBARANG b on a.KODEBRG=b.KODEBRG
	left outer join DBGUDANG C on A.KODEGDG=c.KODEGDG
	left outer join dbLokasiSIG d on a.BARCODE=d.BarcodeLoc
	left outer join DBMERK e on b.KodeMerk = e.KODEMERK 
	where b.ISAKTIF = 1 and floor(cast(a.SALDOQNT as numeric(18,2))) <> 0  
	AND a.KODEGDG<>'GTC'
	--and A.KODEBRG='11210012240024'
	) a option (recompile) -- 2026-10-27: rencana query dibuat ulang sesuai nilai parameter




SELECT @jmlterhitung = COUNT(*),@jmlsesuai=SUM(jmlsesuai),@selisihkurang=SUM(selisihkurang),@selisihlebih=SUM(selisihlebih)
 FROM (


select 
KODEBRG ,MAX(B.TANGGAL) TGL,	
	case when max(isnull(A.Selisih,0))=0 then 1 else 0 end jmlsesuai,	
case when max(isnull(A.QNTCR,0))>0 then 1 else 0 end selisihkurang,
case when max(isnull(A.QNTDB,0))>0 then 1 else 0 end selisihlebih	
	from DBKOREKSIOPNDET A
	left outer join DBKOREKSIOPN b on a.NOBUKTI=b.NOBUKTI
	where year(b.TANGGAL)=@tahun and MONTH(b.tanggal)=@bulan --and a.KODEGDG=@gudang
	GROUP BY A.KODEBRG--,A.Selisih,A.QNTCR,A.QNTDB
) a


	set @jmlCount= case when @jmlCount=0 then 1 else @jmlCount end

	select @jmlCount targetaudit ,@jmlterhitung terhitung,@jmlCount - ISNULL(@jmlterhitung,0) belumterhitung, 
	cast((@jmlterhitung/@jmlCount) * 100.00 as numeric(18,2)) terhitungpersen,
	cast(((@jmlCount - ISNULL(@jmlterhitung,0))/@jmlCount) * 100.00as numeric(18,2)) belumterhitungpersen
	,cast((@jmlterhitung/@jmlCount) * 100.00 as numeric(18,2)) pencapaianaudit
	,@jmlsesuai sesuai,@selisihkurang selisihkurang,@selisihlebih sesuailebih,
	cast((@selisihkurang/@jmlCount) * 100.00 as numeric(18,2)) selisihkurangpersen,
	cast((@selisihlebih/@jmlCount) * 100.00 as numeric(18,2)) sesuailebihpersen
	,cast(((@selisihkurang + @selisihlebih)/@jmlCount) * 100.00  as numeric(18,2)) firstdifferent

end	
if @tipe = 1
begin
	--select @tgl='05/01/2025',@tglakhir='05/30/2025',@gudang='GMPL'

	select @jmlCount = COUNT(*) from (

	select A.KODEBRG,Replace(b.NAMABRG,'#','') NAMABRG,A.KODEGDG,c.NAMA + '-'+a.KODEGDG NMgudang,d.BarcodeLoc,d.NamaLokasi +'  - kode : '+ D.BarcodeLoc NamaLokasi, cast(a.SALDOQNT as numeric(18,2)) SALDOQNT  ,
	 b.PartNumber , isnull(e.NAMAMERK,'') NAMAMERK, b.SAT1
	from (

		  SELECT KODEGDG,BARCODE,KODEBRG,SUM(ISNULL(SALDOQNT,0)) SALDOQNT
		  FROM ( select  KODEGDG,BARCODE,KodeBrg, QntAwal SaldoQnt                        
				  from 	dbStockBrgSIG
				  where  Tahun=@tahun and  Bulan= case when @tahun=2024 then 2 else 1 End		
				-- and KODEGDG=@gudang 	
				   			  				
				  union all 
				  
				  select KODEGDG,Barcode,KodeBrg, 
				  SUM(ISNULL(QntDb,0))-SUM(ISNULL(QntCr,0)) SaldoQnt				
				  from 	vwKartuStockSIG 
				  where 	year(Tanggal)=@tahun 
				--  and Tanggal < CASE WHEN @semester=1 THEN @TGLSEMSTER1 ELSE @TGLSEMESTER2 END	  
				  and Tipe not in ('AWL') --and Kodegdg=@gudang				  			
				  group by KODEGDG,BARCODE,KodeBrg
				 
				  
				  ) A
		GROUP BY A.KODEBRG,KODEGDG,BARCODE
	)A 
	left outer join DBBARANG b on a.KODEBRG=b.KODEBRG
	left outer join DBGUDANG C on A.KODEGDG=c.KODEGDG
	left outer join dbLokasiSIG d on a.BARCODE=d.BarcodeLoc
	left outer join DBMERK e on b.KodeMerk = e.KODEMERK 
	where b.ISAKTIF = 1 and floor(cast(a.SALDOQNT as numeric(18,2))) <> 0  
	AND a.KODEGDG<>'GTC'
	--and A.KODEBRG='11210012240024'
	) a option (recompile) -- 2026-10-27: rencana query dibuat ulang sesuai nilai parameter

	
SELECT @jmlterhitung = COUNT(*),@jmlsesuai=SUM(jmlsesuai),@selisihkurang=SUM(selisihkurang),@selisihlebih=SUM(selisihlebih)
 FROM (


select 
KODEBRG ,MAX(B.TANGGAL) TGL,	
	case when isnull(A.Selisih,0)=0 then 1 else 0 end jmlsesuai,	
case when isnull(A.QNTCR,0)>0 then 1 else 0 end selisihkurang,
case when isnull(A.QNTDB,0)>0 then 1 else 0 end selisihlebih	
	from DBKOREKSIOPNDET A
	left outer join DBKOREKSIOPN b on a.NOBUKTI=b.NOBUKTI
	where YEAR(TANGGAL)=@tahun and 
	  month(Tanggal) between case when @semester=1 then 1 else 5 end and case when @semester=1 then 5 else 12 end 	
	  --AND a.KODEGDG=@gudang
	GROUP BY A.KODEBRG,A.Selisih,A.QNTCR,A.QNTDB
) a
  
	  
	  
	  
	  
	  
	  
	  



	set @jmlCount= case when @jmlCount=0 then 1 else @jmlCount end

	select @jmlCount targetaudit ,@jmlterhitung terhitung,@jmlCount - ISNULL(@jmlterhitung,0) belumterhitung, 
	cast((@jmlterhitung/@jmlCount) * 100.00 as numeric(18,2)) terhitungpersen,
	cast(((@jmlCount - ISNULL(@jmlterhitung,0))/@jmlCount) * 100.00as numeric(18,2)) belumterhitungpersen
	,cast((@jmlterhitung/@jmlCount) * 100.00 as numeric(18,2)) pencapaianaudit
	,@jmlsesuai sesuai,@selisihkurang selisihkurang,@selisihlebih sesuailebih,
	cast((@selisihkurang/@jmlCount) * 100.00 as numeric(18,2)) selisihkurangpersen,
	cast((@selisihlebih/@jmlCount) * 100.00 as numeric(18,2)) sesuailebihpersen
	,cast(((@selisihkurang + @selisihlebih)/@jmlCount) * 100.00  as numeric(18,2)) firstdifferent

end	
		
if @tipe = 2 
begin
	select @jmlCount = COUNT(*) from (

	select A.KODEBRG,Replace(b.NAMABRG,'#','') NAMABRG,A.KODEGDG,c.NAMA + '-'+a.KODEGDG NMgudang,d.BarcodeLoc,d.NamaLokasi +'  - kode : '+ D.BarcodeLoc NamaLokasi, cast(a.SALDOQNT as numeric(18,2)) SALDOQNT  ,
	 b.PartNumber , isnull(e.NAMAMERK,'') NAMAMERK, b.SAT1
	from (

		  SELECT KODEGDG,BARCODE,KODEBRG,SUM(ISNULL(SALDOQNT,0)) SALDOQNT
		  FROM ( select  KODEGDG,BARCODE,KodeBrg, QntAwal SaldoQnt                        
				  from 	dbStockBrgSIG
				  where  Tahun=@tahun and  Bulan= case when @tahun=2024 then 2 else 1 End		
				-- and KODEGDG=@gudang 	  			  				
				  union all 
				  
				  select KODEGDG,Barcode,KodeBrg, 
				  SUM(ISNULL(QntDb,0))-SUM(ISNULL(QntCr,0)) SaldoQnt				
				  from 	vwKartuStockSIG 
				  where 	year(Tanggal)=@tahun	--AND Tanggal <@TGLSEMSTER1	
				  and Tanggal < CASE WHEN @semester=1 THEN @TGLSEMSTER1 ELSE @TGLSEMESTER2 END	  				  
				  and Tipe not in ('AWL') --and Kodegdg=@gudang				  			
				  group by KODEGDG,BARCODE,KodeBrg
				  
				  
				  ) A
		GROUP BY A.KODEBRG,KODEGDG,BARCODE
	)A 
	left outer join DBBARANG b on a.KODEBRG=b.KODEBRG
	left outer join DBGUDANG C on A.KODEGDG=c.KODEGDG
	left outer join dbLokasiSIG d on a.BARCODE=d.BarcodeLoc
	left outer join DBMERK e on b.KodeMerk = e.KODEMERK 
	where b.ISAKTIF = 1 and floor(cast(a.SALDOQNT as numeric(18,2))) <> 0  
	AND a.KODEGDG<>'GTC'
	--and A.KODEBRG='11210012240024'
	) a option (recompile) -- 2026-10-27: rencana query dibuat ulang sesuai nilai parameter


	
	
SELECT @jmlterhitung = COUNT(*),@jmlsesuai=SUM(jmlsesuai),@selisihkurang=SUM(selisihkurang),@selisihlebih=SUM(selisihlebih)
 FROM (


select 
KODEBRG ,MAX(B.TANGGAL) TGL,	
	case when isnull(A.Selisih,0)=0 then 1 else 0 end jmlsesuai,	
case when isnull(A.QNTCR,0)>0 then 1 else 0 end selisihkurang,
case when isnull(A.QNTDB,0)>0 then 1 else 0 end selisihlebih	
	from DBKOREKSIOPNDET A
	left outer join DBKOREKSIOPN b on a.NOBUKTI=b.NOBUKTI
	where YEAR(TANGGAL)=@tahun --AND a.KODEGDG=@gudang
	GROUP BY A.KODEBRG,A.Selisih,A.QNTCR,A.QNTDB
) a



	set @jmlCount= case when @jmlCount=0 then 1 else @jmlCount end

	select @jmlCount targetaudit ,@jmlterhitung terhitung,@jmlCount - ISNULL(@jmlterhitung,0) belumterhitung, 
	cast((@jmlterhitung/@jmlCount) * 100.00 as numeric(18,2)) terhitungpersen,
	cast(((@jmlCount - ISNULL(@jmlterhitung,0))/@jmlCount) * 100.00as numeric(18,2)) belumterhitungpersen
	,cast((@jmlterhitung/@jmlCount) * 100.00 as numeric(18,2)) pencapaianaudit
	,@jmlsesuai sesuai,@selisihkurang selisihkurang,@selisihlebih sesuailebih,
	cast((@selisihkurang/@jmlCount) * 100.00 as numeric(18,2)) selisihkurangpersen,
	cast((@selisihlebih/@jmlCount) * 100.00 as numeric(18,2)) sesuailebihpersen
	,cast(((@selisihkurang + @selisihlebih)/@jmlCount) * 100.00  as numeric(18,2)) firstdifferent
	
	
end	


End
