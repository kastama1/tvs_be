<?php

namespace Database\Factories;

use App\Enum\FileTypeEnum;
use Illuminate\Database\Eloquent\Factories\Factory;

class FileFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->text(20)
        ];
    }

    public function image(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => FileTypeEnum::IMAGE,
            'path' => fake()->imageUrl(400, 400)
        ]);
    }
}
