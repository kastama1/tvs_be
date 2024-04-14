<?php

namespace App\Services;

use App\Enum\FileTypeEnum;
use App\Models\Candidate;
use App\Models\ElectionParty;
use App\Models\File as FileModel;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;

class FileService
{
    public function uploadImages(
        array $files,
        string $path,
        Candidate|ElectionParty $model,
    ): void {
        foreach ($files as $file) {
            $this->uploadImage($file, $path, $file->getClientOriginalName(), $model);
        }
    }

    public function uploadImage(
        UploadedFile $file,
        string $path,
        string $fileName,
        Candidate|ElectionParty $model,
    ): void {
        $path = $file->store($path, 'public');

        $fileModel = FileModel::create([
            'name' => $fileName,
            'path' => 'storage/'.$path,
            'type' => FileTypeEnum::IMAGE,
        ]);

        $model->images()->save($fileModel);
    }

    public function deleteFiles(array $files)
    {
        foreach ($files as $file) {
            $this->deleteFile($file);
        }
    }

    public function deleteFile(FileModel $file)
    {
        if (File::exists($file->path)) {
            File::delete($file->path);
        }
    }
}
