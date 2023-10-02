<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SupplierAssignment>
 */
class SupplierAssignmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'user_id' => 2,
            'title' => $this->faker->text(50),
            'description' => $this->faker->text,
            'status' => $this->faker->numberBetween(0, 3),
        ];
    }
}
