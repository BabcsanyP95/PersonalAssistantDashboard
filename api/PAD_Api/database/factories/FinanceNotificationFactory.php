<?php

namespace Database\Factories;

use App\Models\FinanceNotification;
use Illuminate\Database\Eloquent\Factories\Factory;

class FinanceNotificationFactory extends Factory
{
    protected $model = FinanceNotification::class;

    public function definition(): array
    {
        return [
            'title' => fake()->randomElement([
                'Budget Alert',
                'Goal Progress',
                'Transaction Alert',
                'Savings Update'
            ]),
            'message' => fake()->sentence(10),
            'type' => fake()->randomElement(['info', 'warning', 'success']),
            'is_read' => fake()->boolean(40),
        ];
    }
}