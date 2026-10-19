<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\NewMenu;
use App\Model\NewAksesMenu;
use App\Model\NewPeriode;
// use App\Model\NewUsers;
use Illuminate\Support\Facades\DB;



// use App\Http\Controllers\NewMenuController;

class NewSetupPeriodeKerjaController extends Controller
{

  public function index() {


    // $user = DB::connection("SML")->select('select * from DBGUDANG where KODEGDG <> :id', ['id' => 'GTC']);
    // $users = DB::connection("SML")->select('select * from new_users');


    // $periode = NewPeriode::where('user_id' , \Auth::User()->username)->first();
    $periode = NewPeriode::where('user_id' , \Auth::id())->first();

    $menul0 = app('App\Http\Controllers\NewMenuController')->getMenuL0();

    return view('newsetupperiodekerja' , [
      "menul0" => $menul0,
      "periode" => $periode,
      // "users"=> $users
    ]);
    // $username = \Auth::user()->username;

  }

  public function updatePeriodeKerja (Request $req) {
    // $username = \Auth::user()->username;

    $userid = \Auth::id();

    $tes = DB::connection("SML")->update('update new_periode set bulan= :bulan , tahun= :tahun where user_id= :username' , ['bulan' => $req->bulan , 'tahun' => $req->tahun , 'username' => $userid ]);
    $tes2 = DB::connection("SML")->select('select a.* , b.username from new_periode a join new_users b on a.user_id = b.id where user_id = :username ', ['username' => $userid]);
    return ["status" => $tes, "data" => $tes2];
  }




}
