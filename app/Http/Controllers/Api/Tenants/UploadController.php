<?php

namespace App\Http\Controllers\Api\Tenants;

use App\Http\Controllers\Controller;
use App\Models\Tenants\UploadedFile;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UploadController extends Controller
{
    public function __invoke(Request $request)
    {
        if ($request->file('file')->isValid()) {
            $name = Str::random(40) . '.' . $request->file('file')->extension();
            Storage::disk('tmp')->put($name, file_get_contents($request->file('file')->getRealPath()));
            UploadedFile::create([
                'name' => $name,
                'original_name' => $originalName = $request->file('file')->getClientOriginalName(),
                'url' => $url = optional(Storage::disk('tmp'))->url($name),
                'mime_type' => $request->file('file')->getMimeType(),
                'extension' => $request->file('file')->extension(),
                'size' => $request->file('file')->getSize(),
                'path' => storage_path('app/tmp/' . $name),
                'disk' => 'tmp',
            ]);
        } else {
            throw new Exception('File is not valid');
        }

        return $this->success([
            'name' => $name,
            'url' => $url,
            'original_name' => $originalName,
        ]);
    }
}
