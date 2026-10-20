<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Model\NewMenu;
use App\Model\NewAksesMenu;
use App\Model\NewPeriode;
use App\Model\NewUsers;
use Illuminate\Support\Facades\DB;
use App\Model\vwOUtPOWMS;
use App\Model\VWtampilbeli;
use App\Model\NEWDBBELI;
use App\Model\vw_outbeliqc;

class NewQCLPBController extends Controller
{


  public function index () {

    $kodemenu = '012';
    $akses = NewAksesMenu::where('USERID', \Auth::id())-> where('L1', $kodemenu)->first();
    if(!$akses || !$akses->HASACCESS) {
       return redirect('/home');
    }

    $periode = NewPeriode::where('user_id' , \Auth::id())->first();
    $menul0 = app('App\Http\Controllers\NewMenuController')->getMenuL0();

    // $lpbDetail = vw_outbeliqc::all()->sortBy('URUT')->where('pQC', 0)->where('NOBUKTI', 'SML/LPB/00001/0116' );

    // $lpbArray1 = DB::connection("SML")->select('select * from vw_outbeliqc' );
    // Dinonaktifkan 2026-10-19: memuat seluruh view lalu menyaring periode di PHP (lambat).
    // $lpb = vw_outbeliqc::all()->sortBy('URUT')->sortBy('NoBukti')->where('Bulan',$periode->bulan )->where('Tahun', $periode->tahun)->where('pQC', 0)->groupBy('NOBUKTI');
    // Pengganti: saring periode yang sama langsung di query database (hasil sama, jauh lebih cepat).
    $lpb = vw_outbeliqc::where('Bulan', $periode->bulan)->where('Tahun', $periode->tahun)->get()->sortBy('URUT')->sortBy('NoBukti')->where('pQC', 0)->groupBy('NOBUKTI');
    $lpbArray = [];
    foreach ($lpb as $l) {
      array_push($lpbArray, $l);
    }
    // Dinonaktifkan 2026-10-19: memuat seluruh view lalu menyaring periode di PHP (lambat).
    // $qc = vw_outbeliqc::all()->sortBy('URUT')->sortBy('NoBukti')->where('Bulan',$periode->bulan )->where('Tahun', $periode->tahun)->where('pQC', 1)->groupBy('NOBUKTI');
    // Pengganti: saring periode yang sama langsung di query database (hasil sama, jauh lebih cepat).
    $qc = vw_outbeliqc::where('Bulan', $periode->bulan)->where('Tahun', $periode->tahun)->get()->sortBy('URUT')->sortBy('NoBukti')->where('pQC', 1)->groupBy('NOBUKTI');
    $qcArray = [];
    foreach ($qc as $q) {
      array_push($qcArray, $q);
    }
    return view('newqclpb' , [
      "periode" => $periode,
      "menul0" => $menul0,
      "lpbArray" => $lpbArray,
      "qcArray" =>$qcArray,
      "akses" => $akses
    ]);

  }

  public function getAkses () {
    $kodemenu = '012';
    $akses = NewAksesMenu::where('USERID', \Auth::id())-> where('L1', $kodemenu)->first();
    return $akses;
  }

  public function getAllLPB() {
    $periode = NewPeriode::where('user_id' , \Auth::id())->first();
    // Dinonaktifkan 2026-10-19: memuat seluruh view lalu menyaring periode di PHP (lambat).
    // $lpb = vw_outbeliqc::all()->sortBy('URUT')->sortBy('NoBukti')->where('Bulan',$periode->bulan )->where('Tahun', $periode->tahun)->where('pQC', 0)->groupBy('NOBUKTI');
    // Pengganti: saring periode yang sama langsung di query database (hasil sama, jauh lebih cepat).
    $lpb = vw_outbeliqc::where('Bulan', $periode->bulan)->where('Tahun', $periode->tahun)->get()->sortBy('URUT')->sortBy('NoBukti')->where('pQC', 0)->groupBy('NOBUKTI');
    $lpbArray = [];
    foreach ($lpb as $l) {
      array_push($lpbArray, $l);
    }
    return $lpbArray;
  }
  public function getAllQC() {
    $periode = NewPeriode::where('user_id' , \Auth::id())->first();
    // Dinonaktifkan 2026-10-19: memuat seluruh view lalu menyaring periode di PHP (lambat).
    // $qc = $lpb = vw_outbeliqc::all()->sortBy('URUT')->sortBy('NoBukti')->where('Bulan',$periode->bulan )->where('Tahun', $periode->tahun)->where('pQC', 1)->groupBy('NOBUKTI');
    // Pengganti: saring periode yang sama langsung di query database (hasil sama, jauh lebih cepat).
    $qc = $lpb = vw_outbeliqc::where('Bulan', $periode->bulan)->where('Tahun', $periode->tahun)->get()->sortBy('URUT')->sortBy('NoBukti')->where('pQC', 1)->groupBy('NOBUKTI');
    $qcArray = [];
    foreach ($qc as $q) {
      array_push($qcArray, $q);
    }
    return $qcArray;
  }

  public function detailLPB(Request $req) {
    // return 1;
    // $pembelian = VWtampilbeli::all()->where('Bulan',$periode->bulan )->where('Tahun', $periode->tahun)->where('NoBukti', $req->input('NoBukti'))->sortBy('NoBukti')->groupBy('NoBukti');
    // Dinonaktifkan 2026-10-20: memuat seluruh view lalu menyaring di PHP (lambat).
    // $lpbDetail = vw_outbeliqc::all()->where('pQC', 0)->where('NOBUKTI', $req->input('NOBUKTI'))->sortBy('URUT')->groupBy('NoBukti');
    // Pengganti: saringan yang sama langsung di query database (hasil sama, jauh lebih cepat).
    $lpbDetail = vw_outbeliqc::whereRaw('(pQC = 0 or pQC is null)')->whereRaw('NOBUKTI = cast(? as varchar(50))', [$req->input('NOBUKTI')])->get()->sortBy('URUT')->groupBy('NoBukti');
    $lpbDetail1 = [];
    foreach ($lpbDetail as $lpb) {
      array_push($lpbDetail1, $lpb);
    }
    return $lpbDetail1;
  }

  public function detailQC(Request $req) {
    // return 1;
    // $pembelian = VWtampilbeli::all()->where('Bulan',$periode->bulan )->where('Tahun', $periode->tahun)->where('NoBukti', $req->input('NoBukti'))->sortBy('NoBukti')->groupBy('NoBukti');
    // Dinonaktifkan 2026-10-20: memuat seluruh view lalu menyaring di PHP (lambat).
    // $qcDetail = vw_outbeliqc::all()->where('pQC', 1)->where('NOBUKTI', $req->input('NOBUKTI'))->sortBy('URUT')->groupBy('NoBukti');
    // Pengganti: saringan yang sama langsung di query database (hasil sama, jauh lebih cepat).
    $qcDetail = vw_outbeliqc::where('pQC', 1)->whereRaw('NOBUKTI = cast(? as varchar(50))', [$req->input('NOBUKTI')])->get()->sortBy('URUT')->groupBy('NoBukti');
    $qcDetail1 = [];
    foreach ($qcDetail as $qc) {
      array_push($qcDetail1, $qc);
    }
    return $qcDetail1;
  }


  public function submitLPB(Request $req) {
    $data = $req->input('data');
      $username = \Auth::user()->username;
    foreach ($data as $d) {
      $values = [
        $d['NOBUKTI'],
        $d['KODEBRG'],
        $d['Urut'],
        $d['QntTerima'],
        $d['QntTerima1'],
        $d['QntTerima2'],
        $d['QntReject'],
        $d['QntReject1'],
        $d['QntReject2'],
        $username
      ];
      DB::connection("SML")->statement('exec Sp_QC ?,?,?,?,?,?,?,?,?,?',$values);


    }
    return 1;
  }



}
