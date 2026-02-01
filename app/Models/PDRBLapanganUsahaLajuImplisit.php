<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PDRBLapanganUsahaLajuImplisit extends Model
{
    protected $table = 'p_d_r_b_lapangan_usaha_laju_implisit';

    protected $fillable = [
        'industry_category',
        'year',
        'preliminary_flag',
        'value',
    ];

    protected $casts = [
        'value' => 'decimal:2',
    ];
}

