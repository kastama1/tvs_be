<?php

namespace App\Http\Controllers;

use App\Http\Requests\Election\AssignElectionPartiesElectionRequest;
use App\Http\Requests\Election\StoreElectionRequest;
use App\Http\Requests\Election\UpdateElectionRequest;
use App\Http\Resources\ElectionResource;
use App\Http\Resources\ElectionsByTypeResource;
use App\Models\Election;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class ElectionController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $elections = Election::all();

        return ElectionResource::collection($elections);
    }

    public function listByType(): AnonymousResourceCollection
    {
        $elections = Election::all()->sortBy('start_from')->sortBy('type')->groupBy('type');

        $electionsByType = $elections->map(function ($elections, $key) {
            return collect(['type' => $key, 'elections' => $elections]);
        });

        return ElectionsByTypeResource::collection($electionsByType->values());
    }

    public function show(Election $election): ElectionResource
    {
        $election->load('electionParties');

        return ElectionResource::make($election);
    }

    public function store(StoreElectionRequest $request): Response
    {
        $this->authorize('store');

        Election::create($request->validated());

        return response()->noContent();
    }

    public function update(UpdateElectionRequest $request, Election $election): Response
    {
        $this->authorize('update');

        $election->update($request->validated());

        return response()->noContent();
    }

    public function assignElectionParties(AssignElectionPartiesElectionRequest $request, Election $election): Response
    {
        $this->authorize('assignElectionParties');

        $election->electionParties()->sync($request->validated()['election_parties']);

        return response()->noContent();
    }
}
