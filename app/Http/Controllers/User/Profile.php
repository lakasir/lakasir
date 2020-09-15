<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\Profile\Index;
use App\Http\Requests\User\Profile\Store;
use App\Repositories\Profile as ProfileRepository;
use App\Services\ProfileService;
use App\Traits\HasCrudActions;
use Lakasir\UserLoggingActivity\Models\Activity;

class Profile extends Controller
{
    use HasCrudActions;

    protected $viewPath = 'app.user.profiles';

    protected $permission = 'profile';

    protected $redirect = '/user/profile';

    protected $storeRequest = Store::class;

    protected $indexRequest = Index::class;

    protected $repositoryClass = ProfileRepository::class;

    protected $storeService = [ProfileService::class, 'create'];
    /**
     * Display a listing of the resource.
     *
     * @return mix
     */
    public function index()
    {
        get_lang();

        $request = resolve($this->indexRequest);

        $this->authorize("browse-$this->permission");

        $data = collect();
        $data->put('activity', Activity::where('user_id', auth()->user()->id)->latest()->cursor()->groupBy(function ($activity) {
            return $activity->created_at->format('Y-m-d');
        }));

        $resources = $this->permission;

        return view("{$this->viewPath}.index", [
            'resources' => $resources,
            'data' => $data
        ]);
    }
}
