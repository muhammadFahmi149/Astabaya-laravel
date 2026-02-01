<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PDRBLapanganUsahaADHKTriwulanan extends Model
{
    protected $table = 'p_d_r_b_lapangan_usaha_a_d_h_k_triwulanan';

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

