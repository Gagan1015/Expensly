<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Admin User
        $admin = User::firstOrCreate(
            ['email' => 'admin@expensly.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
            ]
        );

        $this->command->info('Admin user created: admin@expensly.com / admin123');

        // Create Demo User
        $demoUser = User::firstOrCreate(
            ['email' => 'demo@expensly.com'],
            [
                'name' => 'Demo User',
                'password' => Hash::make('demo123'),
                'role' => 'user',
            ]
        );

        $this->command->info('Demo user created: demo@expensly.com / demo123');

        // Seed Categories
        $this->call(CategorySeeder::class);
        
        // Seed Sample Expenses for demo user
        $this->call(ExpenseSeeder::class);
        
        // Seed Budgets
        $this->call(BudgetSeeder::class);

        $this->command->info('Database seeding completed successfully!');
        $this->command->info('');
        $this->command->info('===========================================');
        $this->command->info('Login Credentials:');
        $this->command->info('===========================================');
        $this->command->info('Admin: admin@expensly.com / admin123');
        $this->command->info('Demo User: demo@expensly.com / demo123');
        $this->command->info('===========================================');
    }
}
