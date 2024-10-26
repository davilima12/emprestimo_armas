<?php

declare(strict_types=1);

namespace App\Features\Filesystem\Services;

use App\Features\Filesystem\SystemFolders;
use App\Features\Medias\Models\Media;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Uid\Ulid;

class FileSystemService
{
    public function saveUploadedFile(UploadedFile $file, SystemFolders $folder): string
    {
        $extension = $file->getClientOriginalExtension();
        $fileName = strtolower(Ulid::generate()).'.'.$extension;
        $path = $folder->value.'/'.$fileName;
        Storage::put($path, $file->getContent());

        return $path;
    }

    public function getMedia(Media $media): ?string
    {
        return Storage::get($media->system_path);
    }

    public function deleteMedia(Media $media): void
    {
        Storage::delete($media->system_path);
    }
}
