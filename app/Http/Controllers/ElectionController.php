<?php

namespace App\Http\Controllers;

use App\Http\Requests\Election\AssignOptionsElectionRequest;
use App\Http\Requests\Election\StoreElectionRequest;
use App\Http\Requests\Election\UpdateElectionRequest;
use App\Http\Requests\Election\VoteElectionRequest;
use App\Http\Resources\ElectionResource;
use App\Http\Resources\VoteResource;
use App\Models\Election;
use App\Models\Vote;
use App\Services\AssignOptionsService;
use App\Services\CryptoService;
use App\Services\VoteService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Gate;
use Spatie\SimpleExcel\SimpleExcelWriter;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use ZipArchive;

class ElectionController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $electionQuery = Election::query();

        if (Gate::check('listAll', Election::class)) {
            //
        } elseif (Gate::check('listPublish', Election::class)) {
            $electionQuery = $electionQuery->published()->orWhereHas('votes', function ($query) {
                $query->where('user_id', '=', Auth::user()->id);
            });
        }

        $electionQuery = $electionQuery->with('candidates', 'candidates.electionParty', 'candidates.images', 'electionParties', 'electionParties.images');

        return ElectionResource::collection($electionQuery->get());
    }

    public function show(VoteService $voteService, Election $election): ElectionResource
    {
        $this->authorize('view', $election);

        $election->load('candidates', 'candidates.electionParty', 'candidates.images', 'electionParties', 'electionParties.images');

        if (Gate::check('listAll', Election::class)) {
            $voteService->getVotesCount($election);
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

    public function showVote(VoteService $voteService, Election $election): VoteResource
    {
        $vote = $voteService->getUserVote($election);

        if ($vote) {
            $this->authorize('view', $vote);

            foreach ($vote->votes as $childrenVote) {
                $this->authorize('view', $childrenVote);
            }
        }

        return VoteResource::make($vote);
    }

    public function downloadVotes(VoteService $voteService, CryptoService $cryptoService, Election $election): BinaryFileResponse|JsonResponse
    {
        $this->authorize('downloadVotes', $election);

        $voteService->getVotes($election);
        $election->load('votes.votable', 'votes.votes');

        $data = [];
        /** @var Vote $vote */
        foreach ($election->votes as $vote) {
            $data[] = [$vote->uuid, $vote->votable->name, $vote->created_at->format('d.m.Y H:i')];
        }

        $filePath = public_path('storage/votes.csv');
        SimpleExcelWriter::create($filePath)
            ->addHeader([sprintf('Hlasy ve volbách %s', $election->name)])
            ->addRow([])
            ->addHeader(['Uuid', 'Jméno', 'Datum hlasování'])
            ->addRows($data);

        $signaturePath = $cryptoService->createSignature(File::get($filePath));

        $zip = new ZipArchive();
        if ($zip->open(public_path('storage/Votes.zip'), ZipArchive::CREATE) === true) {

            $zip->addFile($filePath, 'votes.csv');
            $zip->addFile($signaturePath, 'signature.csv');
            $zip->addFile(storage_path('app/keys/public.key'), 'public.key');
            $zip->close();

            File::delete($filePath);
            File::delete($signaturePath);

            return response()->download(public_path('storage/Votes.zip'))->deleteFileAfterSend();
        }

        return response()->json(['error' => 'Něco se pokazilo'], 500);
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
