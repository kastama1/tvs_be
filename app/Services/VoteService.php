<?php

namespace App\Services;

use App\Enum\ElectionVotableTypeEnum;
use App\Models\Candidate;
use App\Models\Election;
use App\Models\ElectionParty;
use App\Models\User;
use App\Models\Vote;
use Carbon\Carbon;
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
            $previousVote = Vote::latest()->first();

            $vote = $this->createVote($voteData['vote'], $user, $previousVote ? $previousVote->hash : '0', $election);

            if ($election->votable === ElectionVotableTypeEnum::ELECTION_PARTIES && array_key_exists('prefer_votes', $voteData)) {
                $previousVote = $vote;
                foreach ($voteData['prefer_votes'] as $preferVote) {
                    $previousVote = $this->createVote($preferVote, $user, $previousVote->hash, $election, $vote);
                }
            }
        }
    }

    private function createVote(int $votableId, User $user, string $previousHash, Election $election, Vote $parentVote = null): Vote
    {
        $vote = new Vote();
        $vote->previous_hash = $previousHash;
        $vote->hash = $this->cryptoService->getHash($previousHash, $votableId, Carbon::now()->toDateTimeString());
        $vote->votable_type = $parentVote ? Candidate::class : $election->votableType;
        $vote->votable_id = base64_encode($this->cryptoService->encrypt((string) $votableId));
        $vote->election_id = $election->id;
        $vote->vote_id = $parentVote?->id;
        $vote->user_id = $user->id;
        $vote->save();

        return $vote;
    }

    public function getUserVote($election): ?Vote
    {
        $vote = Auth::user()->vote()->ofElection($election)->with('votes')->latest()->first();

        if ($vote) {
            $vote->votable_id = $this->cryptoService->decrypt(base64_decode($vote->votable_id));

            foreach ($vote->votes as $childVote) {
                $childVote->votable_id = $this->cryptoService->decrypt(base64_decode($childVote->votable_id));
            }

            return $vote;
        }

        return null;
    }

    public function getVotesCount(Election $election): void
    {
        $election->load(['votes' => function ($query) use ($election) {
            $query->ofElection($election)->where('vote_id', '=', null)->orderByDesc('created_at');
        }, 'votes.votes']);

        $election->votes = $election->votes->unique('user_id');

        foreach ($election->votes as $vote) {
            $vote->votable_id = (int) $this->cryptoService->decrypt(base64_decode($vote->votable_id));

            foreach ($vote->votes as $childVote) {
                $childVote->votable_id = (int) $this->cryptoService->decrypt(base64_decode($childVote->votable_id));
            }
        }

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
