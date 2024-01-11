<?php

namespace App\Http\Controllers\Api\Tenants;

use App\Http\Controllers\Controller;
use App\Models\Tenants\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function store(Request $request)
    {
        $this->validate($request, [
            'key' => ['required', 'string', 'in:currency,locale,methode_price'],
            'value' => ['required', 'string'],
        ]);

        Setting::set($request->key, $request->value);

        return $this->buildResponse()
            ->setMessage('success update setting')
            ->present();
    }
}
