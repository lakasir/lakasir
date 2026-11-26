<?php

namespace App\Filament\Tenant\Resources\CompanyResource\Pages;

use App\Filament\Tenant\Resources\CompanyResource;
use App\Models\Company;
use App\Services\ProvisionCompany;
use Filament\Resources\Pages\CreateRecord;

class CreateCompany extends CreateRecord
{
    protected static string $resource = CompanyResource::class;

    /**
     * Route through the provisioning service so the new shop gets its payment
     * methods, permissions, default category and owner, not just a bare row.
     */
    protected function handleRecordCreation(array $data): Company
    {
        $company = app(ProvisionCompany::class)->create($data + [
            'owner' => auth()->user(),
            'provisioned_by' => 'local',
        ]);

        session()->put('company_id', $company->id);
        $company->makeCurrent();

        return $company;
    }

    protected function getRedirectUrl(): string
    {
        return CompanyResource::getUrl('index');
    }
}
