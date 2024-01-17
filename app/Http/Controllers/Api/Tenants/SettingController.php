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
        if (!in_array($key, ['currency', 'locale', 'methode_price', 'cash_drawer_enabled', 'all'])) {
            return $this->buildResponse()
                ->setMessage('key not found')
                ->setCode(404)
                ->present();
        }

        if ($key === 'all') {
            return $this->buildResponse()
                ->setData([
                    'currency' => Setting::get('currency', 'IDR'),
                    'locale' => Setting::get('locale', 'en'),
                    'methode_price' => Setting::get('methode_price', 'normal'),
                    'cash_drawer_enabled' => (bool) Setting::get('cash_drawer_enabled', false),
                ])
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
