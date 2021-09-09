<?php

namespace App\Http\Controllers\User;

use App\Http\Requests\User\Profile\Store;
use App\Models\Company;
use App\Services\User;
use App\Traits\User\ProfileTrait;
use Exception;
use Illuminate\View\View;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\RedirectResponse;

/** @package App\Http\Controllers\User */
class Profile
{
    use ProfileTrait;

    protected $viewPath = "app.user.profiles";

    /**
     * @return View|Factory
     * @throws BindingResolutionException
     */
    public function index()
    {
        return view("{$this->viewPath}.index", [
            'auth' => auth()->user(),
            'company' => Company::first()
        ]);
    }

    /**
     * @param Store $request
     * @param User $userService
     * @return RedirectResponse|void
     */
    public function store(Store $request, User $userService)
    {
        try {
            $userService->updateProfile($request, auth()->user());
            $message = __('app.global.message.success.update', [
                'item' => ucfirst($this->resources())
            ]);
            flash()->success($message);

            return redirect()->back();
        } catch (Exception $e) {
            $message = $e->getMessage();
            flash()->error($message);
            return redirect()->back()->withInput($request->except(['photo_profile']));
        }

    }
}
