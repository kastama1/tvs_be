<?php

namespace App\Services;

use App\Enum\ElectionVotableTypeEnum;
use App\Models\Candidate;
use App\Models\Election;
use App\Models\ElectionParty;
use App\Models\User;
use App\Models\Vote;
use Illuminate\Support\Facades\Auth;

class VoteService
{
    protected CryptoService $cryptoService;

    public function __construct(CryptoService $hashService)
    {
        $this->cryptoService = $hashService;
    }

    public function vote(Election $election, array $voteData): void
    {
        /** @var User $user */
        $user = Auth::user();

        if ($user) {
            $vote = $this->createVote($voteData['vote'], $user, $election);

            if ($election->votable === ElectionVotableTypeEnum::ELECTION_PARTIES && array_key_exists('prefer_votes', $voteData)) {
                foreach ($voteData['prefer_votes'] as $preferVote) {
                    $this->createVote($preferVote, $user, $election, $vote);
                }
            }
        }
    }

    private function createVote(int $votableId, User $user, Election $election, Vote $parentVote = null): Vote
    {
        return Vote::create([
            'votable_type' => $parentVote ? Candidate::class : $election->votableType,
            'votable_id' => $votableId,
            'election_id' => $election->id,
            'vote_id' => $parentVote,
            'user_id' => $user->id,
        ]);
    }

    public function getUserVote($election): ?Vote
    {
        $vote = Auth::user()->vote()->ofElection($election)->with('votes')->latest()->first();

        if ($vote) {
            return $vote;
        }

        return null;
    }

    public function getVotes(Election $election): void
    {
        $election->load(['votes' => function ($query) use ($election) {
            $query->ofElection($election)->where('vote_id', '=', null)->orderByDesc('created_at');
        }, 'votes.votes']);

        $election->votes = $election->votes->unique('user_id');
    }

    public function getVotesCount(Election $election): void
    {
        $this->getVotes($election);

        if ($election->votable === ElectionVotableTypeEnum::ELECTION_PARTIES) {
            foreach ($election->electionParties as $electionParty) {
                $electionParty->votes_count = $election->votes->filter(function ($vote) use ($electionParty) {
                    return $vote->votable_id === $electionParty->id && $vote->votable_type === ElectionParty::class;
                })->count();
            }

            $candidateVotes = $election->votes->flatMap(function ($vote) {
                return $vote->votes;
            });

            foreach ($election->candidates as $candidate) {
                $candidate->votes_count = $candidateVotes->filter(function ($vote) use ($candidate) {
                    return $vote->votable_id === $candidate->id && $vote->votable_type === Candidate::class;
                })->count();
            }
        } elseif ($election->votable === ElectionVotableTypeEnum::CANDIDATES) {
            foreach ($election->candidates as $candidate) {

                $candidate->votes_count = $election->votes->filter(function ($vote) use ($candidate) {
                    return $vote->votable_id === $candidate->id && $vote->votable_type === Candidate::class;
                })->count();
            }
        }
    }
}
