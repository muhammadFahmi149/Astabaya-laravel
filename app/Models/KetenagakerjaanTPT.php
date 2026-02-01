<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KetenagakerjaanTPT extends Model
{
    protected $table = 'ketenagakerjaan_tpt';
    
    protected $fillable = [
        'year',
        'laki_laki',
        'perempuan',
        'total',
    ];

    protected $casts = [
        'laki_laki' => 'decimal:2',
        'perempuan' => 'decimal:2',
        'total' => 'decimal:2',
    ];
}
