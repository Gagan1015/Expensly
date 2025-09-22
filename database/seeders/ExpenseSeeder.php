<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Expense;
use App\Models\Category;
use App\Models\User;
use Carbon\Carbon;

class ExpenseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get user with ID 2 (the current logged-in user) or first user
        $user = User::find(2) ?? User::first();
        
        if (!$user) {
            // Create a demo user if none exists
            $user = User::create([
                'name' => 'Demo User',
                'email' => 'demo@example.com',
                'password' => bcrypt('password'),
                'role' => 'user',
            ]);
        }

        // Get categories
        $categories = Category::all();
        
        if ($categories->isEmpty()) {
            $this->command->info('No categories found. Please run CategorySeeder first.');
            return;
        }

        // Sample expense data
        $sampleExpenses = [
            [
                'description' => 'Lunch at Restaurant',
                'amount' => 1250.00,
                'date' => Carbon::today(),
                'category_name' => 'Food & Dining',
            ],
            [
                'description' => 'Bus Ticket',
                'amount' => 350.00,
                'date' => Carbon::today(),
                'category_name' => 'Transportation',
            ],
            [
                'description' => 'Morning Coffee',
                'amount' => 450.00,
                'date' => Carbon::today(),
                'category_name' => 'Food & Dining',
            ],
            [
                'description' => 'Grocery Shopping',
                'amount' => 8520.00,
                'date' => Carbon::yesterday(),
                'category_name' => 'Shopping',
            ],
            [
                'description' => 'Movie Tickets',
                'amount' => 800.00,
                'date' => Carbon::yesterday(),
                'category_name' => 'Entertainment',
            ],
            [
                'description' => 'Doctor Visit',
                'amount' => 1200.00,
                'date' => Carbon::now()->subDays(2),
                'category_name' => 'Health & Medical',
            ],
            [
                'description' => 'Electricity Bill',
                'amount' => 2500.00,
                'date' => Carbon::now()->subDays(3),
                'category_name' => 'Utilities',
            ],
            [
                'description' => 'Online Shopping',
                'amount' => 3200.00,
                'date' => Carbon::now()->subDays(4),
                'category_name' => 'Shopping',
            ],
            [
                'description' => 'Uber Ride',
                'amount' => 680.00,
                'date' => Carbon::now()->subDays(5),
                'category_name' => 'Transportation',
            ],
            [
                'description' => 'Dinner with Friends',
                'amount' => 2100.00,
                'date' => Carbon::now()->subDays(6),
                'category_name' => 'Food & Dining',
            ],
        ];

        foreach ($sampleExpenses as $expenseData) {
            $category = $categories->where('name', $expenseData['category_name'])->first();
            
            if ($category) {
                Expense::create([
                    'user_id' => $user->id,
                    'category_id' => $category->id,
                    'description' => $expenseData['description'],
                    'amount' => $expenseData['amount'],
                    'date' => $expenseData['date'],
                    'payment_method' => 'cash',
                ]);
            }
        }

        $this->command->info('Sample expenses created successfully!');
    }
}
