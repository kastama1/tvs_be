<?php

namespace App\Services;

use App\Models\Election;
use App\Models\ElectionParty;
use Illuminate\Support\Facades\Auth;

class VoteService
{
    public function vote(Election $election, string $electionPartyId){
        $user = Auth::user();

        $user->votes()->ofElection($election)->delete();

        Auth::user()->votes()->create([
            'votable_type' => ElectionParty::class,
            'votable_id' => $electionPartyId,
            'election_id' => $election->id,
        ]);
    }
}
