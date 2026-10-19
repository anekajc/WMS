<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\NewMenu;
use App\Model\NewAksesMenu;
use App\Model\NewPeriode;
use App\Model\NewUsers;
use Illuminate\Support\Facades\DB;

class LockPeriodeController extends Controller
{






  public function check (Request $req) {
    // return 'tes';

    return $expedisi = DB::connection("SML")->select('select * from dblockperiode where TAHUN = :tahun and BULAN = :bulan', ['tahun' => $req->tahun  , 'bulan' => $req->bulan ]);


  }


















}
