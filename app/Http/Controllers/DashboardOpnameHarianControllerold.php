<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Model\NewMenu;
use App\Model\NewAksesMenu;
use App\Model\NewAksesMenuReport;
use App\Model\NewPeriode;
use App\Model\NewUsers;
use Illuminate\Support\Facades\DB;


class DashboardOpnameHarianController extends Controller
{




  public function index () {

    $kodemenu = '041';
    $akses = NewAksesMenu::where('USERID', \Auth::id())-> where('L1', $kodemenu)->first();
    if(!$akses || !$akses->HASACCESS) {
       return redirect('/home');
    }

    $periode = NewPeriode::where('user_id' , \Auth::id())->first();
    $menul0 = app('App\Http\Controllers\NewMenuController')->getMenuL0();

    $gudang = DB::connection("SML")->select("select kodegdg , nama from DBGUDANG order by kodegdg");
    $datenow =
    $values = [
      date('Y-m-d'),
      date('Y-m-d'),
      $gudang[0]->kodegdg,
    ];
    $res = DB::connection('SML')->select('exec sp_dashboardopnameharian ?,?,?',$values);

    return view('dashboardopnameharian' , [
      "periode" => $periode,
      "menul0" => $menul0,
      "gudang" => $gudang,
      "res" => $res
     ]);
  }


  public function spDashboard (Request $req) {
    // return 1;



        $values = [
          $req->tglawal,
          $req->tglakhir,
          $req->gudang
        ];
        $res = DB::connection('SML')->select('exec sp_dashboardopnameharian ?,?,?',$values);
        return $res;



  }


}
