<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class VwPersiapan extends Model
{
		protected $connection = 'SML';
    protected $table = 'VwPersiapan';
    // protected $fillable = array(
    //
    // );
    public $timestamps = false;
}

?>
