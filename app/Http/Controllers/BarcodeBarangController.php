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
			// Diubah 2026-10-20 (lemot): seluruh barang (±19 ribu baris) tidak lagi dirender di HTML -
			// tabel diisi per halaman lewat dataTabel().
			// $gudang = VWBarcodeBarang::All();
			$gudang = collect();
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

	// Ditambahkan 2026-10-20: data tabel Barcode Barang per halaman untuk DataTables (serverSide) -
	// cari, urut, dan Tampilkan dikerjakan di SQL (ROW_NUMBER, SQL Server 2008 R2 tanpa OFFSET).
	public function dataTabel(Request $req) {
		$kolomUrut = [0 => 'KODEBRG', 1 => 'NAMABRG'];
		$urut = $req->input('order.0', []);
		$kolom = $kolomUrut[(int) ($urut['column'] ?? 0)] ?? 'KODEBRG';
		$arah = strtolower($urut['dir'] ?? 'asc') === 'desc' ? 'desc' : 'asc';

		$where = '1 = 1';
		$bind = [];
		$cari = trim((string) $req->input('search.value', ''));
		if ($cari !== '') {
			$pola = '%' . str_replace(['[', '%', '_'], ['[[]', '[%]', '[_]'], $cari) . '%';
			$where = 'KODEBRG like cast(? as varchar(200)) or NAMABRG like cast(? as varchar(200))';
			$bind = [$pola, $pola];
		}

		$db = DB::connection('sqlsrv');
		$total = (int) $db->selectOne('select count(*) as n from vwDBBarang')->n;
		$tersaring = $cari === '' ? $total : (int) $db->selectOne("select count(*) as n from vwDBBarang where $where", $bind)->n;

		$mulai = max(0, (int) $req->input('start', 0));
		$panjang = (int) $req->input('length', 10);
		$sql = "select KODEBRG, NAMABRG from (select row_number() over (order by $kolom $arah, KODEBRG asc) as rn_urut, KODEBRG, NAMABRG"
			. " from vwDBBarang where $where) x";
		if ($panjang > 0) {
			$sql .= ' where rn_urut between ? and ?';
			$bind[] = $mulai + 1;
			$bind[] = $mulai + $panjang;
		}
		$sql .= ' order by rn_urut';

		return [
			'draw' => (int) $req->input('draw', 0),
			'recordsTotal' => $total,
			'recordsFiltered' => $tersaring,
			'data' => $db->select($sql, $bind),
		];
	}





}

?>
