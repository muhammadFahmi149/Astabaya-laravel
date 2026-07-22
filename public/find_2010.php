<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$tables = [
    'App\Models\PDRBLapanganUsahaADHB',
    'App\Models\PDRBLapanganUsahaADHK',
    'App\Models\PDRBLapanganUsahaDistribusi',
    'App\Models\PDRBLapanganUsahaLajuPDRB',
    'App\Models\PDRBLapanganUsahaLajuImplisit',
    'App\Models\PDRBLapanganUsahaADHBTriwulanan',
    'App\Models\PDRBLapanganUsahaADHKTriwulanan',
    'App\Models\PDRBLapanganUsahaDistribusiTriwulanan',
    'App\Models\PDRBLapanganUsahaLajuQtoQ',
    'App\Models\PDRBLapanganUsahaLajuYtoY',
    'App\Models\PDRBLapanganUsahaLajuCtoC',
];

$output = [];
foreach ($tables as $model) {
    if (class_exists($model)) {
        $years = $model::distinct()->pluck('year')->toArray();
        if (in_array(2010, $years)) {
            $output[] = class_basename($model);
        }
    }
}
echo json_encode(['tables_with_2010' => $output]);
