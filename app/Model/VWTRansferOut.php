<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class VWTRansferOut extends Model
{
		protected $connection = 'SML';
    protected $table = 'VWTRansferOut';
    // protected $fillable = array(
    //
    // );
    public $timestamps = false;
}

?>
