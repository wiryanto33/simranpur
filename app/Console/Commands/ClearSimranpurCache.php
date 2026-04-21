<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class ClearSimranpurCache extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'simranpur:clear-cache';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Bersihkan semua cache data master dan statistik SIMRANPUR';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Cleaning SIMRANPUR specific cache keys...');
        
        $keys = [
            'dashboard_common_stats',
            'dashboard_admin_readiness',
            'dashboard_admin_month_stats',
            'dashboard_kepmek_stats',
            'dashboard_logistik_stats',
            'master_kompi',
            'master_kendaraan',
            'master_suku_cadang',
            'master_mekaniks',
            'roles_all',
            'stats_summary',
            'laporan_form_kendaraans_all',
        ];

        foreach ($keys as $key) {
            Cache::forget($key);
            $this->line("Cleared: $key");
        }

        // Search for dynamic keys like laporan_form_kendaraans_* if possible, 
        // but since Laravel doesn't support wildcard forget cleanly without tags,
        // we suggest using cache tags if the driver supports it. 
        // For now, we'll clear the most critical ones.
        
        $this->info('SIMRANPUR cache cleared successfully!');
    }
}
