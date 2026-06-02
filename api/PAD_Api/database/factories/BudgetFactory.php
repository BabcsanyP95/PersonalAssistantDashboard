<?php

namespace Database\Factories;

use App\Models\Budget;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class BudgetFactory extends Factory
{
    protected $model = Budget::class;

    public function definition(): array
    {
        $date = fake()->dateTimeBetween('-1 year', '+1 year');

        return [
            'category_id' => Category::factory(),
            'month' => (int) $date->format('m'),
            'year' => (int) $date->format('Y'),
            'amount' => fake()->randomFloat(2, 100, 2000),
        ];
    }
}