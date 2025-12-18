<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\HealthyImporter;

class SyncHealthyFoods extends Command
{
    protected $signature = 'healthy:sync 
                            {query=snack : Kata kunci OFF}
                            {--pages=2 : Jumlah halaman}
                            {--size=24 : Page size}
                            {--region= : null|id}';

    protected $description = 'Sync healthy foods from OpenFoodFacts into local database';

    public function handle(HealthyImporter $importer): int
    {
        $query  = $this->argument('query');
        $pages  = (int) $this->option('pages');
        $size   = (int) $this->option('size');
        $region = $this->option('region') ?: null;

        $count = $importer->sync($query, $pages, $size, $region);

        $this->info("Imported/updated healthy foods: {$count}");
        return self::SUCCESS;
    }
}