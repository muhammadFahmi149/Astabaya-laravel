<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PDRBPengeluaranLajuYtoY extends Model
{
    protected $table = 'p_d_r_b_pengeluaran_laju_yto_y';

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

