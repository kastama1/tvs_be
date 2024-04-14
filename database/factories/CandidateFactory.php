<?php

namespace Database\Factories;

use App\Models\Candidate;
use App\Models\File;
use Illuminate\Database\Eloquent\Factories\Factory;

class CandidateFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->name,
            'campaign' => fake()->realText(3000),
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Candidate $candidate) {
            $candidate->images()->saveMany(File::factory(1)->image()->make());
        });
    }
}
