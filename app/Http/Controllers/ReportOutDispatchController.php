<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Model\NewMenu;
use App\Model\NewAksesMenu;
use App\Model\NewAksesMenuReport;
use App\Model\NewPeriode;
use App\Model\NewUsers;
use Illuminate\Support\Facades\DB;


class ReportOutDispatchController extends Controller
{




  public function index () {


// $outstanding = DB::connection("SML")->select('exec sp_sosiapkirim ?,?,?',[ $periode->bulan , $periode->tahun ,\Auth::user()->username ]);
    $kodemenu = '0591';
    $akses = NewAksesMenuReport::where('USERID', \Auth::id())-> where('L1', $kodemenu)->first();
    if(!$akses || !$akses->HASACCESS) {
       return redirect('/home');
    }

    $periode = NewPeriode::where('user_id' , \Auth::id())->first();
    $menul0 = app('App\Http\Controllers\NewMenuController')->getMenuL0();
    $bln = $periode->bulan;
    $thn = $periode->tahun;
    $outstanding = DB::connection("SML")->select('exec SP_REPORTOUTDISPATCH ?,? ',[$bln,$thn]);;






    return view('reportoutdispatch' , [
      "periode" => $periode,
      "menul0" => $menul0,
      "listData" => $outstanding
    ]);
  }

  public function spFilter (Request $req) {
    // return $req;
  // $periode = NewPeriode::where('user_id' , \Auth::id())->first();
  $bln = (int)$req->input('month');
  $thn = (int)$req->input('year');
  // return [$bln , $thn];

    return DB::connection("SML")->select('exec SP_REPORTOUTDISPATCH ?,?',[$bln, $thn]);
  }




}
