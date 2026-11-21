<?php

namespace App\Http\Controllers\Api\Tenants;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenants\UploadRequest;
use App\Models\Tenants\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UploadController extends Controller
{
    public function __invoke(UploadRequest $request)
    {
        $file = $request->file('file');
        $extension = $file->extension();
        $name = Str::random(40).'.'.$extension;
        $tmpDisk = config('filesystems.tmp_disk');

        // storeAs keeps the original name out of the path and writes the file
        // once, instead of reading it into memory and putting it.
        Storage::disk($tmpDisk)->putFileAs('', $file, $name);

        $uploadedFile = UploadedFile::create([
            'name' => $name,
            'original_name' => $originalName = $file->getClientOriginalName(),
            'url' => Storage::disk($tmpDisk)->url($name),
            'mime_type' => $file->getMimeType(),
            'extension' => $extension,
            'size' => $file->getSize(),
            'relative_path' => $name,
            'path' => '',
            'disk' => $tmpDisk,
        ]);

        return $this->success([
            'id' => $uploadedFile->id,
            'name' => $name,
            'relative_path' => $name,
            'url' => $uploadedFile->url,
            'original_name' => $originalName,
        ]);
    }
}
