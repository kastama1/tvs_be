<?php

namespace Database\Factories;

use App\Enum\UserRoleEnum;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
        ];
    }

    public function testAdmin(): static
    {
        return $this->state(fn (array $attributes) => [
            'email' => 'testadmin@test.com',
            'role' => UserRoleEnum::ADMIN,
        ]);
    }

    public function testVoter(): static
    {
        return $this->state(fn (array $attributes) => [
            'email' => 'testvoter@test.com',
            'role' => UserRoleEnum::VOTER,
        ]);
    }

    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => UserRoleEnum::ADMIN,
        ]);
    }

    public function voter(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => UserRoleEnum::VOTER,
        ]);
    }
}
