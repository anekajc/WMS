<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\NewMenu;
use App\Model\NewMenuReport;
use App\Model\NewAksesMenu;
use App\Model\NewPeriode;
use App\Model\NewUsers;
use Illuminate\Support\Facades\DB;

class NewMenuController extends Controller
{


  public function index () {
    $periode = NewPeriode::where('user_id' , \Auth::id())->first();
    $menul0 = NewMenu::where('l0' , 0)->orderBy('KODEMENU')->get();
    $menul1 = NewMenu::where('l0' , 1)->orderBy('KODEMENU')->get();
    $menul2 = NewMenu::where('l0' , 2)->orderBy('KODEMENU')->get();

    foreach ($menul1 as $menu1) {
      $array = [];
      $kodecheck = $menu1['KODEMENU'];
      foreach ($menul2 as $menu2) {
          // array_push($array, $kodecheck);
          if (substr($menu2['KODEMENU'],0, strlen($kodecheck)) == $kodecheck) {
            array_push($array, $menu2);
          }
      }
      $menu1->child = $array;

    }

    foreach ($menul0 as $menu0) {

      $array = [];
      $kodecheck = $menu0['KODEMENU'];
      foreach ($menul1 as $menu1) {
        if (substr($menu1['KODEMENU'],0,strlen($kodecheck)) == $kodecheck) {
          array_push($array, $menu1);
        }
      }
        $menu0->child = $array;
    }

    $menu = NewMenu::orderBy('KODEMENU')->get();

    return view('newmenu' , [
      // "title" => "Home",
      "periode" => $periode,
      "menu" => $menu,
      "menul0" => $menul0,
      // "menul1" => $menul1,
      // "menul2" => $menul2,
    ]);
  }

  public function getMenuL0 () {
    $menul0 = NewMenu::where('l0' , 0)->join('new_aksesmenu', 'new_aksesmenu.L1' ,'=' , 'new_menu.KODEMENU')->where('new_aksesmenu.USERID' , \Auth::id())->where('new_aksesmenu.HASACCESS' , 1)->orderBy('KODEMENU')->get();
    $menul1 = NewMenu::where('l0' , 1)->join('new_aksesmenu', 'new_aksesmenu.L1' ,'=' , 'new_menu.KODEMENU')->where('new_aksesmenu.USERID' , \Auth::id())->where('new_aksesmenu.HASACCESS' , 1)->orderBy('KODEMENU')->get();
    $menul2 = NewMenu::where('l0' , 2)->join('new_aksesmenu', 'new_aksesmenu.L1' ,'=' , 'new_menu.KODEMENU')->where('new_aksesmenu.USERID' , \Auth::id())->where('new_aksesmenu.HASACCESS' , 1)->orderBy('KODEMENU')->get();

    // $menul0 = DB::connection("SML")->select('select * from new_menu where l0 = 0 order by KODEMENU');
    // $menul1 = [];
    // $menul2 = [];

    foreach ($menul1 as $menu1) {
      $array = [];
      $kodecheck = $menu1['KODEMENU'];
      foreach ($menul2 as $menu2) {
          // array_push($array, $kodecheck);
          if (substr($menu2['KODEMENU'],0, strlen($kodecheck)) == $kodecheck) {
            array_push($array, $menu2);
          }
      }
      $menu1->child = $array;

    }

    foreach ($menul0 as $menu0) {

      $array = [];
      $kodecheck = $menu0['KODEMENU'];
      foreach ($menul1 as $menu1) {
        if (substr($menu1['KODEMENU'],0,strlen($kodecheck)) == $kodecheck) {
          array_push($array, $menu1);
        }
      }
        $menu0->child = $array;
    }


    $menureportl0 = NewMenuReport::where('l0' , 0)->join('new_aksesmenureport', 'new_aksesmenureport.L1' ,'=' , 'new_menureport.KODEMENU')->where('new_aksesmenureport.USERID' , \Auth::id())->where('new_aksesmenureport.HASACCESS' , 1)->orderBy('KODEMENU')->get();
    $menureportl1 = NewMenuReport::where('l0' , 1)->join('new_aksesmenureport', 'new_aksesmenureport.L1' ,'=' , 'new_menureport.KODEMENU')->where('new_aksesmenureport.USERID' , \Auth::id())->where('new_aksesmenureport.HASACCESS' , 1)->orderBy('KODEMENU')->get();
    $menureportl2 = NewMenuReport::where('l0' , 2)->join('new_aksesmenureport', 'new_aksesmenureport.L1' ,'=' , 'new_menureport.KODEMENU')->where('new_aksesmenureport.USERID' , \Auth::id())->where('new_aksesmenureport.HASACCESS' , 1)->orderBy('KODEMENU')->get();

    foreach ($menureportl1 as $menureport1) {
      $array = [];
      $kodecheck = $menureport1['KODEMENU'];
      foreach ($menureportl2 as $menureport2) {
          // array_push($array, $kodecheck);
          if (substr($menureport2['KODEMENU'],0, strlen($kodecheck)) == $kodecheck) {
            array_push($array, $menureport2);
          }
      }
      $menureport1->child = $array;

    }

    foreach ($menureportl0 as $menureport0) {

      $array = [];
      $kodecheck = $menureport0['KODEMENU'];
      foreach ($menureportl1 as $menureport1) {
        if (substr($menureport1['KODEMENU'],0,strlen($kodecheck)) == $kodecheck) {
          array_push($array, $menureport1);
        }
      }
        $menureport0->child = $array;
    }

    $arraymenu = [];

    foreach ($menul0 as $menu) {
      // code...
      array_push($arraymenu, $menu);
    }

    foreach ($menureportl0 as $menureport) {
      // code...
      array_push($arraymenu, $menureport);
    }


    return $arraymenu;
  }



  // ISTAMBAH, ISKOREKSI, ISHAPUS, ISCETAK , ISEXPORT , IsOtorisasi1, IsOtorisasi2 , IsOtorisasi3 , IsOtorisasi4, IsOtorisasi5 ,
  // TIPE, IsBatal, pembatalan

  public function addMenu (Request $req) {
    // return 12345;
      DB::beginTransaction();

      try{
        // return "balik";
        $check = NewMenu::where('KODEMENU', $req->input('KODEMENU'))->count();
        // return $check;
        if($check == 0) {
          NewMenu::create([
            'KODEMENU'=> $req->input('KODEMENU'),
            'Keterangan'=> $req->input('Keterangan'),
            'L0'=> $req->input('L0') ,
            'ACCESS' => $req->input('ACCESS'),
            'href' => $req->input('href')
          ]);
          $users = NewUsers::all();
          foreach ($users as $u) {
            $access = False;
            if ($u['username'] == 'admin') {
              $access = True;
            }
            NewAksesMenu::create([
              'USERID' => $u->id,
              'L1' => $req->input('KODEMENU'),
              'HASACCESS' => $access
            ]);
          }
          DB::commit();
          return 1;
        } else {
          return 0;
        }

      } catch (\Exception $e) {
        DB::rollback();
      }


  }

  public function editMenu (Request $req) {
    DB::beginTransaction();

    try{

      $check = NewMenu::where('KODEMENU', $req->input('KODEMENU'))->count();
      if($check == 0 ) {
        NewMenu::where('KODEMENU' , $req->input('kodelama'))->update([
          'KODEMENU'=> $req->input('KODEMENU'),
          'Keterangan'=> $req->input('Keterangan'),
          'L0'=> $req->input('L0') ,
          'ACCESS' => $req->input('ACCESS'),
          'href' => $req->input('href')
        ]);

        NewAksesMenu::where('L1' , $req->input('kodelama'))->update([
        'L1' => $req->input('KODEMENU')
        ]);

        DB::commit();
        return 1;
      } else if (  $req->input('KODEMENU') ==  $req->input('kodelama')) {
        NewMenu::where('KODEMENU' , $req->input('kodelama'))->update([
          'Keterangan'=> $req->input('Keterangan'),
          'L0'=> $req->input('L0') ,
          'ACCESS' => $req->input('ACCESS'),
          'href' => $req->input('href')
        ]);
        return 1;
      } else {
        return 0;
      }



    } catch (\Exception $e) {
      DB::rollback();
    }
  }

  public function deleteMenu (Request $req) {
    DB::beginTransaction();

    try {
      NewMenu::where('KODEMENU' , $req->input('KODEMENU'))->delete();

      NewAksesMenu::where('L1' , $req->input('KODEMENU'))->delete();

    } catch (\Exception $e) {
      DB::rollback();
    }
  }
}
