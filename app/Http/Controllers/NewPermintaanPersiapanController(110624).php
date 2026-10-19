<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\NewMenu;
use App\Model\NewAksesMenu;
use App\Model\NewPeriode;
use App\Model\NewUsers;
use Illuminate\Support\Facades\DB;
// use App\Model\VWRSPBWEB;
// use App\Model\VwOutSPBRSPB;
// use App\Model\VwOutSPBRSPBLIMIT;
use App\Model\vwOutBarang;
use App\Model\vwShowOutBrg;
use App\Model\vwShowOutBrgperiod;


class NewPermintaanPersiapanController extends Controller
{




  public function index () {

    $kodemenu = '021';
    $akses = NewAksesMenu::where('USERID', \Auth::id())-> where('L1', $kodemenu)->first();
    if(!$akses || !$akses->HASACCESS) {
       return redirect('/home');
    }

    $periode = NewPeriode::where('user_id' , \Auth::id())->first();
    $menul0 = app('App\Http\Controllers\NewMenuController')->getMenuL0();



    return view('newpermintaanpersiapan' , [
      "periode" => $periode,
      "menul0" => $menul0,
      "akses" => $akses
      // "outstandingArray" => $outstanding,
      // "penerimaanArray" => $penerimaan
    ]);
  }

  public function loadAll () {
    $kodemenu = '021';
    $akses = NewAksesMenu::where('USERID', \Auth::id())-> where('L1', $kodemenu)->first();

    $periode = NewPeriode::where('user_id' , \Auth::id())->first();
    $menul0 = app('App\Http\Controllers\NewMenuController')->getMenuL0();

    // sp_sosiapkirim

    // DB::connection("SML")->statement('exec sp_sosiapkirim ?,?,?',[$periode->bulan , $periode->tahun,\Auth::user()->username; ]);

    // $outstanding = vwOutBarang::whereMonth('TANGGAL', '=', $periode->bulan)->whereYear('TANGGAL', '=', $periode->tahun)->
    //     orderBy('TANGGAL', 'DESC')->selectRaw('vwOutBarang.*')->get();
//
    $outstanding = DB::connection("SML")->select('exec sp_sosiapkirim ?,?,?',[ $periode->bulan , $periode->tahun ,\Auth::user()->username ]);
    // nobukti , tanggal, nama cust, nama barang, pocust, qntso, qty, stock , sat, catatan, lokasipenerima, alamat kirim, duedate, pr, part number, merk, userid
    // $penerimaan = vwShowOutBrg::whereMonth('TANGGAL', '=', $periode->bulan)->whereYear('TANGGAL', '=', $periode->tahun)->
    //     orderBy('NOBUKTI', 'DESC')->selectRaw('vwShowOutBrg.*')->get();

    // $penerimaan = DB::connection("SML")->select("select * from vwShowOutBrg where NOBUKTI LIKE '%/OB/%' and Bulan = :bulan and Tahun = :tahun" , ["bulan" => $periode->bulan ,"tahun" => $periode->tahun  ]);
    $penerimaan = vwShowOutBrg::all()->where('Bulan' ,$periode->bulan)->where('Tahun', $periode->tahun)->sortBy('URUT')->groupBy('NOBUKTI');
    $tempPenerimaan = [];
    foreach ($penerimaan as $p) {
      // code...
      array_push($tempPenerimaan, $p);
    }

    $penerimaanperiod = vwShowOutBrgperiod::all()->where('Bulan' ,$periode->bulan)->where('Tahun', $periode->tahun)->sortBy('URUT')->groupBy('NOBUKTI');
    $tempPenerimaanperiod = [];
    foreach ($penerimaanperiod as $p2) {
      // code...
      array_push($tempPenerimaanperiod, $p2);
    }



    return [
      "outstanding" => $outstanding,
      "penerimaan" => $tempPenerimaan,
      "akses" => $akses,
      "penerimaanperiod" => $tempPenerimaanperiod
    ];
  }

  public function detailPenerimaan (Request $req) {
    $penerimaan = vwShowOutBrg::all()->where('NOBUKTI' ,$req->input('nobukti'))->sortBy('URUT');
    $tempPenerimaan = [];
    foreach ($penerimaan as $p) {
      // code...
      array_push($tempPenerimaan, $p);
    }
    return $tempPenerimaan;
  }

  public function detailPenerimaanperiod (Request $req) {
    $penerimaanperiod = vwShowOutBrgperiod::all()->where('NOBUKTI' ,$req->input('nobukti'))->sortBy('URUT');
    $tempPenerimaanperiod = [];
    foreach ($penerimaanperiod as $p2) {
      // code...
      array_push($tempPenerimaanperiod, $p2);
    }
    return $tempPenerimaanperiod;
  }


  public function getNoBukti (Request $req) {

    $periode = NewPeriode::where('user_id' , \Auth::id())->first();
    // $inisial = DB::connection("SML")->select('select PBL from DBNOMOR');
    $inisial = DB::connection("SML")->select('select OB from DBNOMOR');
    $username = \Auth::user()->username;
    // return [$periode->bulan,$inisial[0]->PBL,$username];
    $values = [
        $inisial[0]->OB,
        $periode->bulan,
        $periode->tahun,
        $username
    ];
    $noBukti = DB::connection('SML')->select('exec SP_IsiNobukti ?,?,?,?',$values);
    return $noBukti;
  }

  public function spAdd (Request $req) {

    $periode = NewPeriode::where('user_id' , \Auth::id())->first();
    $username = \Auth::user()->username;
    $choice = $req->input('choice');
    $tanggal = $req->input('tanggal');
    $keterangan = $req->input('keterangan');
    $nourut = $req->input('nourut');
    $nobukti = $req->input('nobukti');
    $data = $req->input('tempData');
    $idToken = $username . $req->input('idToken');

    // return [$data,$date,$nourut,$nosj,$norspb];

    $check = DB::connection('SML')->select('select * from DBOutBrg where nobukti = :nobukti',["nobukti" => $nobukti]);
    if ($check) {
      return 2;
    }
    $check = DB::connection('SML')->select('select * from DBOutBrg where idToken = :idToken',["idToken" => $idToken]);
    if ($check) {
      return 3;
    }

    $checkSO = 1;

    foreach ($data as $d) {
      // code...
      $checkSOtemp = DB::connection("SML")->select('exec SP_CheckPL ?,?',[ $d['NoBukti'], $d['Urut'] ]);



      if ($checkSOtemp) {
        if ($checkSOtemp[0]->Sisa < $d['QntXz']) {
          $checkSO = 0;
        }
      } else {
        $checkSO = 0;
      }
    }

    if ($checkSO == 0) {
      return 3;
    }

    // SP_CheckPL
    // $outstanding = DB::connection("SML")->select('exec sp_sosiapkirim ?,?,?',[ 9, 10 ,'zxc']);
    //
    // if ($outstanding) {
    //   return 'asd123';
    // } else {
    //   return 'qwe456';
    // }
    //
    // return [
    //   "a" => $outstanding,
    //   "b" => $outstanding[0],
    //   "c" => $outstanding[0]->NoBukti
    // ];

    foreach ($data as $d) {
      $values = [
        $choice,
        $nobukti,
        $nourut,
        $tanggal,
        '',
        '',
        '',
        $keterangan,
        0,
        $d['Kodebrg'],
        $d['namabrg'],
        $d['QntXz'],
        $d['nosat'],
        $d['satuan'],
        $d['isi'],
        $d['NoBukti'],
        $d['Urut'],
        $username,
        $req->input('tanggalInput'),
        '',
        $d['Tipe'],
        $idToken
      ];
      DB::connection("SML")->statement('exec Sp_OutBrg ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?',$values);
    }
      return 1;


  }

  public function spDelete (Request $req) {

    $choice = $req->input('choice');
    $nobukti = $req->input('nobukti');
    $urut = $req->input('urut');


      $values = [
        $choice,
        $nobukti,
        '',
        '',
        '',
        '',
        '',
        '',
        $urut,
        '',
        '',
        0,
        0,
        '',
        0,
        '',
        0,
        '',
        '',
        ''
      ];
      DB::connection("SML")->statement('exec Sp_OutBrg ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?',$values);

      return 1;


  }









}
