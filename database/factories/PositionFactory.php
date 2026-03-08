<?php

namespace Database\Factories;

use App\Models\Election;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Position>
 */
class PositionFactory extends Factory
{
    public function definition(): array
    {
        return [
            'election_id' => Election::factory(),
            'title' => fake()->randomElement(['Bí thư', 'Chủ tịch', 'Phó Chủ tịch', 'Ủy viên']) . ' ' . fake()->city(),
            'ballot_color' => fake()->randomElement(['#FF0000', '#00FF00', '#0000FF', '#FFFF00', '#FF00FF', '#00FFFF']),
            'max_votes' => fake()->numberBetween(1, 3),
            'sort_order' => fake()->numberBetween(0, 10),
        ];
    }
}
