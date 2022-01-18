<?php

namespace App\Services;

use Exception;
use Illuminate\Http\Request;

class Purchasing
{
    public function create(Request $request)
    {
        dd($request->all());
        throw new Exception("Cobak");
    }
}
