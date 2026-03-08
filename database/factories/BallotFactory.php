<?php

namespace Database\Factories;

use App\Models\Election;
use App\Models\Position;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ballot>
 */
class BallotFactory extends Factory
{
    public function definition(): array
    {
        return [
            'election_id' => Election::factory(),
            'position_id' => Position::factory(),
            'expected_count' => fake()->numberBetween(10, 100),
            'entered_count' => fake()->numberBetween(0, 100),
            'counted_at' => fake()->optional(0.7)->dateTimeBetween('-1 week', 'now'),
        ];
    }

    /**
     * Ballot đã hoàn thành
     */
    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'counted_at' => fake()->dateTimeBetween('-1 week', 'now'),
        ]);
    }

    /**
     * Ballot chưa hoàn thành
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'counted_at' => null,
        ]);
    }
}
