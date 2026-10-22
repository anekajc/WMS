<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\NewMenu;
use App\Model\NewAksesMenu;
use App\Model\NewPeriode;
use App\Model\NewUsers;
use Illuminate\Support\Facades\DB;
use App\Model\vwDetailKoreksiWEb;



class NewOpnameGudangController extends Controller
{




  public function index () {

    $kodemenu = '033';
    $akses = NewAksesMenu::where('USERID', \Auth::id())-> where('L1', $kodemenu)->first();
    if(!$akses || !$akses->HASACCESS) {
       return redirect('/home');
    }

    $periode = NewPeriode::where('user_id' , \Auth::id())->first();
    $menul0 = app('App\Http\Controllers\NewMenuController')->getMenuL0();


    $gudang = DB::connection("SML")->select('select * from DBGUDANG where KODEGDG <> :id', ['id' => 'GTC']);


    // Dinonaktifkan 2026-10-22: memuat seluruh view lalu menyaring di PHP (lambat).
    // $penerimaan = vwDetailKoreksiWEb::all()->where('Bulan',$periode->bulan )->where('Tahun', $periode->tahun)->sortBy('urut')->groupBy('Nobukti');
    // Pengganti: saringan yang sama langsung di query database (hasil sama, jauh lebih cepat).
    $penerimaan = vwDetailKoreksiWEb::where('Bulan', $periode->bulan)->where('Tahun', $periode->tahun)->get()->sortBy('urut')->groupBy('Nobukti');
    $tempPenerimaan = [];
    foreach ($penerimaan as $p) {
      // code...
      array_push($tempPenerimaan, $p);
    }


    return view('newopnamegudang' , [
      "periode" => $periode,
      "menul0" => $menul0,
      "gudang" => $gudang,
      "penerimaanArray" => $tempPenerimaan,
      "akses" => $akses
    ]);
  }

  public function loadAll () {
    $periode = NewPeriode::where('user_id' , \Auth::id())->first();
    // Dinonaktifkan 2026-10-22: memuat seluruh view lalu menyaring di PHP (lambat).
    // $penerimaan = vwDetailKoreksiWEb::all()->where('Bulan',$periode->bulan )->where('Tahun', $periode->tahun)->sortBy('urut')->groupBy('Nobukti');
    // Pengganti: saringan yang sama langsung di query database (hasil sama, jauh lebih cepat).
    $penerimaan = vwDetailKoreksiWEb::where('Bulan', $periode->bulan)->where('Tahun', $periode->tahun)->get()->sortBy('urut')->groupBy('Nobukti');
    $tempPenerimaan = [];
    foreach ($penerimaan as $p) {
      // code...
      array_push($tempPenerimaan, $p);
    }

    return [
      "penerimaanArray" => $tempPenerimaan
    ];
  }

  public function getDetailPenerimaan (Request $req) {
    // Dinonaktifkan 2026-10-22: memuat seluruh view lalu menyaring di PHP (lambat).
    // $detailPenerimaan = vwDetailKoreksiWEb::all()->where('Nobukti',$req->input('NOBUKTI') )->sortBy('urut');
    // Pengganti: saringan yang sama langsung di query database (hasil sama, jauh lebih cepat).
    $detailPenerimaan = vwDetailKoreksiWEb::whereRaw('Nobukti = cast(? as varchar(50))', [(string) $req->input('NOBUKTI')])->get()->sortBy('urut');
    $tempPenerimaan = [];
    foreach ($detailPenerimaan as $p) {
      // code...
      array_push($tempPenerimaan, $p);
    }
    return $tempPenerimaan;
  }

  public function getDetailItem (Request $req) {
    $periode = NewPeriode::where('user_id' , \Auth::id())->first();
    $values = [
      $req->input('kodebarang'),
      $req->input('kodegudang'),
      $req->input('kodelokasi'),
      $periode->bulan,
      $periode->tahun,
    ];
    //     @KODEBRG varchar(30),
    // @KODEGDG varchar(20),
    // @KODELOKASI varchar(30),
    // @BULAN int,
    // @TAHUN int
    // $gudang = DB::connection("SML")->select('select * from DBGUDANG where KODEGDG <> :id', ['id' => 'GTC']);

    $item = DB::connection('SML')->select('exec SP_koreksigudanglistitem ?,?,?,?,?',$values);

    return $item;
  }

  public function getNoBukti (Request $req) {

    $periode = NewPeriode::where('user_id' , \Auth::id())->first();
    // $inisial = DB::connection("SML")->select('select PBL from DBNOMOR');
    $inisial = DB::connection("SML")->select('select OPN from DBNOMOR');
    $username = \Auth::user()->username;
    // return [$periode->bulan,$inisial[0]->PBL,$username];
    $values = [
        $inisial[0]->OPN,
        $periode->bulan,
        $periode->tahun,
        $username
    ];
      $noBukti = DB::connection('SML')->select('exec SP_IsiNobukti ?,?,?,?',$values);
    return $noBukti;
  }

  public function spCheck (Request $req) {

      $checkBarang = DB::connection('SML')->select('select * from dbbarang where KODEBRG = :kode',["kode" => $req->input('kode')]);
      $checkLokasi = DB::connection('SML')->select('select * from dbLokasiSIG where BarcodeLoc = :kode',["kode" => $req->input('kode')]);

      return [
        "checkBarang" => $checkBarang,
        "checkLokasi" => $checkLokasi
      ];
  }

  public function addForm (Request $req) {

    $data = $req->input('tempData');
    $choice = $req->input('choice');
    $noopn =  $req->input('noopn');
    $nourut = $req->input('nourut');
    $date = $req->input('inputDate');
    $note = $req->input('note');
    $keterangan = $req->input('keterangan');
    // $urut = 0;
    // 'SML/OPN/00002/0123'
    if($req->input('jmlRecord') == 0) {

      $check = DB::connection('SML')->select('select * from DBKOREKSI where NOBUKTI = :nobukti',["nobukti" => $noopn]);
      if ($check) {
        return 2;
      }
    }


    foreach ($data as $d) {
      $values = [
        $choice,
        $noopn,
        $nourut,
        $date,
        $note,
        $d['urut'],
        $d['Kodebrg'],
        $d['Kodegdg'],
        $d['SaldoComp'],
        $d['inputQnt'],
        $d['Selisih'],
        $d['inputQntDebet'],
        $d['inputQntKredit'],
        $d['NoSat'],
        $d['Isi'],
        $d['Satuan'],
        $d['Harga'],
        $keterangan,
        $d['Saldo2Comp'],
        $d['Qnt2Opname'],
        $d['Selisih2'],
        $d['Qnt2DB'],
        $d['Qnt2CR'],
        $d['isCek'],
        $d['isCek2'],
        $d['Tipe'],
        $d['Kodelokasi'],
        $d['Rak']
      ];
      DB::connection("SML")->statement('exec SP_KOREKSIWEB ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?',$values);

    }

    return 1;

  }









}
