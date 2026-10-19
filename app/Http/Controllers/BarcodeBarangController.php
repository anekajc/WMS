<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\DB;
// use App\Model\Menu;
// use App\Model\User;
use App\Model\GudangSQL;
use App\Model\RakSQL;
use App\Model\LokasiSQL;
// use App\Model\Periode;
// use App\Model\AksesMenu;
use App\Model\AlokasiBrg;
use App\Model\AlokasiBrgDet;
use App\Model\NewMenu;
use App\Model\NewAksesMenu;
use App\Model\NewPeriode;
use App\Model\NewUsers;
use App\Model\VWBarcodeBarang;

class BarcodeBarangController extends Controller {

	public function index() {


			$kodemenu = '0091';
	    $akses = NewAksesMenu::where('USERID', \Auth::id())-> where('L1', $kodemenu)->first();
	    if(!$akses || !$akses->HASACCESS) {
	       return redirect('/home');
	    }

			$menul0 = app('App\Http\Controllers\NewMenuController')->getMenuL0();

			//$gudang = 	VWBarcodeBarang::where('KODEGRP', 'BJ');
			//$gudang = GudangSQL::All();
			$gudang = VWBarcodeBarang::All();
			$periode = NewPeriode::where('user_id', \Auth::id())->first();
			return view("barcodebarang")
			->with('menul0', $menul0)
			->with('gudang', $gudang)
			->with('periode', $periode)
			->with('akses', $akses)
			// ->with('akses', $check)
			;

	}

	public function loadAll() {
		return 	VWBarcodeBarang::All();
		//return GudangSQL::All();

	}





}

?>
