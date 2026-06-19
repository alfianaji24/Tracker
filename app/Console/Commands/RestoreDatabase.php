<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class RestoreDatabase extends Command
{
    protected $signature = 'db:restore {--force : Force restore without confirmation}';
    protected $description = 'Restore database from backup file';

    public function handle()
    {
        $backupPath = database_path('database.backup.sqlite');
        $dbPath = database_path('database.sqlite');

        if (!File::exists($backupPath)) {
            $this->error('❌ Backup file not found: ' . $backupPath);
            return 1;
        }

        if (!$this->option('force')) {
            if (!$this->confirm('⚠️  This will overwrite current database. Continue?')) {
                $this->info('Cancelled.');
                return 0;
            }
        }

        try {
            File::copy($backupPath, $dbPath);
            $this->info('✅ Database restored successfully from backup!');
            return 0;
        } catch (\Exception $e) {
            $this->error('❌ Error restoring database: ' . $e->getMessage());
            return 1;
        }
    }
}
