# Laravel Scheduler Setup

Laravel scheduler digunakan untuk menggantikan Django APScheduler. Scheduler ini akan menjalankan sinkronisasi data BPS secara otomatis setiap hari jam 02:00 AM (WIB).

## Console Commands

### Available Commands

1. **Sync All BPS Data** (Recommended)
   ```bash
   php artisan sync:bps-all
   ```
   Sync semua data: News, Publications, dan Infographics

2. **Sync Individual Data**
   ```bash
   php artisan sync:bps-news
   php artisan sync:bps-publications
   php artisan sync:bps-infographics
   ```

## Scheduled Tasks

Scheduler dikonfigurasi di `app/Console/Kernel.php` untuk menjalankan:
- `sync:bps-all` setiap hari jam 02:00 AM (Asia/Jakarta timezone)

## Setup Scheduler di Server

### Untuk Development (Windows dengan Laragon)

1. Setup Task Scheduler Windows:
   - Buka Task Scheduler
   - Create Basic Task
   - Trigger: Daily at 02:00 AM
   - Action: Start a program
   - Program: `php.exe`
   - Arguments: `d:\laragon\www\astabaya\artisan schedule:run`
   - Start in: `d:\laragon\www\astabaya`

### Untuk Production (Linux/Unix)

Tambahkan cron job berikut ke crontab (`crontab -e`):

```bash
* * * * * cd /path-to-project && php artisan schedule:run >> /dev/null 2>&1
```

Ini akan menjalankan `schedule:run` setiap menit, dan Laravel akan memutuskan apakah perlu menjalankan scheduled tasks berdasarkan konfigurasi di `Kernel.php`.

### Manual Testing

Untuk test scheduler secara manual:

```bash
# Test scheduler (akan menjalankan tasks yang seharusnya berjalan sekarang)
php artisan schedule:run

# Lihat daftar scheduled tasks
php artisan schedule:list

# Test specific command
php artisan sync:bps-all
```

## Notes

- Scheduler menggunakan timezone `Asia/Jakarta` (WIB)
- `withoutOverlapping()` memastikan hanya satu instance yang berjalan pada satu waktu
- `runInBackground()` memungkinkan task berjalan di background tanpa blocking

