<?php

namespace App\Http\Controllers\Api\Tenants;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UploadController extends Controller
{
    public function __invoke(Request $request)
    {
        if ($request->file('file')->isValid()) {
            // generate name for file Str facade
            $name = Str::random(40) . '.' . $request->file('file')->extension();
            Storage::disk('tmp')->put($name, file_get_contents($request->file('file')->getRealPath()));
        }

        return $this->success([
            'name' => $name,
            'url' => Storage::disk('tmp')->url($name),
            'original_name' => $request->file('file')->getClientOriginalName(),
        ]);
    }
}
