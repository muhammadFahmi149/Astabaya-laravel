<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PDRBPengeluaranDistribusiTriwulanan extends Model
{
    protected $table = 'p_d_r_b_pengeluaran_distribusi_triwulanan';

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

