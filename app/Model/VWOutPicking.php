<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class VWOutPicking extends Model
{
		protected $connection = 'SML';
    protected $table = 'VWOutPicking';
    // protected $fillable = array(
    //
    // );
    public $timestamps = false;
}

?>
