<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class VWUbahKemasanOut extends Model
{
		protected $connection = 'SML';
    protected $table = 'VWUbahKemasanOut';
    // protected $fillable = array(
    //
    // );
    public $timestamps = false;
}

?>
