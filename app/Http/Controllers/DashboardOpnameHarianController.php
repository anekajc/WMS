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

    $gudang = DB::connection("SML")->select("
    select '-Semua' kodegdg, '' nama union all
    select kodegdg , nama from DBGUDANG
    order by kodegdg");
    $datenow =
    $values = [
      date('m'),
      date('Y'),
      $gudang[0]->kodegdg,
      0,
      1
    ];
    $res = DB::connection('SML')->select('exec sp_dashboardopnameharian ?,?,?,?,?',$values);
//     $res = DB::connection('SML')->select('Select
// 20 targetaudit,
// 99 terhitung,
// 1 terhitungpersen,
// 98 belumterhitung,
// 2 belumterhitungpersen,
// 2 pencapaianaudit,
//
// 999 sesuai,
// 44 selisihkurang,
// 3 selisihkurangpersen,
// 96 sesuailebih,
// 4 sesuailebihpersen,
// 50 firstdifferent');


    return view('dashboardopnameharian' , [
      "periode" => $periode,
      "menul0" => $menul0,
      "gudang" => $gudang,
      "res" => $res
     ]);
  }


  public function spDashboard (Request $req) {

        $values = [
          $req->bulan,
          $req->tahun,
          $req->gudang,
          $req->tipe,
          $req->semester
        ];
        $res = DB::connection('SML')->select('exec sp_dashboardopnameharian ?,?,?,?,?',$values);
    //     $res = DB::connection('SML')->select('Select
    // 228 targetaudit,
    // 99 terhitung,
    // 1 terhitungpersen,
    // 98 belumterhitung,
    // 2.2 belumterhitungpersen,
    // 2 pencapaianaudit,
    //
    // 999 sesuai,
    // 44 selisihkurang,
    // 3 selisihkurangpersen,
    // 96 sesuailebih,
    // 4 sesuailebihpersen,
    // 48.06 firstdifferent');
        return $res;
  }

  public function spListBarang (Request $req) {
    // return 1;
        $values = [
          $req->bulan,
          $req->tahun,
          $req->gudang,
          $req->tipe,
          $req->semester,
          $req->choice
        ];
        // $res = DB::connection('SML')->select('exec sp_dashboardopnameharian ?,?,?,?,?',$values);
        $res = DB::connection('SML')->select('exec sp_dashboardopnameharianlistbarang ?,?,?,?,?,?',$values);

        // $res = DB::connection('SML')->select("Select 'asd' NamaBrg , '1123' KodeBrg ");
        return $res;
  }


}
