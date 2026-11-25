<?php

namespace App\Filament\Tenant\Pages;

use App\Models\Company;
use App\Traits\HasTranslatableResource;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Collection;

class Companies extends Page
{
    use HasTranslatableResource;

    protected static ?string $label = 'Switch shop';

    protected static ?string $navigationIcon = 'heroicon-o-arrows-right-left';

    // CompanyResource already owns /member/companies, so the switcher needs a
    // slug of its own or the resource's namespace swallows it.
    protected static ?string $slug = 'switch-shop';

    protected static string $view = 'filament.tenant.pages.companies';

    /** Reachable from the user menu, not the sidebar. */
    protected static bool $shouldRegisterNavigation = false;

    public static function canAccess(): bool
    {
        return can('access general setting');
    }

    /**
     * Only the shops this user belongs to. A company they cannot enter must
     * never appear here, since switching makes it the current one.
     */
    /**
     * @return Collection<int, Company>
     */
    public function getCompaniesProperty()
    {
        /** @var \Illuminate\Database\Eloquent\Collection<int, Company> $companies */
        $companies = auth()->user()
            ->companies()
            ->orderBy('name')
            ->get();

        return $companies;
    }

    public function switchTo(int $companyId): void
    {
        /** @var Company|null $company */
        $company = auth()->user()->companies()->find($companyId);

        if (! $company) {
            return;
        }

        if (! $company->enabled) {
            Notification::make()
                ->title(__('This shop is disabled'))
                ->danger()
                ->send();

            return;
        }

        session()->put('company_id', $company->id);

        $company->makeCurrent();

        $this->redirect(static::getUrl());
    }
}
