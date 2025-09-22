<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create global categories that all users can use
        $globalCategories = [
            ['name' => 'Food & Dining', 'color' => '#FF6B6B', 'description' => 'Restaurants, groceries, and food-related expenses'],
            ['name' => 'Transportation', 'color' => '#4ECDC4', 'description' => 'Gas, public transport, taxi, and travel expenses'],
            ['name' => 'Shopping', 'color' => '#F9CA24', 'description' => 'Clothing, electronics, and general shopping'],
            ['name' => 'Entertainment', 'color' => '#45B7D1', 'description' => 'Movies, games, subscriptions, and entertainment'],
            ['name' => 'Health & Medical', 'color' => '#6C5CE7', 'description' => 'Medical bills, pharmacy, and health expenses'],
            ['name' => 'Utilities', 'color' => '#A0E7E5', 'description' => 'Electricity, water, internet, and utility bills'],
            ['name' => 'Home', 'color' => '#FD79A8', 'description' => 'Rent, mortgage, home improvement, and household items'],
            ['name' => 'Education', 'color' => '#FDCB6E', 'description' => 'Books, courses, tuition, and educational expenses'],
            ['name' => 'Travel', 'color' => '#74B9FF', 'description' => 'Vacation, flights, hotels, and travel expenses'],
            ['name' => 'Gifts', 'color' => '#A29BFE', 'description' => 'Birthday gifts, donations, and special occasions'],
            ['name' => 'Other', 'color' => '#95A5A6', 'description' => 'Miscellaneous and other expenses'],
        ];

        foreach ($globalCategories as $category) {
            Category::create($category); // user_id will be null for global categories
        }
    }
}
