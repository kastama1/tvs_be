<?php

namespace Database\Seeders;

use App\Models\Election;
use Illuminate\Database\Seeder;

class ElectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Election::factory(10)->presidential()->create();
        Election::factory(10)->senate()->create();
        Election::factory(10)->chamberOfDeputies()->create();
        Election::factory(10)->europeanParliament()->create();
        Election::factory(10)->regionalAssembly()->create();
        Election::factory(10)->municipalAssembly()->create();
    }
}
