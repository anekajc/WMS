<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class OutSOSPBWEB extends Model
{
		protected $connection = 'SML';
    //protected $table = 'OutSOSPBWEB';
    protected $table = 'OutpackingSPBWEB';
    // protected $fillable = array(
    //
    // );
    public $timestamps = false;
}

?>
