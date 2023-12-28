<?php

namespace App\Http\Controllers;

use App\Http\Requests\ElectionParty\StoreElectionPartyRequest;
use App\Http\Requests\ElectionParty\UpdateElectionPartyRequest;
use App\Http\Resources\ElectionPartyResource;
use App\Models\ElectionParty;

class ElectionPartyController extends Controller
{
    public function index()
    {
        $elections = ElectionParty::all();

        return ElectionPartyResource::collection($elections);
    }


    public function show(ElectionParty $electionParty)
    {
        return ElectionPartyResource::make($electionParty);
    }

    public function store(StoreElectionPartyRequest $request)
    {
        ElectionParty::create($request->validated());

        return response()->noContent();
    }

    public function update(UpdateElectionPartyRequest $request, ElectionParty $electionParty)
    {
        $electionParty->update($request->validated());

        return response()->noContent();
    }
}
