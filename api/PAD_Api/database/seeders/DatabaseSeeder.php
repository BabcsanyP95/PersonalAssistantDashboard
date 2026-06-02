<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Budget;
use App\Models\Category;
use App\Models\FinanceNotification;
use App\Models\RecurringTransaction;
use App\Models\SavingsGoal;
use App\Models\Transaction;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
{
    $user = User::factory()->create([
        'email' => 'demo@test.com',
        'password' => bcrypt('password'),
    ]);

    $categories = Category::factory(5)->create([
        'user_id' => $user->id,
    ]);

    Transaction::factory(50)->create([
        'user_id' => $user->id,
        'category_id' => $categories->random()->id,
    ]);

    Budget::factory(5)->create([
        'user_id' => $user->id,
        'category_id' => $categories->random()->id,
    ]);

    SavingsGoal::factory(3)->create([
        'user_id' => $user->id,
    ]);

    FinanceNotification::factory(5)->create([
        'user_id' => $user->id,
    ]);
}
}
