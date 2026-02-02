<?php

namespace Database\Seeders;

use App\Models\KetenagakerjaanTPT;
use App\Models\KetenagakerjaanTPAK;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KetenagakerjaanSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Data TPT (Tingkat Pengangguran Terbuka)
        $tptData = [
            ['year' => 2020, 'total' => 7.07, 'laki_laki' => 7.12, 'perempuan' => 6.98],
            ['year' => 2021, 'total' => 6.49, 'laki_laki' => 6.64, 'perempuan' => 6.26],
            ['year' => 2022, 'total' => 5.86, 'laki_laki' => 5.82, 'perempuan' => 5.92],
            ['year' => 2023, 'total' => 5.45, 'laki_laki' => 5.38, 'perempuan' => 5.55],
            ['year' => 2024, 'total' => 5.12, 'laki_laki' => 5.05, 'perempuan' => 5.22],
        ];

        foreach ($tptData as $data) {
            KetenagakerjaanTPT::updateOrCreate(
                ['year' => $data['year']],
                $data
            );
        }

        // Data TPAK (Tingkat Partisipasi Angkatan Kerja)
        $tpakData = [
            ['year' => 2020, 'total' => 63.28, 'laki_laki' => 79.15, 'perempuan' => 47.82],
            ['year' => 2021, 'total' => 63.65, 'laki_laki' => 79.42, 'perempuan' => 48.21],
            ['year' => 2022, 'total' => 64.12, 'laki_laki' => 79.68, 'perempuan' => 48.85],
            ['year' => 2023, 'total' => 64.89, 'laki_laki' => 80.15, 'perempuan' => 49.56],
            ['year' => 2024, 'total' => 65.45, 'laki_laki' => 80.42, 'perempuan' => 50.12],
        ];

        foreach ($tpakData as $data) {
            KetenagakerjaanTPAK::updateOrCreate(
                ['year' => $data['year']],
                $data
            );
        }
    }
}
