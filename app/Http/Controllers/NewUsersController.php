<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Model\NewMenu;
use App\Model\NewPeriode;
use App\Model\NewAksesMenu;

use App\Model\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;


class NewUsersController extends Controller
{




  public function index () {
    $kodemenu = '005';
    $akses = NewAksesMenu::where('USERID', \Auth::id())-> where('L1', $kodemenu)->first();
    if(!$akses || !$akses->HASACCESS) {
       return redirect('/home');
    }

    $periode = NewPeriode::where('user_id' , \Auth::id())->first();
    $menul0 = app('App\Http\Controllers\NewMenuController')->getMenuL0();

    $listData = DB::connection('SML')->select("select name, username, level,id, status from new_users where deleted = 0 and username != 'SA' "); // 2026-10-20: + status untuk kartu User Online



    return view('newusers' , [
      "periode" => $periode,
      "menul0" => $menul0,
      "listData" => $listData,
      "akses" => $akses
    ]);
  }

  public function loadAll () {
    $listData = DB::connection('SML')->select("select name, username, level,id, status from new_users where deleted = 0 and username != 'SA' "); // 2026-10-20: + status untuk kartu User Online
    return $listData;
  }

  public function gantiPassword (Request $req) {
    User::where('username', $req->input('username'))->update(['password' => Hash::make($req->input('password'))]);
    return 1;
  }

  public function edit (Request $req) {
    User::where('username', $req->input('username'))->update(['name' => $req->input('nama'), 'level' => $req->input('level') ]);
    return 1;
  }

  public function delete (Request $req) {
    if ($req->input('username') == 'sa') {
      // code...
      return 2;
    }
    User::where('username', $req->input('username'))->update(['deleted' => 1]);
    return 1;
  }

  public function detail (Request $req) {
    $detail = DB::connection('SML')->select('select name, username, level from new_users where deleted = 0 and username = :username' , ['username' => $req->input('username')]);
    return $detail;
  }

  public function add (Request $req) {


    $check = User::where('username', $req->input('username'))->count();

    if ($check > 0 ) {
      return 'Username telah digunakkan';
    }

    $newUser = User::create([
      'name' => $req->input('fullname'),
      'username' => $req->input('username'),
      'password' => Hash::make($req->input('password')),
      'level' => $req->input('level'),
      'hostid' => "",
      'ipaddress' => "",
      'deleted' => 0,
      'status' => 0
    ]);

    $periode = DB::connection('SML')->update('insert into new_periode (user_id , bulan, tahun , deleted) values ( :user_id , :bulan , :tahun , 0) ' , ['user_id' => $newUser->id , 'bulan' => (int)date('m') , 'tahun' => (int)date('Y')]);
    // 'insert into DBGROUP (KODEGRP, NAMA) values (:kode, :nama)' , ['kode' => $req->kode , 'nama' => $req->nama]

    // 'bulan' => (int)date('m'),
    // 'tahun' => (int)date('Y')

    return 1;

    // $newUser->id

  }





}
