<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KemiskinanJawaTimur extends Model
{
    protected $table = 'kemiskinan_jawa_timur';

    protected $fillable = [
        'year',
        'jumlah_penduduk_miskin',
        'persentase_penduduk_miskin',
        'indeks_kedalaman_kemiskinan_p1',
        'indeks_keparahan_kemiskinan_p2',
        'garis_kemiskinan',
    ];

    protected $casts = [
        'jumlah_penduduk_miskin' => 'decimal:3',
        'persentase_penduduk_miskin' => 'decimal:2',
        'indeks_kedalaman_kemiskinan_p1' => 'decimal:2',
        'indeks_keparahan_kemiskinan_p2' => 'decimal:2',
        'garis_kemiskinan' => 'decimal:2',
    ];
}
