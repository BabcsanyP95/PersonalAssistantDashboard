<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    protected $model = Category::class;

    public function definition(): array
    {
        $expenseCategories = [
            'Food',
            'Rent',
            'Transport',
            'Entertainment',
            'Health',
            'Utilities',
            'Shopping',
        ];

        $incomeCategories = [
            'Salary',
            'Freelance',
            'Investments',
            'Gifts',
        ];

        $type = fake()->randomElement(['income', 'expense']);

        return [
            'name' => $type === 'expense'
                ? fake()->randomElement($expenseCategories)
                : fake()->randomElement($incomeCategories),

            'type' => $type,

            'color' => fake()->hexColor(),
        ];
    }
}