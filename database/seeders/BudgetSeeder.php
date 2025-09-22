<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\UserBudget;
use Carbon\Carbon;

class BudgetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        
        foreach ($users as $user) {
            // Create current month budget
            UserBudget::create([
                'user_id' => $user->id,
                'monthly_budget' => 50000, // ₹50,000 default budget
                'year' => Carbon::now()->year,
                'month' => Carbon::now()->month,
            ]);
        }
    }
}
