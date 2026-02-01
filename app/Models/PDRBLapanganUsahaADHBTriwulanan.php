<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PDRBLapanganUsahaADHBTriwulanan extends Model
{
    protected $table = 'p_d_r_b_lapangan_usaha_a_d_h_b_triwulanan';

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

