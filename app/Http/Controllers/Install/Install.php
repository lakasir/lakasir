<?php

namespace App\Http\Controllers\Install;

use App\Http\Controllers\Controller;
use App\Http\Requests\Install\Company;
use App\Http\Requests\Install\Database;
use App\Http\Requests\Install\Install as InstallRequest;
use App\Http\Requests\Install\User;
use App\Jobs\SaveSessionUser;
use App\Jobs\UpdateEnv;
use App\Repositories\Company as CompanyRepository;
use App\Repositories\User as UserRepository;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class Install extends Controller
{
    /**
     * @var User
     */
    private $user;

    /**
     * @var Company
     */
    private $company;


    /**
     * @param UserRepository $user
     */
    public function __construct(UserRepository $user, CompanyRepository $company)
    {
        $this->user = $user;
        $this->company = $company;
    }

    /**
     * show install form
     *
     * @return View
     */
    public function show(): View
    {
        return view('app.install.index');
    }

    public function store(InstallRequest $request): RedirectResponse
    {
    }

    public function databaseStore(Database $request): RedirectResponse
    {
        $this->dispatchNow(new UpdateEnv([
            'DB_HOST' => $request->host,
            'DB_DATABASE' => $request->name,
            'DB_USERNAME' => $request->username,
            'DB_PASSWORD' => $request->password
        ]));

        return redirect()->to('install?tab=user');
    }

    public function userStore(User $request): RedirectResponse
    {
        $this->dispatchNow(new SaveSessionUser($request));

        return redirect()->to('install?tab=company');
    }

    public function companyStore(Company $request): RedirectResponse
    {
        $this->user->role('owner')->create($request);
        $this->company->create($request);
        $this->dispatchNow(new UpdateEnv([
            'INSTALL' => 'true'
        ]));

        return redirect()->to('/');
    }
}
