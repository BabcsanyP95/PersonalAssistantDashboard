<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\User;
use App\Models\Category;
use App\Models\Transaction;
use App\Models\Budget;
use App\Models\SavingsGoal;
use App\Models\FinanceNotification;
use App\Models\RecurringTransaction;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | 1. USER
        |--------------------------------------------------------------------------
        */

        $user = User::factory()->create([
            'name' => 'Demo User',
            'email' => 'demo@test.com',
            'password' => bcrypt('password'),
        ]);

        /*
        |--------------------------------------------------------------------------
        | 2. FIXED CATEGORIES (NO FACTORY → NO DUPLICATES)
        |--------------------------------------------------------------------------
        */

        $categoriesData = [
            ['name' => 'Salary', 'type' => 'income'],
            ['name' => 'Freelance', 'type' => 'income'],
            ['name' => 'Investments', 'type' => 'income'],

            ['name' => 'Food', 'type' => 'expense'],
            ['name' => 'Rent', 'type' => 'expense'],
            ['name' => 'Transport', 'type' => 'expense'],
            ['name' => 'Entertainment', 'type' => 'expense'],
            ['name' => 'Shopping', 'type' => 'expense'],
        ];

        $categories = collect();

        foreach ($categoriesData as $data) {
            $categories->push(
                Category::firstOrCreate(
                    [
                        'user_id' => $user->id,
                        'name' => $data['name'],
                        'type' => $data['type'],
                    ],
                    [
                        'color' => fake()->hexColor(),
                    ]
                )
            );
        }

        $incomeCategories = $categories->where('type', 'income');
        $expenseCategories = $categories->where('type', 'expense');

        /*
        |--------------------------------------------------------------------------
        | 3. TRANSACTIONS (REALISTIC HISTORY)
        |--------------------------------------------------------------------------
        */

        for ($i = 0; $i < 80; $i++) {
            $type = fake()->randomElement(['income', 'expense']);

            Transaction::create([
                'user_id' => $user->id,
                'category_id' => $type === 'income'
                    ? $incomeCategories->random()->id
                    : $expenseCategories->random()->id,

                'type' => $type,
                'amount' => fake()->randomFloat(2, 5, 1000),
                'description' => fake()->sentence(3),
                'transaction_date' => fake()->dateTimeBetween('-6 months', 'now'),
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | 4. BUDGETS (THIS MONTH)
        |--------------------------------------------------------------------------
        */

        foreach ($expenseCategories as $category) {
            Budget::create([
                'user_id' => $user->id,
                'category_id' => $category->id,
                'month' => now()->month,
                'year' => now()->year,
                'amount' => fake()->randomFloat(2, 200, 2000),
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | 5. SAVINGS GOALS
        |--------------------------------------------------------------------------
        */

        $goals = [
            ['name' => 'New Laptop', 'target' => 1500],
            ['name' => 'Vacation', 'target' => 3000],
            ['name' => 'Emergency Fund', 'target' => 5000],
        ];

        foreach ($goals as $goal) {
            SavingsGoal::create([
                'user_id' => $user->id,
                'name' => $goal['name'],
                'target_amount' => $goal['target'],
                'current_amount' => fake()->randomFloat(2, 0, $goal['target']),
                'target_date' => now()->addMonths(rand(3, 18)),
                'is_completed' => false,
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | 6. RECURRING TRANSACTIONS
        |--------------------------------------------------------------------------
        */

        for ($i = 0; $i < 5; $i++) {
            $category = $expenseCategories->random();

            RecurringTransaction::create([
                'user_id' => $user->id,
                'category_id' => $category->id,
                'type' => 'expense',
                'amount' => fake()->randomFloat(2, 10, 500),
                'description' => fake()->sentence(3),
                'frequency' => fake()->randomElement(['monthly', 'weekly']),
                'next_run_date' => now()->addDays(rand(1, 30)),
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | 7. NOTIFICATIONS
        |--------------------------------------------------------------------------
        */

        FinanceNotification::create([
            'user_id' => $user->id,
            'title' => 'Budget Alert',
            'message' => 'You have used 80% of your Food budget this month.',
            'type' => 'warning',
            'is_read' => false,
        ]);

        FinanceNotification::create([
            'user_id' => $user->id,
            'title' => 'Goal Progress',
            'message' => 'Your Emergency Fund is halfway complete.',
            'type' => 'info',
            'is_read' => false,
        ]);
    }
}