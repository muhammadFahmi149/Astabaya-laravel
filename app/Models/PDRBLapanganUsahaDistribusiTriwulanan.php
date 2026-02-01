<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PDRBLapanganUsahaDistribusiTriwulanan extends Model
{
    protected $table = 'p_d_r_b_lapangan_usaha_distribusi_triwulanan';

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

