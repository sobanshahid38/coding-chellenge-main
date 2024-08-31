<?php

namespace Database\Factories;

use App\Models\Connection;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Connection>
 */
class ConnectionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Connection::class;

    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'connected_user_id' => User::factory(),
            'status' => $this->faker->randomElement(['pending', 'accepted']),
        ];
    }
}
