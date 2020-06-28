<?php

namespace App\Http\Controllers\Install;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class Install extends Controller
{
    public function show()
    {
        return view('app.install.index');
    }

}
