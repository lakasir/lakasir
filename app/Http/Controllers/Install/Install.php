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
use Illuminate\Support\Facades\Artisan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
     * @param companyRepository $company
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
        $is_connected = $this->checkDatabaseConnection($request, true);

        if ($is_connected !==true) {
            return $is_connected;
        }

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
        $is_connected = $this->checkDatabaseConnection($request);

        if ($is_connected !==true) {
            return $is_connected;
        }

        $this->dispatchNow(new SaveSessionUser($request));

        return redirect()->to('install?tab=company');
    }

    public function companyStore(Company $request): RedirectResponse
    {
        $is_connected = $this->checkDatabaseConnection($request);

        if ($is_connected !==true) {
            return $is_connected;
        }

        /**
         * FIXME: the first value is don't wan't change <sheenazien 2020-07-02>
         * INSTALL variable env
         */

        $this->user->role('owner')->create($request);
        $this->company->create($request);
        if (app()->environment() == 'production' || app()->environment() == 'local') {
            $this->dispatchNow(new UpdateEnv([
                'INSTALL' => 'true'
            ]));
        }

        return redirect()->to('/completed');
    }

    private function checkDatabaseConnection($request = null, $set_config = false)
    {
        try {
            if ($set_config) {
                \Config::set('database.connections.mysql', [
                    'host' => $request->host,
                    'database' => $request->name,
                    'username' => $request->username,
                    'password' => $request->password,
                    'driver' => 'mysql',
                ]);
            }
            DB::connection()->reconnect();
            DB::connection()->getPdo();
            Artisan::call('migrate:fresh');
            Artisan::call('db:seed');

            return true;
        } catch (\Exception $e) {
            return redirect()->to('install?tab=database')->with('database_error_message', $e->getMessage());
        }
    }
}
