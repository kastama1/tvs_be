<?php

namespace Database\Factories;

use App\Models\Candidate;
use App\Models\ElectionParty;
use App\Models\File;
use Illuminate\Database\Eloquent\Factories\Factory;

class ElectionPartyFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => ucfirst(fake()->words(3, true)),
            'campaign' => fake()->realText(600),
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (ElectionParty $electionParty) {
            $electionParty->candidates()->saveMany(Candidate::factory(10)->create());
            $electionParty->images()->saveMany(File::factory(1)->image()->make());
        });
    }
}
