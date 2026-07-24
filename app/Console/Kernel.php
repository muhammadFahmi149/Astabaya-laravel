<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // ---------------------------------------------------------
        // BLOK 1: BPS API (Publikasi, Berita, Infografis)
        // ---------------------------------------------------------
        $schedule->command('sync:bps-publications')
            ->dailyAt('02:00')->timezone('Asia/Jakarta')
            ->withoutOverlapping()->runInBackground();

        $schedule->command('sync:bps-news')
            ->dailyAt('02:05')->timezone('Asia/Jakarta')
            ->withoutOverlapping()->runInBackground();

        $schedule->command('sync:bps-infographics')
            ->dailyAt('02:10')->timezone('Asia/Jakarta')
            ->withoutOverlapping()->runInBackground();

        // ---------------------------------------------------------
        // BLOK 2: Indikator Google Sheets (Ringan-Menengah)
        // ---------------------------------------------------------
        $schedule->command('sync:google-sheets --service=gini')
            ->dailyAt('03:00')->timezone('Asia/Jakarta')
            ->withoutOverlapping()->runInBackground();

        $schedule->command('sync:google-sheets --service=hotel')
            ->dailyAt('03:02')->timezone('Asia/Jakarta')
            ->withoutOverlapping()->runInBackground();

        $schedule->command('sync:google-sheets --service=ipm')
            ->dailyAt('03:04')->timezone('Asia/Jakarta')
            ->withoutOverlapping()->runInBackground();

        $schedule->command('sync:google-sheets --service=kemiskinan')
            ->dailyAt('03:06')->timezone('Asia/Jakarta')
            ->withoutOverlapping()->runInBackground();

        $schedule->command('sync:google-sheets --service=kependudukan')
            ->dailyAt('03:08')->timezone('Asia/Jakarta')
            ->withoutOverlapping()->runInBackground();

        $schedule->command('sync:google-sheets --service=ketenagakerjaan')
            ->dailyAt('03:10')->timezone('Asia/Jakarta')
            ->withoutOverlapping()->runInBackground();

        // ---------------------------------------------------------
        // BLOK 3: Indikator Google Sheets (Berat - Jeda 10 Menit)
        // ---------------------------------------------------------
        $schedule->command('sync:google-sheets --service=inflasi')
            ->dailyAt('03:20')->timezone('Asia/Jakarta')
            ->withoutOverlapping()->runInBackground();

        $schedule->command('sync:google-sheets --service=pdrb')
            ->dailyAt('03:25')->timezone('Asia/Jakarta')
            ->withoutOverlapping()->runInBackground();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}

