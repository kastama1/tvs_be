<?php

namespace App\Services;

use App\Models\Candidate;
use App\Models\Election;
use App\Models\ElectionParty;
use Illuminate\Support\Facades\Auth;

class VoteService
{
    public function vote(Election $election, array $voteData): void
    {
        $user = Auth::user();

        $user->votes()->ofElection($election)->delete();

        $user->votes()->create([
            'votable_type' => $election->votableType,
            'votable_id' => $voteData['vote'],
            'election_id' => $election->id,
        ]);

        if ($election->votableType === ElectionParty::class && array_key_exists('prefer_votes', $voteData)) {
            foreach ($voteData['prefer_votes'] as $preferVote) {
                $user->votes()->create([
                    'votable_type' => Candidate::class,
                    'votable_id' => $preferVote,
                    'election_id' => $election->id,
                ]);
            }
        }
    }
}
