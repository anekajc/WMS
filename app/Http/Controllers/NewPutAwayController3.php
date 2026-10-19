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


class NewPutAwayController extends Controller
{




  public function index () {

    $kodemenu = '019';
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


    return view('newputaway' , [
      "periode" => $periode,
      "menul0" => $menul0,
      "penerimaanArray" => $tempPenerimaan
    ]);
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

    $nobukti = $req->input('nobukti');
    $nobarang = $req->input('nobarang');
    $barcodelokasi = $req->input('barcodelokasi');
    $urut = $req->input('urut');
    $qntShow = $req->input('qntShow');

    // $urut = 0;
    // 'SML/OPN/00002/0123'
    $values = [
      $nobukti, $nobarang, $barcodelokasi, $urut, $qntShow
    ];

      DB::connection("SML")->statement('exec Sp_TransAlokasiBrg ?,?,?,?,?',$values);
      return 1;

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

      $lokasi =  DB::connection('SML')->select('select A.BarcodeLoc,A.qntMax,isnull(b.SALDOQNT,0) StockLokasi,A.qntMax - isnull(B.SALDOQNT,0) SisaKapasitas
from dbBarangLokasiSIG A
left outer join DBSTOCKBRGSIG B on A.KodeBarang=B.KODEBRG and A.BarcodeLoc=B.BARCODE AND B.BULAN= :bulan AND B.TAHUN= :tahun
where A.KodeBarang= :nobarang', ["nobarang" => $nobarang, "bulan" => $periode->bulan , "tahun" => $periode->tahun]);

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
