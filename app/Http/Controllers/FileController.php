<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Services\FileService;

class FileController extends Controller
{
    public function destroy(File $file, FileService $fileService)
    {
        $fileService->deleteFile($file);

        $file->delete();

        return response()->noContent();
    }
}
