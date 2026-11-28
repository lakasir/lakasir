<?php

namespace App\Traits;

use App\Models\Company;
use App\Scopes\CompanyScope;
use Illuminate\Database\Eloquent\Builder;

/**
 * Company-owned model. Every table using this trait carries a company_id
 * column, filtered by the global scope and filled in on insert.
 */
trait BelongsToCompany
{
    public static function bootBelongsToCompany(): void
    {
        static::addGlobalScope(new CompanyScope);
    }

    /**
     * Every insert path ends here, including createQuietly() and
     * withoutEvents(), which never fire the creating event.
     */
    protected function performInsert(Builder $query)
    {
        $this->fillCompanyId();

        return parent::performInsert($query);
    }

    public function fillCompanyId(): void
    {
        if ($this->getAttribute('company_id') === null && ($id = Company::currentId())) {
            $this->setAttribute('company_id', $id);
        }
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
