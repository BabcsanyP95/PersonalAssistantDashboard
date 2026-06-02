<?php

namespace Database\Factories;

use App\Models\Transaction;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class TransactionFactory extends Factory
{
    protected $model = Transaction::class;

    public function definition(): array
    {
        $type = fake()->randomElement(['income', 'expense']);

        $expenseDescriptions = [
            'Groceries', 'Restaurant', 'Uber', 'Netflix', 'Gas', 'Clothes'
        ];

        $incomeDescriptions = [
            'Salary', 'Freelance payment', 'Bonus', 'Investment return'
        ];

        return [
            'category_id' => Category::factory(),
            'type' => $type,
            'amount' => fake()->randomFloat(2, 5, 500),
            'description' => $type === 'expense'
                ? fake()->randomElement($expenseDescriptions)
                : fake()->randomElement($incomeDescriptions),
            'transaction_date' => fake()->dateTimeBetween('-6 months', 'now'),
        ];
    }
}