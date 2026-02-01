<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PDRBPengeluaranLajuQtoQ extends Model
{
    protected $table = 'p_d_r_b_pengeluaran_laju_qto_q';

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

