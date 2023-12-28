<?php

namespace Database\Seeders;

use App\Models\ElectionParty;
use Illuminate\Database\Seeder;

class ElectionPartySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ElectionParty::factory(10)->create();
    }
}
