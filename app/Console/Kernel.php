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
        // Sync all BPS data every day at 02:00 AM (WIB - Asia/Jakarta timezone)
        // Equivalent to Django APScheduler cron job: day_of_week='*', hour=2, minute=0
        $schedule->command('sync:bps-all')
            ->dailyAt('02:00')
            ->timezone('Asia/Jakarta')
            ->withoutOverlapping()
            ->runInBackground();

        // You can also schedule individual syncs if needed
        // $schedule->command('sync:bps-news')->dailyAt('02:00');
        // $schedule->command('sync:bps-publications')->dailyAt('02:30');
        // $schedule->command('sync:bps-infographics')->dailyAt('03:00');
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

