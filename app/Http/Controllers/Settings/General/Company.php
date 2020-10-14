<?php

namespace App\Http\Controllers\Settings\General;

use App\Http\Controllers\Controller;
use App\Http\Requests\Master\Company\Store;
use App\Repositories\Company as CompanyRepositories;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class Company extends Controller
{
    private $viewPath = 'app.settings.general.company.index';

    /**
     * @var Company
     */
    private $company;

    /**
     * @param App\Repositories\Company $company
     */
    public function __construct(CompanyRepositories $company)
    {
        $this->company = $company;
    }

    /**
     * Get Form edit company
     *
     * @return view
     */
    public function index(Request $request): View
    {
        get_lang();

        return view($this->viewPath, ['data' => $this->company->query()->first()]);
    }

    /**
     * undocumented function
     *
     * @return void
     */
    public function store(Store $request): RedirectResponse
    {
        get_lang();

        $this->company->updateOrCreate($request);

        return redirect()->back();
    }


}
