<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class NewPeriode extends Model
{
		protected $connection = 'SML';
    protected $primaryKey = 'id';
    protected $table = 'new_periode';
    protected $fillable = array(
        'user_id',
        'bulan',
        'tahun'
    );
    public $timestamps = false;
}

?>
