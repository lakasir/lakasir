<?php

namespace App\Http\Controllers\Lakasir;

use App\Http\Controllers\Controller;

class App extends Controller
{
    public function index()
    {
        get_lang();

        return view('app.lakasir');
    }

}
