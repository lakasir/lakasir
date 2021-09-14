<?php

namespace App\Http\Controllers\Settings\General;

use Illuminate\View\View;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\Container\BindingResolutionException;

/** @package App\Http\Controllers\Settings\General */
class Date
{
    private $viewPath = 'app.settings.general.date';

    /**
     * @return View|Factory
     * @throws BindingResolutionException
     */
    public function index()
    {
        return view("{$this->viewPath}.index", [
            'resources' => 'setting.general.date'
        ]);
    }
}
