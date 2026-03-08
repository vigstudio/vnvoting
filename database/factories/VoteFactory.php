<?php

namespace Database\Factories;

use App\Models\Ballot;
use App\Models\Candidate;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Vote>
 */
class VoteFactory extends Factory
{
    public function definition(): array
    {
        return [
            'ballot_id' => Ballot::factory(),
            'candidate_id' => Candidate::factory(),
        ];
    }
}
