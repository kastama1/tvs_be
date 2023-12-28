<?php

namespace App\Http\Controllers;

use App\Http\Requests\ElectionParty\StoreElectionPartyRequest;
use App\Http\Requests\ElectionParty\UpdateElectionPartyRequest;
use App\Http\Resources\ElectionPartyResource;
use App\Models\ElectionParty;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class ElectionPartyController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $elections = ElectionParty::all();

        return ElectionPartyResource::collection($elections);
    }


    public function show(ElectionParty $electionParty): ElectionPartyResource
    {
        return ElectionPartyResource::make($electionParty);
    }

    public function store(StoreElectionPartyRequest $request): Response
    {
        ElectionParty::create($request->validated());

        return response()->noContent();
    }

    public function update(UpdateElectionPartyRequest $request, ElectionParty $electionParty): Response
    {
        $electionParty->update($request->validated());

        return response()->noContent();
    }
}
