<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class VWOutTRansfer extends Model
{
		protected $connection = 'SML';
    protected $table = 'VWOutTRansfer';
    // protected $fillable = array(
    //
    // );
    public $timestamps = false;
}

?>
