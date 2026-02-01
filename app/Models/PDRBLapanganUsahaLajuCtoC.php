<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PDRBLapanganUsahaLajuCtoC extends Model
{
    protected $table = 'p_d_r_b_lapangan_usaha_laju_cto_c';

    protected $fillable = [
        'industry_category',
        'year',
        'quarter',
        'preliminary_flag',
        'value',
    ];

    protected $casts = [
        'value' => 'decimal:2',
    ];
}

