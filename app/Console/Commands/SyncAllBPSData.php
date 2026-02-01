<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\BPSNewsService;
use App\Services\BPSPublicationService;
use App\Services\BPSInfographicService;

class SyncAllBPSData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:bps-all';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync all BPS data (News, Publications, Infographics)';

    protected $newsService;
    protected $publicationService;
    protected $infographicService;

    /**
     * Create a new command instance.
     */
    public function __construct(
        BPSNewsService $newsService,
        BPSPublicationService $publicationService,
        BPSInfographicService $infographicService
    ) {
        parent::__construct();
        $this->newsService = $newsService;
        $this->publicationService = $publicationService;
        $this->infographicService = $infographicService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting synchronization of all BPS data...');
        $this->newLine();

        // Sync News
        $this->info('1. Syncing News...');
        try {
            $result = $this->newsService->syncNews();
            $this->info("   ✓ News: Created {$result['created']}, Updated {$result['updated']}");
        } catch (\Exception $e) {
            $this->error("   ✗ News failed: " . $e->getMessage());
        }

        // Sync Publications
        $this->info('2. Syncing Publications...');
        try {
            $result = $this->publicationService->syncPublication();
            $this->info("   ✓ Publications: Created {$result['created']}, Updated {$result['updated']}");
        } catch (\Exception $e) {
            $this->error("   ✗ Publications failed: " . $e->getMessage());
        }

        // Sync Infographics
        $this->info('3. Syncing Infographics...');
        try {
            $result = $this->infographicService->syncInfographic();
            $this->info("   ✓ Infographics: Created {$result['created']}, Updated {$result['updated']}");
        } catch (\Exception $e) {
            $this->error("   ✗ Infographics failed: " . $e->getMessage());
        }

        $this->newLine();
        $this->info('All BPS data synchronization completed!');
        
        return Command::SUCCESS;
    }
}

