<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PDRBPengeluaranLajuPDRB extends Model
{
    protected $table = 'p_d_r_b_pengeluaran_laju_p_d_r_b';

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

