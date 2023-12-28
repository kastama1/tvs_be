<?php

namespace App\Http\Controllers;

use App\Http\Requests\Election\StoreElectionRequest;
use App\Http\Requests\Election\UpdateElectionRequest;
use App\Http\Resources\ElectionResource;
use App\Http\Resources\ElectionsByTypeResource;
use App\Models\Election;

class ElectionController extends Controller
{
    public function index()
    {
        $elections = Election::all();

        return ElectionResource::collection($elections);
    }

    public function listByType()
    {
        $elections = Election::all()->sortBy('start_from')->sortBy('type')->groupBy('type');

        $electionsByType = $elections->map(function ($elections, $key) {
            return collect(['type' => $key, 'elections' => $elections]);
        });

        return ElectionsByTypeResource::collection($electionsByType->values());
    }

    public function show(Election $election)
    {
        return ElectionResource::make($election);
    }

    public function store(StoreElectionRequest $request)
    {
        Election::create($request->validated());

        return response()->noContent();
    }

    public function update(UpdateElectionRequest $request, Election $election)
    {
       $election->update($request->validated());

        return response()->noContent();
    }
}
