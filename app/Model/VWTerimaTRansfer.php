<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class VWTerimaTRansfer extends Model
{
		protected $connection = 'SML';
    protected $table = 'VWTerimaTRansfer';
    // protected $fillable = array(
    //
    // );
    public $timestamps = false;
}

?>
