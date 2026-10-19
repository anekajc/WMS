<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\NewMenu;
use App\Model\NewAksesMenu;
use App\Model\NewPeriode;
use App\Model\NewUsers;
use Illuminate\Support\Facades\DB;




// use App\Http\Controllers\NewMenuController;

class SetPemakaiController extends Controller
{

  public function index() {

    $kodemenu = '006';
    $akses = NewAksesMenu::where('USERID', \Auth::id())-> where('L1', $kodemenu)->first();
    if(!$akses || !$akses->HASACCESS) {
       return redirect('/home');
    }


    $users = DB::connection("SML")->select("select * from new_users where username != 'SA' and deleted = 0");


    $periode = NewPeriode::where('user_id' , \Auth::id())->first();


    $menul0 = app('App\Http\Controllers\NewMenuController')->getMenuL0();

    return view('setpemakai' , [
      "menul0" => $menul0,
      "periode" => $periode,
      "users"=> $users,
      "akses" => $akses
    ]);

  }


  public function listAkses (Request $req) {

    $values = [
        $req->userid,
      ];
    DB::connection('SML')->update('exec sp_updateMenuWeb ?',$values);

    $listAkses = DB::connection("SML")->select('select a.* , b.* from new_menu a join new_aksesmenu b ON a.KODEMENU = b.L1 where b.USERID = :userid order by a.KODEMENU' , ['userid' => $req->userid]);

    return $listAkses;
  }



  public function listAksesReport (Request $req) {

    $values = [
        $req->userid,
      ];
    DB::connection('SML')->update('exec sp_updateMenuReportWeb ?',$values);

    $listAkses = DB::connection("SML")->select('select a.* , b.* from new_menureport a join new_aksesmenureport b ON a.KODEMENU = b.L1 where b.USERID = :userid order by a.KODEMENU' , ['userid' => $req->userid]);

    return $listAkses;
  }


  public function spUpdateAkses (Request $req) {

    $username = $req->input('userid');

    $data = $req->input('tempData');


    // DB::connection('SML')->statement('delete	DBFLMENU where USERID = :USERID',['USERID' => $username ]);


    // foreach ($data as $d) {
      $values = [
         $username,
         $data['KODEMENU'],
         $data['HASACCESS'],
         $data['ISTAMBAH'],
         $data['ISKOREKSI'],
         $data['ISHAPUS'],
         $data['ISCETAK'],
         $data['ISEXPORT'],
         $data['ISOTO1'],
         $data['ISOTO2'],
         $data['ISOTO3'],
         $data['ISOTO4'],
         $data['ISOTO5'],
         $data['ISBATAL'],
         $data['pembatalan']

        ];
      DB::connection('SML')->update('exec SP_Flmenuweb ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?',$values);
    // }

    return 1;


  }

  public function spUpdateAksesReport (Request $req) {

    $username = $req->input('userid');

    $data = $req->input('tempData');


    // DB::connection('SML')->statement('delete	DBFLMENU where USERID = :USERID',['USERID' => $username ]);


    // foreach ($data as $d) {
      $values = [
         $username,
         $data['KODEMENU'],
         $data['HASACCESS'],
         $data['ISDESIGN'],
         $data['ISEXPORT'],

        ];
      DB::connection('SML')->update('exec SP_FlmenuReportweb ?,?,?,?,?',$values);
    // }

    return 1;


  }

  public function spUpdateAksesHeader (Request $req) {

    // $username = $req->input('username');

    // $data = $req->input('tempData');


    // DB::connection('SML')->statement('delete	DBFLMENU where USERID = :USERID',['USERID' => $username ]);


    // foreach ($data as $d) {
      $values = [
         $req->input('headermenu'),
         $req->input('nilai'),
         $req->input('userid'),
         $req->input('field'),
        ];
      DB::connection('SML')->update('exec sp_updatesetmenuweb ?,?,?,?',$values);
    // }

    return 1;


  }

  public function spUpdateAksesReportHeader (Request $req) {

    // $username = $req->input('username');

    // $data = $req->input('tempData');


    // DB::connection('SML')->statement('delete	DBFLMENU where USERID = :USERID',['USERID' => $username ]);


    // foreach ($data as $d) {
      $values = [
         $req->input('headermenu'),
         $req->input('nilai'),
         $req->input('userid'),
         $req->input('field'),
        ];
      DB::connection('SML')->update('exec sp_UpdateSetmenuReportWeb ?,?,?,?',$values);
    // }

    return 1;


  }




}
