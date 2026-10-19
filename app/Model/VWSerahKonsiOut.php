<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class VWSerahKonsiOut extends Model
{
		protected $connection = 'SML';
    protected $table = 'VWSerahKonsiOut';
    // protected $fillable = array(
    //
    // );
    public $timestamps = false;
}

?>
