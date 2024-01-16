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
            'key' => ['required', 'string', 'in:currency,locale,methode_price,cash_drawer_enabled'],
            'value' => ['required'],
        ]);

        Setting::set($request->key, $request->value);

        return $this->buildResponse()
            ->setMessage('success update setting')
            ->present();
    }

    public function show(string $key)
    {
        if (!in_array($key, ['currency', 'locale', 'methode_price', 'cash_drawer_enabled'])) {
            return $this->buildResponse()
                ->setMessage('key not found')
                ->setCode(404)
                ->present();
        }

        $setting = Setting::get($key);

        return $this->buildResponse()
            ->setData([
                'key' => $key,
                'value' => $setting,
            ])
            ->present();
    }
}
