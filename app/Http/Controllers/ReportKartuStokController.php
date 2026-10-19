<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Model\NewMenu;
use App\Model\NewAksesMenu;
use App\Model\NewAksesMenuReport;
use App\Model\NewPeriode;
use App\Model\NewUsers;
use Illuminate\Support\Facades\DB;


class ReportKartuStokController extends Controller
{




  public function index () {

    $kodemenu = '051';
    $akses = NewAksesMenuReport::where('USERID', \Auth::id())-> where('L1', $kodemenu)->first();
    if(!$akses || !$akses->HASACCESS) {
       return redirect('/home');
    }

    $periode = NewPeriode::where('user_id' , \Auth::id())->first();
    $menul0 = app('App\Http\Controllers\NewMenuController')->getMenuL0();





    return view('reportkartustok' , [
      "periode" => $periode,
      "menul0" => $menul0,
    ]);
  }

  public function listBarang () {
    $list = DB::connection('SML')->select('select KODEBRG, NAMABRG from DBBARANG order by KODEBRG, NAMABRG');
    return $list;
  }

  public function listLokasi () {
    $list = DB::connection('SML')->select('select KodeGdg, NamaLokasi , BarcodeLoc from dblokasisig order by BarcodeLoc');
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
