<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\BPSInfographicService;

class SyncBPSInfographics extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:bps-infographics';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync infographics data from BPS API';

    protected $infographicService;

    /**
     * Create a new command instance.
     */
    public function __construct(BPSInfographicService $infographicService)
    {
        parent::__construct();
        $this->infographicService = $infographicService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting BPS Infographics synchronization...');
        
        try {
            $result = $this->infographicService->syncInfographic();
            
            $this->info("Synchronization completed!");
            $this->info("Created: {$result['created']} records");
            $this->info("Updated: {$result['updated']} records");
            
            if ($result['created'] == 0 && $result['updated'] == 0) {
                $this->warn("⚠️  No records were created or updated. Please check the logs for details.");
                $this->warn("Make sure the migration has been run: php artisan migrate");
            }
            
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error("Error: " . $e->getMessage());
            $this->error("Stack trace: " . $e->getTraceAsString());
            return Command::FAILURE;
        }
    }
}

