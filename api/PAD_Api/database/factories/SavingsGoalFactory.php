<?php

namespace Database\Factories;

use App\Models\SavingsGoal;
use Illuminate\Database\Eloquent\Factories\Factory;

class SavingsGoalFactory extends Factory
{
    protected $model = SavingsGoal::class;

    public function definition(): array
    {
        $target = fake()->randomFloat(2, 500, 10000);

        return [
            'name' => fake()->randomElement([
                'New Laptop',
                'Vacation',
                'Car',
                'Emergency Fund',
                'House Deposit'
            ]),
            'target_amount' => $target,
            'current_amount' => fake()->randomFloat(2, 0, $target),
            'target_date' => fake()->dateTimeBetween('now', '+2 years'),
            'is_completed' => fake()->boolean(20),
        ];
    }
}