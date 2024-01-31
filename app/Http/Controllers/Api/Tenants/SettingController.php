<?php

namespace App\Http\Controllers\Api\Tenants;

use App\Http\Controllers\Controller;
use App\Models\Tenants\Setting;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SettingController extends Controller
{
    public function store(Request $request)
    {
        $this->validate($request, [
            'key' => [
                'required',
                'string',
                Rule::in([
                    'currency',
                    'locale',
                    'methode_price',
                    'cash_drawer_enabled',
                    'secure_initial_price_enabled',
                    'secure_initial_price_using_pin',
                    'default_tax',
                ]),
                function ($attribute, $value, $fail) use ($request) {
                    if ($value == 'default_tax') {
                        if (! is_numeric($request->value)) {
                            $fail('The '.$attribute.' should be numeric.');

                            return;
                        }
                        if ($request->value < 0 || $request->value > 100) {
                            $fail('The '.$attribute.' should be between 0 and 100.');

                            return;
                        }
                    }
                    if (in_array($value, ['cash_drawer_enabled', 'secure_initial_price_enabled', 'secure_initial_price_using_pin'])) {
                        if (! is_bool($request->value)) {
                            $fail('The '.$attribute.' should be boolean.');

                            return;
                        }
                    }
                },
            ],
            'value' => ['required'],
        ]);

        Setting::set($request->key, $request->value);

        return $this->buildResponse()
            ->setMessage('success update setting')
            ->present();
    }

    public function show(string $key)
    {
        if (! in_array($key, [
            'currency',
            'locale',
            'methode_price',
            'cash_drawer_enabled',
            'secure_initial_price_enabled',
            'secure_initial_price_using_pin',
            'default_tax',
            'all',
        ])) {
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
                    'methode_price' => Setting::get('methode_price', 'fifo'),
                    'cash_drawer_enabled' => (bool) Setting::get('cash_drawer_enabled', false),
                    'secure_initial_price_enabled' => (bool) Setting::get('secure_initial_price_enabled', false),
                    'secure_initial_price_using_pin' => (bool) Setting::get('secure_initial_price_using_pin', false),
                    'default_tax' => (float) Setting::get('default_tax', 0),
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
