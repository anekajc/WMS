<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Model\NewMenu;
use App\Model\NewAksesMenu;
use App\Model\NewAksesMenuReport;
use App\Model\NewPeriode;
use App\Model\NewUsers;
use Illuminate\Support\Facades\DB;


class ReportOpnameHarianController extends Controller
{




  public function index () {


// $outstanding = DB::connection("SML")->select('exec sp_sosiapkirim ?,?,?',[ $periode->bulan , $periode->tahun ,\Auth::user()->username ]);
    $kodemenu = '0592';
    $akses = NewAksesMenuReport::where('USERID', \Auth::id())-> where('L1', $kodemenu)->first();
    if(!$akses || !$akses->HASACCESS) {
       return redirect('/home');
    }


    $periode = NewPeriode::where('user_id' , \Auth::id())->first();
    $menul0 = app('App\Http\Controllers\NewMenuController')->getMenuL0();

    $bln = $periode->bulan;
    $thn = $periode->tahun;


    $outstanding = DB::connection("SML")->select('select c.NAMABRG , YEAR(a.tanggal) TAHUN, MONTH(a.tanggal) BULAN, a.tanggal, a.nourut , b.* from dbkoreksiopndet b
    left outer join dbkoreksiopn a   on a.nobukti = b.nobukti
    left outer join dbbarang c on c.kodebrg = b.kodebrg
    where  YEAR(a.tanggal) = :tahun and MONTH(a.tanggal) = :bulan
     ',["tahun" => $thn , "bulan" => $bln]);;





    return view('reportopnameharian' , [
      "periode" => $periode,
      "menul0" => $menul0,
      "listData" => $outstanding
    ]);
  }

  public function spFilter (Request $req) {
  $periode = NewPeriode::where('user_id' , \Auth::id())->first();
  $bln = $req->input('bln');
  $thn = $req->input('thn');
  $values = [
		$bln,
        $thn
	];
    //return DB::connection("SML")->select("exec Sp_ReportSoSpb :bulan,:tahun ", ["bulan" => $bulan,"tahun" => $tahun] );
    return DB::connection("SML")->select('select c.NAMABRG , YEAR(a.tanggal) TAHUN, MONTH(a.tanggal) BULAN, a.tanggal, a.nourut , b.* from dbkoreksiopndet b
    left outer join dbkoreksiopn a   on a.nobukti = b.nobukti
    left outer join dbbarang c on c.kodebrg = b.kodebrg
    where  YEAR(a.tanggal) = :tahun and MONTH(a.tanggal) = :bulan
     ',["tahun" => $thn , "bulan" => $bln]);;
  }




}
