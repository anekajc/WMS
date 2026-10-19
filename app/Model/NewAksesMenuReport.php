<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class NewAksesMenuReport extends Model
{
		protected $connection = 'SML';
    protected $primaryKey = 'id';
    protected $table = 'new_aksesmenureport';
    protected $fillable = array(
        'USERID',
				'L1',
        'HASACCESS',
				'ISDESIGN',
				'ISEXPORT'
    );
    public $timestamps = false;
}

?>
