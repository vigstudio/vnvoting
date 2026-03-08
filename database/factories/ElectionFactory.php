<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Election>
 */
class ElectionFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(3) . ' ' . fake()->year(),
            'description' => fake()->paragraph(),
            'starts_at' => fake()->dateTimeBetween('-1 month', 'now'),
            'ends_at' => fake()->dateTimeBetween('now', '+1 month'),
            'is_active' => true,
        ];
    }

    /**
     * Election đã kết thúc
     */
    public function finished(): static
    {
        return $this->state(fn (array $attributes) => [
            'starts_at' => fake()->dateTimeBetween('-2 months', '-1 month'),
            'ends_at' => fake()->dateTimeBetween('-1 month', 'now'),
            'is_active' => false,
        ]);
    }
}
