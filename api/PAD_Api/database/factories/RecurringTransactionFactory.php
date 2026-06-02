<?php

namespace Database\Factories;

use App\Models\RecurringTransaction;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class RecurringTransactionFactory extends Factory
{
    protected $model = RecurringTransaction::class;

    public function definition(): array
    {
        return [
            'category_id' => Category::factory(),
            'type' => fake()->randomElement(['income', 'expense']),
            'amount' => fake()->randomFloat(2, 10, 1000),
            'description' => fake()->sentence(3),
            'frequency' => fake()->randomElement(['daily', 'weekly', 'monthly', 'yearly']),
            'next_run_date' => fake()->dateTimeBetween('now', '+1 month'),
        ];
    }
}