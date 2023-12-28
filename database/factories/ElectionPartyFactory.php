<?php

namespace Database\Factories;

use App\Enum\ElectionTypeEnum;
use Carbon\Carbon;
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
}
