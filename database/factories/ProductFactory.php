<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     *
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        //добавляем 5 товаров
        $productFakeName = $this->faker->randomElement(['Товар 1', 'Товар 2', 'Товар 3', 'Товар 4', 'Товар 5']);

        return [
            'name' => $productFakeName,
            'price' => fake()->numberBetween(100, 200),
            'stock' => fake()->numberBetween(1, 10)
        ];
    }
}
