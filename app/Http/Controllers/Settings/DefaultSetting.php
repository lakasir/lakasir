<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;

class DefaultSetting extends Controller
{
    protected $viewPath = 'app.settings.general';

    public function index()
    {
        get_lang();

        return view($this->viewPath);
    }
}
