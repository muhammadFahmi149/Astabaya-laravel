<?php

/**
 * Script untuk generate semua models PDRB dan IPM yang masih kurang
 * Run dengan: php scripts/generate_pdrb_models.php
 */

$pdrbModels = [
    // PDRB Pengeluaran
    ['name' => 'PDRBPengeluaranADHK', 'table' => 'p_d_r_b_pengeluaran_a_d_h_k', 'type' => 'yearly'],
    ['name' => 'PDRBPengeluaranDistribusi', 'table' => 'p_d_r_b_pengeluaran_distribusi', 'type' => 'yearly'],
    ['name' => 'PDRBPengeluaranLajuPDRB', 'table' => 'p_d_r_b_pengeluaran_laju_p_d_r_b', 'type' => 'yearly'],
    ['name' => 'PDRBPengeluaranADHBTriwulanan', 'table' => 'p_d_r_b_pengeluaran_a_d_h_b_triwulanan', 'type' => 'quarterly'],
    ['name' => 'PDRBPengeluaranADHKTriwulanan', 'table' => 'p_d_r_b_pengeluaran_a_d_h_k_triwulanan', 'type' => 'quarterly'],
    ['name' => 'PDRBPengeluaranDistribusiTriwulanan', 'table' => 'p_d_r_b_pengeluaran_distribusi_triwulanan', 'type' => 'quarterly'],
    ['name' => 'PDRBPengeluaranLajuQtoQ', 'table' => 'p_d_r_b_pengeluaran_laju_qto_q', 'type' => 'quarterly'],
    ['name' => 'PDRBPengeluaranLajuYtoY', 'table' => 'p_d_r_b_pengeluaran_laju_yto_y', 'type' => 'quarterly'],
    ['name' => 'PDRBPengeluaranLajuCtoC', 'table' => 'p_d_r_b_pengeluaran_laju_cto_c', 'type' => 'quarterly'],
    
    // PDRB Lapangan Usaha
    ['name' => 'PDRBLapanganUsahaADHB', 'table' => 'p_d_r_b_lapangan_usaha_a_d_h_b', 'type' => 'yearly'],
    ['name' => 'PDRBLapanganUsahaADHK', 'table' => 'p_d_r_b_lapangan_usaha_a_d_h_k', 'type' => 'yearly'],
    ['name' => 'PDRBLapanganUsahaDistribusi', 'table' => 'p_d_r_b_lapangan_usaha_distribusi', 'type' => 'yearly'],
    ['name' => 'PDRBLapanganUsahaLajuPDRB', 'table' => 'p_d_r_b_lapangan_usaha_laju_p_d_r_b', 'type' => 'yearly'],
    ['name' => 'PDRBLapanganUsahaLajuImplisit', 'table' => 'p_d_r_b_lapangan_usaha_laju_implisit', 'type' => 'yearly'],
    ['name' => 'PDRBLapanganUsahaADHBTriwulanan', 'table' => 'p_d_r_b_lapangan_usaha_a_d_h_b_triwulanan', 'type' => 'quarterly'],
    ['name' => 'PDRBLapanganUsahaADHKTriwulanan', 'table' => 'p_d_r_b_lapangan_usaha_a_d_h_k_triwulanan', 'type' => 'quarterly'],
    ['name' => 'PDRBLapanganUsahaDistribusiTriwulanan', 'table' => 'p_d_r_b_lapangan_usaha_distribusi_triwulanan', 'type' => 'quarterly'],
    ['name' => 'PDRBLapanganUsahaLajuQtoQ', 'table' => 'p_d_r_b_lapangan_usaha_laju_qto_q', 'type' => 'quarterly'],
    ['name' => 'PDRBLapanganUsahaLajuYtoY', 'table' => 'p_d_r_b_lapangan_usaha_laju_yto_y', 'type' => 'quarterly'],
    ['name' => 'PDRBLapanganUsahaLajuCtoC', 'table' => 'p_d_r_b_lapangan_usaha_laju_cto_c', 'type' => 'quarterly'],
];

$ipmModels = [
    ['name' => 'IPM_UHH_SP', 'table' => 'i_p_m__u_h_h__s_ps'],
    ['name' => 'IPM_HLS', 'table' => 'i_p_m__h_l_s'],
    ['name' => 'IPM_RLS', 'table' => 'i_p_m__r_l_s'],
    ['name' => 'IPM_PengeluaranPerKapita', 'table' => 'i_p_m_pengeluaran_per_kapitas'],
    ['name' => 'IPM_IndeksKesehatan', 'table' => 'i_p_m_indeks_kesehatans'],
    ['name' => 'IPM_IndeksHidupLayak', 'table' => 'i_p_m_indeks_hidup_layaks'],
    ['name' => 'IPM_IndeksPendidikan', 'table' => 'i_p_m_indeks_pendidikans'],
];

foreach ($pdrbModels as $model) {
    echo "Creating model {$model['name']}...\n";
    $command = "php artisan make:model {$model['name']} -m 2>&1";
    exec($command, $output, $return);
    if ($return === 0) {
        echo "✓ Created {$model['name']}\n";
    } else {
        echo "✗ Failed to create {$model['name']}: " . implode("\n", $output) . "\n";
    }
}

foreach ($ipmModels as $model) {
    echo "Creating model {$model['name']}...\n";
    $command = "php artisan make:model {$model['name']} -m 2>&1";
    exec($command, $output, $return);
    if ($return === 0) {
        echo "✓ Created {$model['name']}\n";
    } else {
        echo "✗ Failed to create {$model['name']}: " . implode("\n", $output) . "\n";
    }
}

echo "\nDone!\n";

