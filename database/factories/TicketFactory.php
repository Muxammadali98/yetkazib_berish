<?php

namespace Database\Factories;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ticket>
 */
class TicketFactory extends Factory
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
            'address' => fake()->address(),
            'phone' => fake()->phoneNumber(),
            'client_name' => fake()->name(),
            'comment' => fake()->text(),
            'status' => Ticket::STATUS_OPEN,
            'type' => Ticket::TYPE_RETURN,
            'additional_phone' => fake()->phoneNumber(),
            'contract_id' => fake()->buildingNumber(),
        ];
    }
}
