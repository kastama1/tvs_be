<?php

namespace App\Http\Controllers;

use App\Http\Requests\ElectionParty\StoreElectionPartyRequest;
use App\Http\Requests\ElectionParty\UpdateElectionPartyRequest;
use App\Http\Resources\ElectionPartyResource;
use App\Models\ElectionParty;
use App\Services\FileService;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class ElectionPartyController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $electionParties = ElectionParty::all();

        $electionParties->load('candidates');

        return ElectionPartyResource::collection($electionParties);
    }

    public function show(ElectionParty $electionParty): ElectionPartyResource
    {
        $electionParty->load('candidates', 'images');

        return ElectionPartyResource::make($electionParty);
    }

    public function store(StoreElectionPartyRequest $request, FileService $fileService): Response
    {
        $this->authorize('store');

        $electionPartyData = $request->validated();

        $electionParty = ElectionParty::create($electionPartyData);

        if (array_key_exists('images', $electionPartyData)) {
            $fileService->uploadImages(
                $electionPartyData['images'],
                'candidates/'.$electionParty->id,
                $electionParty,
            );
        }

        return response()->noContent();
    }

    public function update(UpdateElectionPartyRequest $request, ElectionParty $electionParty, FileService $fileService): Response
    {
        $this->authorize('update');

        $electionPartyData = $request->validated();

        $electionParty->update($electionPartyData);

        if (array_key_exists('images', $electionPartyData)) {
            $fileService->uploadImages(
                $electionPartyData['images'],
                'candidates/'.$electionParty->id,
                $electionParty,
            );
        }

        return response()->noContent();
    }
}
