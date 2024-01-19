<?php

namespace App\Http\Controllers\Api\Tenants\Settings;

use App\Http\Controllers\Controller;
use App\Models\Tenants\SecureInitialPrice;
use App\Models\Tenants\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class SecureInitialPriceController extends Controller
{
    public function store(Request $request)
    {
        if (!Setting::get('secure_initial_price_enabled', false)) {
            return $this->buildResponse()
                ->setMessage('secure initial price is not enabled')
                ->setCode(403)
                ->present();
        }

        $this->validate($request, [
            'password' => ['required', 'string', 'confirmed'],
        ]);

        SecureInitialPrice::create([
            'password' => bcrypt($request->password),
            'user_id' => auth()->user()->id,
        ]);

        return $this->buildResponse()
            ->setMessage('secure initial price password has been set')
            ->present();
    }

    public function verify(Request $request)
    {
       if (!Setting::get('secure_initial_price_enabled', false)) {
            return $this->buildResponse()
                ->setMessage('secure initial price is not enabled')
                ->setCode(403)
                ->present();
        }

        $this->validate($request, [
            'password' => ['required', 'string'],
        ]);

        $secureInitialPrice = SecureInitialPrice::where('user_id', auth()->user()->id)->first();

        if (!$secureInitialPrice) {
            return $this->buildResponse()
                ->setMessage('secure initial price password has not been set')
                ->setCode(403)
                ->present();
        }

        if (!Hash::check($request->password, $secureInitialPrice->password)) {
            return $this->buildResponse()
                ->setMessage('secure initial price password is not valid')
                ->setCode(403)
                ->present();
        }

        return $this->buildResponse()
            ->setMessage('secure initial price password is valid')
            ->present();
    }

    public function update(Request $request)
    {
        if (!Setting::get('secure_initial_price_enabled', false)) {
            return $this->buildResponse()
                ->setMessage('secure initial price is not enabled')
                ->setCode(403)
                ->present();
        }

        $this->validate($request, [
            'old_password' => ['required', 'string'],
            'password' => ['required', 'string', 'confirmed'],
        ]);

        if (!Hash::check($request->old_password, auth()->user()->secureInitialPrice->password)) {
            return $this->buildResponse()
                ->setMessage('old password is not valid')
                ->setCode(403)
                ->present();
        }

        auth()->user()->secureInitialPrice->update([
            'password' => bcrypt($request->password),
        ]);

        return $this->buildResponse()
            ->setMessage('secure initial price password has been updated')
            ->present();
    }
}
