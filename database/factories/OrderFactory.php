<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $typeFakeElement = $this->faker->randomElement(['online', 'offline']);
        $statusFakeElement = $this->faker->randomElement(['active', 'completed', 'canceled']);

        return [
            'customer' => fake()->name(),
            'phone' => fake()->phoneNumber(),
            'created_at' => fake()->dateTimeThisDecade(),
            'completed_at' => fake()->dateTimeThisDecade(),
            'user_id' => User::factory(),
            'type' => $typeFakeElement,
            'status' => $statusFakeElement,
        ];
    }
}
