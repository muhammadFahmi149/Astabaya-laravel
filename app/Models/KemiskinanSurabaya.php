<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KemiskinanSurabaya extends Model
{
    protected $table = 'kemiskinan_surabaya';

    protected $fillable = [
        'year',
        'jumlah_penduduk_miskin',
        'persentase_penduduk_miskin',
        'indeks_kedalaman_kemiskinan_p1',
        'indeks_keparahan_kemiskinan_p2',
        'garis_kemiskinan',
    ];

    protected $casts = [
        'year' => 'integer',
        'jumlah_penduduk_miskin' => 'float',
        'persentase_penduduk_miskin' => 'float',
        'indeks_kedalaman_kemiskinan_p1' => 'float',
        'indeks_keparahan_kemiskinan_p2' => 'float',
        'garis_kemiskinan' => 'float',
    ];
}
