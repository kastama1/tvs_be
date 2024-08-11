<?php

namespace App\Http\Controllers;

use App\Models\Vote;
use App\Services\CryptoService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\File;
use Spatie\SimpleExcel\SimpleExcelWriter;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use ZipArchive;

class VoteController extends Controller
{
    public function download(CryptoService $cryptoService, Vote $vote): BinaryFileResponse|JsonResponse
    {
        $vote->load('votable', 'votes', 'election');

        $this->authorize('download', $vote);

        $data = [];
        $data[] = [$vote->uuid, $vote->votable->name, $vote->created_at->format('d.m.Y H:i')];
        /** @var Vote $childVote */
        foreach ($vote->votes as $childVote) {
            $data[] = [$childVote->uuid, $childVote->votable->name, $childVote->created_at->format('d.m.Y H:i')];
        }

        $filePath = public_path('storage/vote.csv');
        SimpleExcelWriter::create($filePath)
            ->addHeader([sprintf('Vaše hlasy ve volbách %s', $vote->election->name)])
            ->addRow([])
            ->addHeader(['Uuid', 'Jméno', 'Datum hlasování'])
            ->addRows($data);

        $signaturePath = $cryptoService->createSignature(File::get($filePath));

        $zip = new ZipArchive();
        if ($zip->open(public_path('storage/Votes.zip'), ZipArchive::CREATE) === true) {

            $zip->addFile($filePath, 'vote.csv');
            $zip->addFile($signaturePath, 'signature.csv');
            $zip->addFile(storage_path('app/keys/public.key'), 'public.key');
            $zip->close();

            File::delete($filePath);
            File::delete($signaturePath);

            return response()->download(public_path('storage/Votes.zip'))->deleteFileAfterSend();
        }

        return response()->json(['error' => 'Něco se pokazilo'], 500);
    }
}
