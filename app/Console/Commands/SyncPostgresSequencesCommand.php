<?php

namespace App\Console\Commands;

use App\Database\SyncPostgresSequences;
use Illuminate\Console\Command;

class SyncPostgresSequencesCommand extends Command
{
    protected $signature = 'db:sync-sequences';

    protected $description = 'Sync PostgreSQL ID sequences after seeding with explicit IDs';

    public function handle(): int
    {
        if (\Illuminate\Support\Facades\DB::getDriverName() !== 'pgsql') {
            $this->warn('This command only applies to PostgreSQL.');

            return self::SUCCESS;
        }

        SyncPostgresSequences::run();
        $this->info('PostgreSQL sequences synced.');

        return self::SUCCESS;
    }
}
