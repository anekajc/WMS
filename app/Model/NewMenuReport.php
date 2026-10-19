<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class NewMenuReport extends Model
{
		protected $connection = 'SML';
    // protected $table = 'DBMENU';
		protected $table = 'new_menureport';
    protected $fillable = array(
        'KODEMENU',
        'Keterangan',
        'L0',
        'ACCESS',
				'href'
    );
    public $timestamps = false;
}


?>
