<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Model\NewMenu;
use App\Model\NewAksesMenu;
use App\Model\NewPeriode;
use App\Model\NewUsers;
use Illuminate\Support\Facades\DB;
use App\Model\VWOUTPENERIMAANBRGWEB;
use App\Model\vwShowAlokasiBrg;



class TransferLokasiSiteController extends Controller
{




  public function index () {

    $kodemenu = '036';
    $akses = NewAksesMenu::where('USERID', \Auth::id())-> where('L1', $kodemenu)->first();
    if(!$akses || !$akses->HASACCESS) {
       return redirect('/home');
    }


    $periode = NewPeriode::where('user_id' , \Auth::id())->first();
    $menul0 = app('App\Http\Controllers\NewMenuController')->getMenuL0();


    $penerimaan = vwShowAlokasiBrg::all()->where('Bulan',$periode->bulan )->where('Tahun', $periode->tahun)->sortBy('urut')->groupBy('NOBUKTI');
    $tempPenerimaan = [];
    foreach ($penerimaan as $p) {
      // code...
      array_push($tempPenerimaan, $p);
    }


    return view('transferlokasisite' , [
      "periode" => $periode,
      "menul0" => $menul0,
      "penerimaanArray" => $tempPenerimaan
    ]);
  }

  public function getNoBukti (Request $req) {

    $periode = NewPeriode::where('user_id' , \Auth::id())->first();
    // $inisial = DB::connection("SML")->select('select PBL from DBNOMOR');
    $inisial = DB::connection("SML")->select('select RLB from DBNOMOR');
    $username = \Auth::user()->username;
    // return [$periode->bulan,$inisial[0]->PBL,$username];
    $values = [
        $inisial[0]->RLB,
        $periode->bulan,
        $periode->tahun,
        $username
    ];
    $noBukti = DB::connection('SML')->select('exec SP_IsiNobukti ?,?,?,?',$values);
    return $noBukti;
  }


  public function cekLokasiAwal (Request $req) {
      $check =  DB::connection('SML')->select( 'select * from dblokasisig where BarcodeLoc = :kodelokasi' , ["kodelokasi" => $req->kodelokasi]);
      // return $check;
      // return 'asd';
      // return count($check);
      if (!$check) {
        // return 'asd';
        return 2;
      }
      // return '444';
      return DB::connection('SML')->select("select b.NAMABRG, b.KODEBRG, b.SAT1, b.SAT2, b.SAT3, b.ISI1, b.ISI2, b.ISI3 from dbBarangLokasiSIG a join dbbarang b on a.KodeBarang = b.KODEBRG where a.BarcodeLoc = :barcodeloc and b.ISAKTIF = 1 and b.KODEGRP <> 'JS'

        UNION all

        select b.NAMABRG, b.KODEBRG, b.SAT1, b.SAT2, b.SAT3, b.ISI1, b.ISI2, b.ISI3 
        from dbbarang B
        left outer join dbgudang A on 1=1 and A.KODEGDG<>'GTC' and a.KODEGDG+'LREC'= :barcodeloc1
       where b.ISAKTIF = 1 and b.KODEGRP <> 'JS' 


        ", ["barcodeloc" => $req->kodelokasi,"barcodeloc1" => $req->kodelokasi] );
      // select * from dbBarangLokasiSIG where BarcodeLoc = 'GSML2101'
  }

  public function cekLokasiTujuan (Request $req) {
      return DB::connection('SML')->select( 'select * from dbBarangLokasiSIG where KodeBarang = :kodebarang' , ["kodebarang" => $req->kodebarang]);
  }

  public function cekKodeGdg (Request $req) {
    $lokasiawal =  DB::connection('SML')->select( 'select * from dblokasisig where BarcodeLoc = :kodelokasi' , ["kodelokasi" => $req->kodelokasiawal]);
    $lokasitujuan =  DB::connection('SML')->select( 'select * from dblokasisig where BarcodeLoc = :kodelokasi' , ["kodelokasi" => $req->kodelokasitujuan]);
    return [
      "lokasiawal" => $lokasiawal[0],
      "lokasitujuan" => $lokasitujuan[0]
    ];
  }

  public function loadAll () {
    $periode = NewPeriode::where('user_id' , \Auth::id())->first();
    $penerimaan = vwShowAlokasiBrg::all()->where('Bulan',$periode->bulan )->where('Tahun', $periode->tahun)->sortBy('urut')->groupBy('NOBUKTI');
    $tempPenerimaan = [];
    foreach ($penerimaan as $p) {
      // code...
      array_push($tempPenerimaan, $p);
    }

    return [
      "penerimaanArray" => $tempPenerimaan
    ];
  }

  public function spAdd (Request $req) {
    // return date("Y-m-d");
    $username = \Auth::user()->username;
    $tanggal = date("Y-m-d");
    $choice = $req->input('choice');

    $nobukti = $req->input('nobukti');

    if ($choice == 'I') {
      // code...
      $check = DB::connection('SML')->select('select * from DBTRANSFER where NOBUKTI = :nobukti',["nobukti" => $nobukti]);
      if ($check) {
        return 2;
      }
    }


    // $nobukti = $req->input('nobukti');
    // $nobarang = $req->input('nobarang');
    // $barcodelokasi = $req->input('barcodelokasi');
    // $urut = $req->input('urut');
    // $qntShow = $req->input('qntShow');

    // $urut = 0;
    // 'SML/OPN/00002/0123'
    $values = [
      $choice,
      $nobukti,
      $req->input('nourut'),
      $tanggal,
      $req->input('note'),
      $req->input('urut'),
      $req->input('kodebarang'),
      $req->input('kodegdgasal'),
      $req->input('kodegdgtujuan'),
      $req->input('sat1'),
      $req->input('sat2'),
      $req->input('qnt1'),
      $req->input('qnt2'),
      $req->input('nosat'),
      $req->input('isi'),
      $username,
      '',
      '',
      '',
      $req->input('qnt2'),
      $req->input('pTerima'),
      $req->input('lokasiasal'),
      $req->input('lokasitujuan'),

    ];


      DB::connection("SML")->statement('exec SP_TRANSLOKASI ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?',$values);
      return 1;

  }

  public function spCheckKode1 (Request $req) {

    $check = DB::connection('SML')->select( 'select * from dbLokasiSIG where BarcodeLoc = :barcodeloc' , ["barcodeloc" => $barcodeloc]);
    return $check;
  }

  public function spCheckAdd (Request $req) {

    $nobukti = $req->input('nobukti');
    $nobarang = $req->input('nobarang');
    $urut = $req->input('urut');
    // $barcodelokasi = $req->input('barcodelokasi');
    $periode = NewPeriode::where('user_id' , \Auth::id())->first();

    // $urut = 0;
    // 'SML/OPN/00002/0123'
    // $values = [
    //   $nobukti, $nobarang, $barcodelokasi
    // ];
    // $gudang = DB::connection("SML")->select('select * from DBGUDANG where KODEGDG <> :id', ['id' => 'GTC']);

      // $check = DB::connection("SML")->select('select * from dbBeli where nobukti = :nobukti AND month(tanggal) = :bulan AND year(tanggal) = :tahun',['nobukti' => $nobukti, 'bulan'=> $periode->bulan, 'tahun'=>$periode->tahun]);
      $check = VWOUTPENERIMAANBRGWEB::all()->where('Bulan',$periode->bulan )->where('Tahun', $periode->tahun)->where('NoBukti1',$nobukti )->where('Kodebrg', $nobarang)->where('Urut', $urut);
      $tempCheck = [];
      foreach ($check as $p) {
        // code...
        array_push($tempCheck, $p);
      }


      if (str_contains($nobukti, 'TLO')) {

        $barcodeLoc = DB::connection('SML')->select( 'select kodelokasi from dbtransferdet where NOBUKTI = :nobukti' , ["nobukti" => $nobukti]);
        $lokasi =  DB::connection('SML')->select('select A.BarcodeLoc,A.qntMax,isnull(b.SALDOQNT,0) StockLokasi,A.qntMax - isnull(B.SALDOQNT,0) SisaKapasitas
  from dbBarangLokasiSIG A
  left outer join DBSTOCKBRGSIG B on A.KodeBarang=B.KODEBRG and A.BarcodeLoc=B.BARCODE AND B.BULAN= :bulan AND B.TAHUN= :tahun
  where A.KodeBarang= :nobarang and BarcodeLoc =:barcodeloc', ["nobarang" => $nobarang, "bulan" => $periode->bulan , "tahun" => $periode->tahun, "barcodeloc" => $barcodeLoc[0]->kodelokasi]);
      } else {
        $lokasi =  DB::connection('SML')->select('select A.BarcodeLoc,A.qntMax,isnull(b.SALDOQNT,0) StockLokasi,A.qntMax - isnull(B.SALDOQNT,0) SisaKapasitas
  from dbBarangLokasiSIG A
  left outer join DBSTOCKBRGSIG B on A.KodeBarang=B.KODEBRG and A.BarcodeLoc=B.BARCODE AND B.BULAN= :bulan AND B.TAHUN= :tahun
  where A.KodeBarang= :nobarang

  union all

   
  select A.KODEGDG+"LREC" loc ,10000 QNTMAX,isnull(b.SALDOQNT,0) StockLokasi,10000 - isnull(B.SALDOQNT,0) SisaKapasitas
  from DBGUDANG A
  left outer join DBSTOCKBRGSIG B on A.KODEGDG=B.KODEGDG AND  B.BULAN= :bulan1 AND B.TAHUN= :tahun1 AND B.KODEBRG=:nobarang1 and b.kodegdg<>"GTC"
  
  ', ["nobarang" => $nobarang, "bulan" => $periode->bulan , "tahun" => $periode->tahun, "nobarang1" => $nobarang, "bulan1" => $periode->bulan , "tahun1" => $periode->tahun]);
      }



      return ["check" => $tempCheck ,"lokasi" => $lokasi];

  }



  public function spKoreksi (Request $req) {
    $values = [
      $req->input('choice'),
      $req->input('nobukti'),
      $req->input('urut'),
      $req->input('kodeBrg'),
      $req->input('inputQnt')
    ];

    DB::connection('SML')->statement('exec Sp_updAlokasibrgdet ?,?,?,?,?', $values);
    return 1;
  }


    public function getDetailPenerimaan (Request $req) {
      $detailPenerimaan = vwShowAlokasiBrg::all()->where('NOBUKTI',$req->input('NOBUKTI') )->sortBy('URUT');
      $tempPenerimaan = [];
      foreach ($detailPenerimaan as $p) {
        // code...
        array_push($tempPenerimaan, $p);
      }
      return $tempPenerimaan;
    }

}
