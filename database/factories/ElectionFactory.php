<?php

namespace Database\Factories;

use App\Enum\ElectionTypeEnum;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class ElectionFactory extends Factory
{
    public function definition(): array
    {
        $publishFrom = new Carbon(fake()->dateTimeThisMonth());
        $startFrom = $publishFrom->copy()->addDays(5);
        $endTo = $publishFrom->copy()->addDays(10);

        return [
            'name' => ucfirst(fake()->words(3, true)),
            'info' => fake()->realText(600),
            'publish_from' => $publishFrom,
            'start_from' => $startFrom,
            'end_to' => $endTo,
        ];
    }

    public function presidential(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => ElectionTypeEnum::PRESIDENTIAL_ELECTION,
        ]);
    }

    public function senate(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => ElectionTypeEnum::SENATE_ELECTION,
        ]);
    }

    public function chamberOfDeputies(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => ElectionTypeEnum::CHAMBER_OF_DEPUTIES_ELECTION,
        ]);
    }

    public function europeanParliament(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => ElectionTypeEnum::EUROPEAN_PARLIAMENT_ELECTION,
        ]);
    }

    public function regionalAssembly(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => ElectionTypeEnum::REGIONAL_ASSEMBLY_ELECTION,
        ]);
    }

    public function municipalAssembly(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => ElectionTypeEnum::MUNICIPAL_ASSEMBLY_ELECTION,
        ]);
    }
}
