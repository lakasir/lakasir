<?php

namespace App\Http\Controllers\Api\Tenants;

use App\Http\Controllers\Controller;
use App\Http\Resources\AboutResource;
use App\Models\Tenants\About;
use App\Models\Tenants\Setting;
use App\Services\Tenants\AboutService;
use Illuminate\Http\Request;

class AboutController extends Controller
{
    public function index()
    {
        return $this->buildResponse()
            ->setData(new AboutResource(About::first()))
            ->present();
    }

    public function update(Request $request, AboutService $aboutService)
    {
        $this->validate($request, [
            'shop_name' => ['nullable', 'string'],
            'shop_location' => ['nullable', 'string'],
            'business_type' => ['required', 'in:retail,wholesale,fnb,fashion,pharmacy,other'],
            'other_business_type' => ['required_if:business_type,other'],
            'owner_name' => ['nullable', 'string'],
        ]);

        $aboutService->createOrUpdate($request->all());

        Setting::set('currency', $request->currency ?? 'IDR');

        return $this->buildResponse()
            ->setMessage('About updated successfully')
            ->present();
    }
}
