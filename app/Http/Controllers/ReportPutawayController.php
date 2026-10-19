<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Model\NewMenu;
use App\Model\NewAksesMenu;
use App\Model\NewAksesMenuReport;
use App\Model\NewPeriode;
use App\Model\NewUsers;
use Illuminate\Support\Facades\DB;


class ReportPutawayController extends Controller
{




  public function index () {


// $outstanding = DB::connection("SML")->select('exec sp_sosiapkirim ?,?,?',[ $periode->bulan , $periode->tahun ,\Auth::user()->username ]);
    $kodemenu = '0593';
    $akses = NewAksesMenuReport::where('USERID', \Auth::id())-> where('L1', $kodemenu)->first();
    if(!$akses || !$akses->HASACCESS) {
       return redirect('/home');
    }


    $periode = NewPeriode::where('user_id' , \Auth::id())->first();
    $menul0 = app('App\Http\Controllers\NewMenuController')->getMenuL0();

    $bln = $periode->bulan;
    $thn = $periode->tahun;


    $outstanding = DB::connection("SML")->select('select * from dbalokasibrgdet where MONTH(tanggal) = :bln and YEAR(tanggal) = :thn',["bln" => $bln, "thn" => $thn]);;





    return view('reportputaway' , [
      "periode" => $periode,
      "menul0" => $menul0,
      "listData" => $outstanding
    ]);
  }

  public function spFilter (Request $req) {
  $periode = NewPeriode::where('user_id' , \Auth::id())->first();
  $bln = $req->input('bln');
  $thn = $req->input('thn');
  // $values = [
	// 	$bln,
  //       $thn
	// ];
    //return DB::connection("SML")->select("exec Sp_ReportSoSpb :bulan,:tahun ", ["bulan" => $bulan,"tahun" => $tahun] );
    return DB::connection("SML")->select('select * from dbalokasibrgdet where MONTH(tanggal) = :bln and YEAR(tanggal) = :thn',["bln" => $bln, "thn" => $thn]);
  }




}
