<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PDRBPengeluaranADHKTriwulanan extends Model
{
    protected $table = 'p_d_r_b_pengeluaran_a_d_h_k_triwulanan';

    protected $fillable = [
        'expenditure_category',
        'year',
        'quarter',
        'preliminary_flag',
        'value',
    ];

    protected $casts = [
        'value' => 'decimal:2',
    ];
}

