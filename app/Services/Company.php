<?php

namespace App\Services;

use App\Abstracts\Repository as RepositoryAbstract;
use App\Builder\NumberGeneratorBuilder;
use App\Helpers\NumberGenerator;
use App\Models\Company as CompanyModel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Company extends RepositoryAbstract
{
    protected string $model = CompanyModel::class;

    public function create(Request $request)
    {
        $self = $this;
        return DB::transaction(static function () use ($request, $self) {
            if (isset($request->session()->all()['user'])) {
                $session = $request->session()->all()['user'];
            }
            if (!$request->reg_number) {
                $numberGenerator = (new NumberGeneratorBuilder())->prefix('LA')->model($self->model)->build();
                $request->merge([
                    'reg_number' => $numberGenerator->create()
                ]);
            }
            $company = new $self->model();
            $company->fill($request->all());
            $company->save();

            return $company;
        });
    }

    /**
     * undocumented function
     *
     * @return void
     */
    public function updateOrCreate(Request $request): CompanyModel
    {
        $company = $this->query()->first();
        if (!$company) {
            $company = $this->create($request);

            $message = __('app.global.message.create').' '. ucfirst(__('app.companies.title'));

            flash()->success(dash_to_space($message));
        } else {
            $message = __('app.global.message.update').' '. ucfirst(__('app.companies.title'));

            flash()->success(dash_to_space($message));

            $company = $this->update($request, $company);
        }
        return $company;
    }

}
