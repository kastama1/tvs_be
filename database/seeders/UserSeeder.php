<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::factory(1)->testAdmin()->create();
        User::factory(1)->testVoter()->create();
        User::factory(10)->admin()->create();
        User::factory(10)->voter()->create();
    }
}
