<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class TruncateExpenseTables extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'expense:truncate {--force : Force the operation without confirmation}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Truncate all expense management related tables';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if (!$this->option('force')) {
            if (!$this->confirm('This will delete ALL expense and category data. Are you sure?')) {
                $this->info('Operation cancelled.');
                return 0;
            }
        }

        $this->info('Starting to truncate expense-related tables...');

        try {
            // Disable foreign key checks
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');

            // List of tables to truncate (in order to handle foreign keys)
            $tables = [
                'expenses',
                'categories'
            ];

            foreach ($tables as $table) {
                if (Schema::hasTable($table)) {
                    DB::table($table)->truncate();
                    $this->info("✓ Truncated table: {$table}");
                } else {
                    $this->warn("⚠ Table not found: {$table}");
                }
            }

            // Re-enable foreign key checks
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');

            $this->info('✅ All expense-related tables have been truncated successfully!');
            
            // Suggest next steps
            $this->newLine();
            $this->info('💡 Next steps:');
            $this->info('   - Run: php artisan migrate:refresh --path=/database/migrations/2025_09_21_095045_create_categories_table.php');
            $this->info('   - Run: php artisan migrate:refresh --path=/database/migrations/2025_09_21_095049_create_expenses_table.php');
            $this->info('   - Or run: php artisan db:seed to add sample data');

        } catch (\Exception $e) {
            $this->error('❌ Error occurred while truncating tables: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }
}
