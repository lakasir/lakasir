<?php

namespace App\Livewire;

use App\Models\Company;
use App\Models\Tenants\User;
use Livewire\Component;

/**
 * The shop picker in the topbar. Reads the user's own companies and writes the
 * one they pick into the session, which SetCurrentCompany then honours on the
 * next request.
 */
class ShopSwitcher extends Component
{
    public function switchTo(int $companyId): void
    {
        /** @var User|null $user */
        $user = auth()->user();

        $company = $user?->companies()->find($companyId);

        if (! $company || ! $company->enabled) {
            return;
        }

        session()->put('company_id', $company->id);

        $this->redirect(request()->header('Referer') ?: '/member');
    }

    public function render()
    {
        /** @var User|null $user */
        $user = auth()->user();

        return view('livewire.shop-switcher', [
            'companies' => $user
                ? $user->companies()->orderBy('name')->get()
                : collect(),
            'current' => Company::getCurrent(),
        ]);
    }
}
