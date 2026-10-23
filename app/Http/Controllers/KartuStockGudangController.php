<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Model\NewMenu;
use App\Model\NewAksesMenu;
use App\Model\NewAksesMenuReport;
use App\Model\NewPeriode;
use App\Model\NewUsers;
use Illuminate\Support\Facades\DB;


class KartuStockGudangController extends Controller
{




  public function index () {

    // Dinonaktifkan 2026-10-23: kode menu 051 adalah Kartu Stok. Menu ini 059 (new_menureport).
    // $kodemenu = '051';
    $kodemenu = '059';
    $akses = NewAksesMenuReport::where('USERID', \Auth::id())-> where('L1', $kodemenu)->first();
    if(!$akses || !$akses->HASACCESS) {
       return redirect('/home');
    }

    $periode = NewPeriode::where('user_id' , \Auth::id())->first();
    $menul0 = app('App\Http\Controllers\NewMenuController')->getMenuL0();





    return view('kartustockgudang' , [
      "periode" => $periode,
      "menul0" => $menul0,
      "akses" => $akses
    ]);
  }

  public function listBarang () {
    $list = DB::connection('SML')->select('select KODEBRG, NAMABRG from DBBARANG order by KODEBRG, NAMABRG');
    return $list;
  }

  public function listLokasi () {
    $list = DB::connection('SML')->select('select KodeGdg, Nama  from dbgudang order by kodegdg');
    return $list;
  }

  public function spReport (Request $req) {
    // return 1;



        $values = [
          $req->get('kodelokasi'),
          $req->get('kodebrg'),
          $req->get('awalBulan'),
          $req->get('akhirBulan'),
          $req->get('awalTahun'),
          $req->get('akhirTahun'),
          $req->get('satuan')
        ];
        $res = DB::connection('SML')->select('exec Sp_reportkartuStockLokasi ?,?,?,?,?,?,?',$values);
        return $res;



  }


}
