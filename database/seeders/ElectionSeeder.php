<?php

namespace Database\Seeders;

use App\Enum\ElectionVotableTypeEnum;
use App\Models\Candidate;
use App\Models\Election;
use App\Models\ElectionParty;
use Illuminate\Database\Seeder;

class ElectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Election::factory(2)->presidential()->create();
        Election::factory(2)->senate()->create();
        Election::factory(2)->chamberOfDeputies()->create();
        Election::factory(2)->europeanParliament()->create();
        Election::factory(2)->regionalAssembly()->create();
        Election::factory(2)->municipalAssembly()->create();

        $elections = Election::all();
        $candidates = Candidate::all();
        $parties = ElectionParty::all();

        /** @var Election $election */
        foreach ($elections as $election) {
            if ($election->votableType === ElectionVotableTypeEnum::CANDIDATES) {
                $electionCandidates = $candidates->random(5);
                $election->candidates()->attach($electionCandidates->pluck('id'));
            } else {
                $electionParties = $parties->random(5);
                $election->electionParties()->attach($electionParties->pluck('id'));

                /** @var ElectionParty $electionParty */
                foreach ($electionParties as $electionParty) {
                    $electionCandidates = $candidates->where('election_party_id', '=', $electionParty->id)->random(5);
                    $election->candidates()->attach($electionCandidates->pluck('id'));
                }
            }
        }
    }
}
