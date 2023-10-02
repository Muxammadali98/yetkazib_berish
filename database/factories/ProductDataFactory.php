<?php

namespace Database\Factories;

use App\Models\Ticket;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductData>
 */
class ProductDataFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'ticket_id' => Ticket::factory(),
            'product_name' => fake()->name(),
            'article' => fake()->name(),
            'model' => fake()->name(),
            'quantity' => fake()->numberBetween(1, 100),
        ];
    }
}
