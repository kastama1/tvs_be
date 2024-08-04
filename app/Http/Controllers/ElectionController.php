<?php

namespace App\Http\Controllers;

use App\Http\Requests\Election\AssignOptionsElectionRequest;
use App\Http\Requests\Election\StoreElectionRequest;
use App\Http\Requests\Election\UpdateElectionRequest;
use App\Http\Requests\Election\VoteElectionRequest;
use App\Http\Resources\ElectionResource;
use App\Http\Resources\VoteResource;
use App\Models\Election;
use App\Services\AssignOptionsService;
use App\Services\VoteService;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class ElectionController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $electionQuery = Election::query();

        if (Gate::check('listAll', Election::class)) {
            //
        } elseif (Gate::check('listPublish', Election::class)) {
            $electionQuery = $electionQuery->published();
        }

        $electionQuery = $electionQuery->with('candidates', 'candidates.electionParty', 'candidates.images', 'electionParties', 'electionParties.images');

        return ElectionResource::collection($electionQuery->get());
    }

    public function show(Election $election): ElectionResource
    {
        $this->authorize('view', $election);

        $election->load('candidates', 'candidates.electionParty', 'candidates.images', 'electionParties', 'electionParties.images');

        if (Gate::check('listAll', Election::class)) {
            $election->candidates->loadCount(['votes' => function ($query) use ($election) {
                $query->ofElection($election);
            }, ]);
            $election->electionParties->loadCount(['votes' => function ($query) use ($election) {
                $query->ofElection($election);
            }, ]);
        } elseif (Gate::check('listPublish', Election::class)) {
            //
        }

        return ElectionResource::make($election);
    }

    public function store(StoreElectionRequest $request): Response
    {
        Election::create($request->validated());

        return response()->noContent();
    }

    public function update(UpdateElectionRequest $request, Election $election): Response
    {
        $election->update($request->validated());

        return response()->noContent();
    }

    public function showVote(Election $election): AnonymousResourceCollection
    {
        $votes = Auth::user()->votes()->ofElection($election)->get();

        foreach ($votes as $vote) {
            $this->authorize('view', $vote);
        }

        return VoteResource::collection($votes);
    }

    public function vote(VoteElectionRequest $request, Election $election, VoteService $voteService): Response
    {
        $voteService->vote($election, $request->validated());

        return response()->noContent();
    }

    public function assignOptions(AssignOptionsElectionRequest $request, Election $election, AssignOptionsService $assignService): Response
    {
        $assignService->assign($election, $request->validated());

        return response()->noContent();
    }
}
