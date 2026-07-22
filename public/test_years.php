<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
$years = collect();
$years = $years->merge(App\Models\PDRBLapanganUsahaADHB::distinct()->pluck('year'))
               ->merge(App\Models\PDRBLapanganUsahaADHK::distinct()->pluck('year'))
               ->merge(App\Models\PDRBLapanganUsahaDistribusi::distinct()->pluck('year'))
               ->merge(App\Models\PDRBLapanganUsahaLajuPDRB::distinct()->pluck('year'))
               ->merge(App\Models\PDRBLapanganUsahaLajuImplisit::distinct()->pluck('year'))
               ->merge(App\Models\PDRBLapanganUsahaADHBTriwulanan::distinct()->pluck('year'))
               ->merge(App\Models\PDRBLapanganUsahaADHKTriwulanan::distinct()->pluck('year'))
               ->merge(App\Models\PDRBLapanganUsahaDistribusiTriwulanan::distinct()->pluck('year'))
               ->merge(App\Models\PDRBLapanganUsahaLajuQtoQ::distinct()->pluck('year'))
               ->merge(App\Models\PDRBLapanganUsahaLajuYtoY::distinct()->pluck('year'))
               ->merge(App\Models\PDRBLapanganUsahaLajuCtoC::distinct()->pluck('year'));
echo json_encode($years->unique()->sort()->values()->toArray());
