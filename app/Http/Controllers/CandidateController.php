<?php

namespace App\Http\Controllers;

use App\Http\Requests\Candidate\StoreCandidateRequest;
use App\Http\Requests\Candidate\UpdateCandidateRequest;
use App\Http\Resources\CandidateResource;
use App\Models\Candidate;
use App\Services\FileService;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class CandidateController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $candidates = Candidate::with('electionParty', 'images')->get();

        return CandidateResource::collection($candidates);
    }

    public function show(Candidate $candidate): CandidateResource
    {
        $candidate->load('electionParty', 'images');

        return CandidateResource::make($candidate);
    }

    public function store(StoreCandidateRequest $request, FileService $fileService): Response
    {
        $candidateData = $request->validated();

        $candidate = Candidate::create($candidateData);

        if (array_key_exists('images', $candidateData)) {
            $fileService->uploadImages(
                $candidateData['images'],
                'candidates/'.$candidate->id,
                $candidate,
            );
        }

        return response()->noContent();
    }

    public function update(UpdateCandidateRequest $request, Candidate $candidate, FileService $fileService): Response
    {
        $candidateData = $request->validated();

        $candidate->update($candidateData);

        if (array_key_exists('images', $candidateData)) {
            $fileService->uploadImages(
                $candidateData['images'],
                'candidates/'.$candidate->id,
                $candidate,
            );
        }

        return response()->noContent();
    }
}
