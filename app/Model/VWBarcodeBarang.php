<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class VWBarcodeBarang extends Model
{
		protected $connection = 'sqlsrv';
    protected $table = 'vwDBBarang';
    protected $fillable = array(
        'KODEBRG',
				'NAMABRG',
				'KODEGRP',
				'KODESUBGRP',
				'KODESUPP',
				'SAT1',
				'ISI1',
				'SAT2',
				'ISI2'


    );
    public $timestamps = false;
}

?>
