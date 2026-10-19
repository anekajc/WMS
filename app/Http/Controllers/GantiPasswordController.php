<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\NewMenu;
use App\Model\NewAksesMenu;
use App\Model\NewPeriode;
// use App\Model\NewUsers;
use App\Model\User;
use Illuminate\Support\Facades\DB;
// use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;



// use App\Http\Controllers\NewMenuController;

class GantiPasswordController extends Controller
{

  public function index() {


    // $user = DB::connection("SML")->select('select * from DBGUDANG where KODEGDG <> :id', ['id' => 'GTC']);
    // $users = DB::connection("SML")->select('select * from new_users');


    // $periode = NewPeriode::where('user_id' , \Auth::User()->username)->first();
    $periode = NewPeriode::where('user_id' , \Auth::id())->first();

    $menul0 = app('App\Http\Controllers\NewMenuController')->getMenuL0();

    return view('gantipassword' , [
      "menul0" => $menul0,
      "periode" => $periode,
      // "users"=> $users
    ]);
    // $username = \Auth::user()->username;

  }

  public function gantiPassword (Request $req) {
    User::where('username', \Auth::user()->username )->update(['password' => Hash::make($req->input('password'))]);
    return 1;
  }




}
