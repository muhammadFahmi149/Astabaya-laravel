<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PDRBPengeluaranDistribusi extends Model
{
    protected $table = 'p_d_r_b_pengeluaran_distribusi';

    protected $fillable = [
        'expenditure_category',
        'year',
        'preliminary_flag',
        'value',
    ];

    protected $casts = [
        'value' => 'decimal:2',
    ];
}

