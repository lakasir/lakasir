<?php

namespace App\Http\Controllers\Api\Tenants;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenants\AboutRequest;
use App\Http\Resources\AboutResource;
use App\Models\Tenants\About;
use App\Models\Tenants\Setting;
use App\Services\Tenants\AboutService;

class AboutController extends Controller
{
    public function index()
    {
        return $this->buildResponse()
            ->setData(new AboutResource(About::first()))
            ->present();
    }

    public function update(AboutRequest $request, AboutService $aboutService)
    {
        $data = $request->validated();

        $aboutService->createOrUpdate($data);

        Setting::set('currency', $data['currency'] ?? 'IDR');

        return $this->buildResponse()
            ->setMessage('About updated successfully')
            ->present();
    }
}