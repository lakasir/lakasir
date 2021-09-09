<?php

namespace App\Http\Controllers\User;

use App\Http\Requests\User\ChangePassword\Store;
use App\Services\User;
use App\Traits\User\ProfileTrait;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

/** @package App\Http\Controllers\User */
class ChangePassword
{
    use ProfileTrait;

    protected $viewPath = 'app.user.change_passwords';

    /**
     * @return View|Factory
     * @throws BindingResolutionException
     */
    public function index()
    {
        return view("{$this->viewPath}.index", [
            "resources" => $this->resources()
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
            $userService->updatePassword($request, auth()->user());
            $message = __('app.global.message.success.update', [
                'item' => ucfirst($this->password())
            ]);

            flash()->success($message);

            return redirect()->back();
        } catch (Exception $e) {
            $message = $e->getMessage();
            flash()->error($message);
            return redirect()->back();
        }
    }

}
