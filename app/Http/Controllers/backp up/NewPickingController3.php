<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\NewMenu;
use App\Model\NewAksesMenu;
use App\Model\NewPeriode;
use App\Model\NewUsers;
use Illuminate\Support\Facades\DB;
use App\Model\VwPersiapan;
use App\Model\VWOUTPERSIAPANBRG;
use App\Model\VWOUTPENERIMAANBRGWEB;


class NewPickingController extends Controller
{





  public function index () {

    $kodemenu = '022';
    $akses = NewAksesMenu::where('USERID', \Auth::id())-> where('L1', $kodemenu)->first();
    if(!$akses || !$akses->HASACCESS) {
       return redirect('/home');
    }

    $periode = NewPeriode::where('user_id' , \Auth::id())->first();
    $menul0 = app('App\Http\Controllers\NewMenuController')->getMenuL0();


    $penerimaan = VwPersiapan::all()->where('bulan',$periode->bulan )->where('tahun', $periode->tahun)->sortBy('urut')->groupBy('NOBUKTI');
    $tempPenerimaan = [];
    foreach ($penerimaan as $p) {
      // code...
      array_push($tempPenerimaan, $p);
    }


    return view('newpicking' , [
      "periode" => $periode,
      "menul0" => $menul0,
      "penerimaanArray" => $tempPenerimaan
    ]);
  }

  public function loadAll () {
    $periode = NewPeriode::where('user_id' , \Auth::id())->first();
    $penerimaan = VwPersiapan::all()->where('bulan',$periode->bulan )->where('tahun', $periode->tahun)->sortBy('urut')->groupBy('NOBUKTI');
    $tempPenerimaan = [];
    foreach ($penerimaan as $p) {
      // code...
      array_push($tempPenerimaan, $p);
    }

    return [
      "penerimaanArray" => $tempPenerimaan
    ];
  }
  // kode3 ambil qnt dari ob g bisa diganti, yang lama qntsisa bisa diganti
  public function spAdd (Request $req) {
    // return 'tes';

    $nobukti = $req->input('nobukti');
    $nobarang = $req->input('nobarang');
    $barcodelokasi = $req->input('barcodelokasi');
    $qntTerima = $req->input('qntTerima');
    $urut = $req->input('urut');


    $values = [
      $nobukti, $nobarang, $barcodelokasi, $qntTerima, $urut
    ];

      DB::connection("SML")->statement('exec Sp_TransPersiapan ?,?,?,?,?',$values);
      return 1;

  }

  public function spAddLPB (Request $req) {
    // return 'tes';

    $nobukti = $req->input('nobukti');
    $urut = $req->input('urut');
    $nobarang = $req->input('nobarang');
    $nobuktiLPB = $req->input('nobuktiLPB');
    $urutLPB = $req->input('urutLPB');
    $nobarangLPB = $req->input('nobarangLPB');
    $barcodelokasi = $req->input('barcodelokasi');
    $qntTerima = $req->input('qntTerima');


    $values = [
      $nobukti, $nobarang, $barcodelokasi, $qntTerima, $urut, $nobuktiLPB, $urutLPB
    ];

      DB::connection("SML")->statement('exec Sp_TransPersiapan ?,?,?,?,?,?,?',$values);
      return 1;

  }




    public function spKoreksi (Request $req) {
      $values = [
        $req->input('choice'),
        $req->input('nobukti'),
        $req->input('urut'),
        $req->input('kodeBrg'),
        $req->input('inputQnt')
      ];

      DB::connection('SML')->statement('exec Sp_updPersiapan ?,?,?,?,?', $values);
      return 1;
    }

  public function spCheckAdd (Request $req) {

    $nobukti = $req->input('nobukti');
    $nobarang = $req->input('nobarang');
    $urut = $req->input('urut');
    $periode = NewPeriode::where('user_id' , \Auth::id())->first();

    $check = VWOUTPERSIAPANBRG::all()->where('Bulan',$periode->bulan )->where('Tahun', $periode->tahun)->where('NoBukti',$nobukti )->where('Kodebrg', $nobarang)->where('Urut',$urut );


    $tempCheck= [];
    foreach ($check as $p) {
      // code...
      array_push($tempCheck, $p);
    }
    $kodegdg = $tempCheck[0]->KodeGdgSO;

    if (str_contains($nobukti, 'TFL')) {
  $check1 = DB::connection('SML')->select('select TOP 1 kodelokasi from DBOutBrgDET where NOBUKTI = :nobukti and KODEBRG = :nobarang', ["nobukti" => $nobukti,"nobarang" => $nobarang]);
  $lokasi = [
    ["BarcodeLoc" => $check1[0]->kodelokasi,
    "SisaKapasitas" => 0 ,
    "StockLokasi" => 0,
    "qntMax" => 0

   ]
  ];
} else {

  $lokasi =  DB::connection('SML')->select("

 declare @Kodegdg Varchar(20)
select @Kodegdg= :kodegdg
set @Kodegdg=ISNULL(@Kodegdg,'')

  select A.BarcodeLoc,A.qntMax,
isnull(b.SALDOQNT,0) StockLokasi ,A.qntMax - isnull(B.SALDOQNT,0) SisaKapasitas
,c.KodeGdg
  from dbBarangLokasiSIG A
  left outer join dbLokasiSIG C on A.BarcodeLoc=c.BarcodeLoc
 left outer join (select  KODEBRG,BARCODE,SUM(SaldoQnt) SaldoQnt,KODEGDG
				from (
					select  KodeBrg,barcode ,QntAwal SaldoQnt ,KODEGDG
					from 	DBSTOCKBRGSIG
					where         Tahun=year(GETDATE()) and Bulan=MONTH(GETDATE())

					union all

					select 	KodeBrg,Barcode ,
					SUM(QntDb)-SUM(QntCr) SaldoQnt,Kodegdg
					from 	vwKartuStockSIG
					where 	year(Tanggal)=year(GETDATE()) and month(tanggal)=MONTH(GETDATE()) and Tanggal<=GETDATE()
					and Tipe not in ('AWL')
					and KodeGdg <>'GTC'

					group by KodeBrg,Barcode,Kodegdg

					) A


				group by KODEBRG,BARCODE,KODEGDG
				having SUM(SaldoQnt) >0
 ) B on A.KodeBarang=B.KODEBRG and A.BarcodeLoc=B.BARCODE and C.KodeGdg=b.KODEGDG
  where A.KodeBarang= :nobarang
  and B.SALDOQNT>0 and C.KODEGDG <>'GTC'
  and C.KodeGdg like case when @Kodegdg='' then '%%' else @Kodegdg End
  ", ["nobarang" => $nobarang,"kodegdg" => $kodegdg]);
}

      return ["check" => $tempCheck ,"lokasi" => $lokasi];

  }

  public function spCheckAddLPB (Request $req) {

    $nobukti = $req->input('nobukti');
    $nobarang = $req->input('nobarang');
    $urut = $req->input('urut');
    $periode = NewPeriode::where('user_id' , \Auth::id())->first();

    $check = VWOUTPERSIAPANBRG::all()->where('Bulan',$periode->bulan )->where('Tahun', $periode->tahun)->where('NoBukti',$nobukti )->where('Kodebrg', $nobarang)->where('Urut',$urut );

    $tempCheck= [];
    foreach ($check as $p) {
      // code...
      array_push($tempCheck, $p);
    }

    $nobuktiLPB = $req->input('nobuktiLPB');
    $nobarangLPB = $req->input('nobarangLPB');
    $urutLPB = $req->input('urutLPB');
      // ->orWhere('trans', 'UKO')->get()
      $checkLPB = VWOUTPENERIMAANBRGWEB::all()->where('NoBukti1',$nobuktiLPB )->where('Kodebrg', $nobarangLPB)->where('Urut', $urutLPB)->whereIn('trans', ['UKI' , 'PBL' , 'RSPB']);
      $tempCheckLPB = [];
      foreach ($checkLPB as $p) {
        // code...
        array_push($tempCheckLPB, $p);
      }

      return ["check" => $tempCheck , "checkLPB" => $tempCheckLPB];

  }

  public function getDetailPenerimaan (Request $req) {
    $detailPenerimaan = VwPersiapan::all()->where('NOBUKTI',$req->input('NOBUKTI') )->sortBy('KODEBRG');
    $tempPenerimaan = [];
    foreach ($detailPenerimaan as $p) {
      // code...
      array_push($tempPenerimaan, $p);
    }
    return $tempPenerimaan;
  }





}
