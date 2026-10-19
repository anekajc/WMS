<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\NewMenu;
use App\Model\NewAksesMenu;
use App\Model\NewPeriode;
use App\Model\NewUsers;
use Illuminate\Support\Facades\DB;
use App\Model\VWRSPBWEB;
use App\Model\VwOutSPBRSPB;
use App\Model\VwOutSPBRSPBLIMIT;

use App\Model\OutSOSPBWEB;
use App\Model\OutSOPACKINGWEB;
use App\Model\Vwpersiapanspb;
use App\Model\vwpersiapanPACKING;

class PackingController extends Controller
{






  public function index () {
    $kodemenu = '0231';
    $akses = NewAksesMenu::where('USERID', \Auth::id())-> where('L1', $kodemenu)->first();
    if(!$akses || !$akses->HASACCESS) {
       return redirect('/home');
    }

    $periode = NewPeriode::where('user_id' , \Auth::id())->first();
    $menul0 = app('App\Http\Controllers\NewMenuController')->getMenuL0();

    $outstanding = OutSOPACKINGWEB::all()->sortBy('URUT')->groupBy('NOSO');

    $tempOutstanding = [];
    foreach ($outstanding as $o) {
      // code...
      array_push($tempOutstanding, $o);
    }


    $penerimaan = vwpersiapanPACKING::all()->where('bulan',$periode->bulan )->where('tahun', $periode->tahun)->sortBy('urut')->groupBy('nobukti');
    $tempPenerimaan = [];
    foreach ($penerimaan as $p) {
      // code...
      array_push($tempPenerimaan, $p);
    }

    $expedisi = DB::connection("SML")->select('select * from dbcustsupp where jenis = :id', ['id' => 3]);

    return view('packing' , [
      "periode" => $periode,
      "menul0" => $menul0,
      "outstandingArray" => $tempOutstanding,
      "penerimaanArray" => $tempPenerimaan,
      "expedisi" => $expedisi,
      "akses" => $akses
    ]);
  }

  public function getNoBukti (Request $req) {

    $periode = NewPeriode::where('user_id' , \Auth::id())->first();
    // $inisial = DB::connection("SML")->select('select PBL from DBNOMOR');
    // $inisial = DB::connection("SML")->select('select SPB from DBNOMOR');
    $username = \Auth::user()->username;
    // return [$periode->bulan,$inisial[0]->PBL,$username];
    $values = [
        'PCK',
        $periode->bulan,
        $periode->tahun,
        $username
    ];
    $noBukti = DB::connection('SML')->select('exec SP_IsiNobukti ?,?,?,?',$values);
    return $noBukti;
  }


  public function getDetailOutstanding (Request $req) {

    $detailOutstanding = OutSOPACKINGWEB::all()->where('NOSO', $req->input('NOBUKTI'))->sortBy('URUT');
    $tempOutstanding = [];
    foreach ($detailOutstanding as $do) {
      // code...
      array_push($tempOutstanding,$do);
    }
    return $tempOutstanding;

  }


public function checkAdd (Request $req) {

    $check = DB::connection('SML')->select('exec sp_checkPLQC ?,? ',[ $req->input('NOBUKTI') ,$req->input('gdgpl') ]);
    if ($check) {
      return 0;
    } else {
      return 1;
    }




  }

  public function getDetailOutstandingKolli (Request $req) {

    $detailOutstanding = OutSOPACKINGWEB::all()->where('NOBUKTI', $req->input('NOBUKTI'))->sortBy('URUT');
    $tempOutstanding = [];
    foreach ($detailOutstanding as $do) {
      // code...
      array_push($tempOutstanding,$do);
    }
    return $tempOutstanding;

  }

  public function getDetailPenerimaan (Request $req) {
    // return $req->input('NOBUKTI');
    $detailPenerimaan = vwpersiapanPACKING::all()->where('nobukti',$req->input('NOBUKTI'))->sortBy('Urut');
    // $detailPenerimaan = OutSOPACKINGWEB::all()->where('NOBUKTI',$req->input('NOBUKTI'))->sortBy('URUT');
    $tempPenerimaan = [];
    foreach ($detailPenerimaan as $p) {
      // code...
      array_push($tempPenerimaan, $p);
    }
    return $tempPenerimaan;
  }

  public function getKoreksiAddList (Request $req) {
    $tempPenerimaan = vwpersiapanPACKING::where('nobukti' , $req->input('norspb'))->select('kodebrg')->get()->toArray();

    $tempAddList = OutSOPACKINGWEB::select()->where('NOSO', $req->input('nosj'))->whereNotIn('KODEBRG', $tempPenerimaan)->get();

    return $tempAddList;
  }

  public function addPacking (Request $req) {


    $periode = NewPeriode::where('user_id' , \Auth::id())->first();
    $date = $req->input('inputDate');
    $username = \Auth::user()->username;
    $data = $req->input('tempData');
    $nourut = $req->input('nourut');

    $jumlahkolli = $req->input('jumlahkolli');
    // $nosj = $req->input('nosj');
    $norspb = $req->input('norspb');
    $nooutso = $req->input('nooutso');
    $nopolkendaraan = $req->input('nopolkendaraan');
    $expedisi = $req->input('expedisi');
    $choice = "I";
      // return [$data,$date,$nourut,$nosj,$norspb,$nooutso,$nopolkendaraan,$expedisi];

      // select * from dbSPB where NoBukti = 'SML/SJ/00002/0523'
      $check = DB::connection('SML')->select('select * from dbpacking where NOBUKTI = :nobukti',["nobukti" => $norspb]);
      if ($check) {
        return 2;
      }

      $check3 = DB::connection('SML')->select("select  case when month(:tgl) = (select bulan from new_periode where user_id=:iduser1)
                                              and year(:tgl2)= (select tahun from new_periode where user_id=:iduser2)

                                              then 0 else 1 End pp  ",
                                              ["tgl"=>$date,"iduser1"=>\Auth::user()->id,"tgl2"=>$date,"iduser2"=>\Auth::user()->id]);

        if (($check3[0]->pp ) ==1) {
          return  8;
        }


        $flagCheck4 = 0;
        foreach ($data as $d) {
          // code...
          $check4 = DB::connection('SML')->select('select * from dbpackingdet where nopicking = :nobukti and urutpicking = :urut',["nobukti" => $d['NOBUKTI'] , "urut" => $d['URUT']]);
          if ($check4) {
            $flagCheck4 = 1;
          }

        }

        if($flagCheck4 == 1) {
          return 4;
        }
      // $checkSaldo = 0;
    // DB::connection('SML')->statement('delete	TempOutstanding where IDUser = :idUser',['idUser' => $username ]);
    foreach ($data as $d) {
      // code...
      $values = [
          $choice,
          $norspb,
          $nourut,
          $date,
          $d['NOSO'],
          $jumlahkolli,
          $username,
          0,
          $d['NOBUKTI'],
          $d['URUT'],
          $d['Qnt']
      ];


      DB::connection("SML")->statement('exec sp_packing ?,?,?,?,?,?,?,?,?,?,?',$values);


    }


    return 1;

  }

  public function updateCetakKolli (Request $req) {

    $res = DB::connection('SML')->statement('update	dbspb set cetakkolli = :jumlahPrint where nobukti = :nobukti',['jumlahPrint' => $req->jumlahPrint , 'nobukti' => $req->nobukti   ]);
    return 1;
  }

  public function spOtorisasi1 (Request $req) {
    $username = \Auth::user()->username;
    $nobukti =  $req->nobukti;
    $otorisasi = $req->otorisasi;

    if ($otorisasi == 0 ) {
      $username = '';
      $tanggal = null;
    }


    $update = DB::connection('SML')->update("update dbSPB set IsOtorisasi1 = :otorisasi , OtoUser1 = :username , TglOto1 = getDate() , MaxOL = 1 where nobukti = :nobukti", ['otorisasi' => $otorisasi, 'username' => $username,  'nobukti' => $nobukti ] );
    return $update;

  }

  public function loadAll () {
    $kodemenu = '0231';
    $akses = NewAksesMenu::where('USERID', \Auth::id())-> where('L1', $kodemenu)->first();
    $periode = NewPeriode::where('user_id' , \Auth::id())->first();

    $outstanding = OutSOPACKINGWEB::all()->sortBy('URUT')->groupBy('NOSO');

    $tempOutstanding = [];
    foreach ($outstanding as $o) {
      // code...
      array_push($tempOutstanding, $o);
    }

    $penerimaan = vwpersiapanPACKING::all()->where('bulan',$periode->bulan )->where('tahun', $periode->tahun)->sortBy('Urut')->groupBy('nobukti');
    $tempPenerimaan = [];
    foreach ($penerimaan as $p) {
      // code...
      array_push($tempPenerimaan, $p);
    }

    return [
      "outstandingArray" => $tempOutstanding,
      "penerimaanArray" => $tempPenerimaan,
      "akses" => $akses
    ];
  }






  // choice
  // nobukti
  // nourut
  // tanggal
  // noso
  // jumlahkolli
  // iduser
  // urut
  // nopicking
  // urutpicking
  // qnt


  public function spKoreksi (Request $req) {

    // choice
    // nobukti
    // nourut
    // tanggal
    // noso
    // jumlahkolli
    // iduser
    // urut
    // nopicking
    // urutpicking
    // qnt

    if ($req->input('choice') == 'D') {

      $check = DB::connection('SML')->select('select * from dbspbdet where Nopacking = :nobukti and Urutpacking = :urut',["nobukti" => $req->input('nobukti') , "urut" => $req->input('urut')]);
      if ($check) {
        return 2;
      }
    }

    $username = \Auth::user()->username;

    $values = [
      $req->input('choice'),
      $req->input('nobukti'),
      $req->input('nourut'),
      $req->input('inputDate'),
      $req->input('noso'),
      $req->input('jumlahkolli'),
      $username ,
      $req->input('urut'),
      $req->input('nopicking'),
      $req->input('urutpicking'),
      $req->input('qnt'),

    ];

      DB::connection('SML')->statement('exec sp_packing ?,?,?,?,?,?,?,?,?,?,?' ,$values);
      return 1;

  }

  // public function spKoreksi (Request $req) {
  //   $values = [
  //     $req->input('choice'),
  //     $req->input('norspb'),
  //     $req->input('nourut'),
  //     $req->input('inputDate'),
  //     $req->input('nosj'),
  //     $req->input('kodecustsupp'),
  //     $req->input('nopolkend'),
  //     $req->input('container'),
  //     $req->input('nocontainer'),
  //     $req->input('noseal'),
  //     $req->input('catatan'),
  //     $req->input('urut'),
  //     $req->input('urutsj'),
  //     $req->input('kodebrg'),
  //     $req->input('qntTerima'),
  //     $req->input('qntTerima2'),
  //     $req->input('sat_1'),
  //     $req->input('sat_2'),
  //     $req->input('nosat'),
  //     $req->input('isi'),
  //     $req->input('netw'),
  //     $req->input('grossw'),
  //     \Auth::user()->username,
  //     $req->input('isempty'),
  //     $req->input('namabrg'),
  //     $req->input('flagmenu'),
  //     $req->input('flagtipe'),
  //     $req->input('retursupp')
  //   ];
  //
  //
  //
  //   DB::connection('SML')->statement('exec sp_SPBweb ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?' ,$values);
  //   return 1;
  // }








}
