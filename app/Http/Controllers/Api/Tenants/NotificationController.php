<?php

namespace App\Http\Controllers\Api\Tenants;

use App\Http\Controllers\Controller;
use App\Http\Resources\NotificationCollection;
use App\Models\Tenants\Product;
use App\Models\Tenants\User;
use Illuminate\Support\Arr;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->buildResponse()
            ->setData(NotificationCollection::collection(
                auth()->user()
                    ->unreadNotifications()
                    ->where('type', '!=', 'Filament\Notifications\DatabaseNotification')
                    ->get()
            ))
            ->setMessage('success get notification')
            ->present();
    }

    public function update($notification, Product $product)
    {
        // Scoped to the signed-in user: someone else's id is a 404, not a 500
        // on a null notification.
        $found = User::findOrFail(auth()->id())
            ->notifications()
            ->where('id', $notification)
            ->firstOrFail();
        $data = array_values(Arr::where($found->data ?? [], function ($data) use ($product) {
            return ($data['id'] ?? null) != $product->id;
        }));
        $found->data = $data;
        $found->save();

        if (count($found->data) == 0) {
            $found->delete();
        }

        return $this->buildResponse()
            ->setMessage('Success delete the notification')
            ->present();

    }

    public function clear()
    {
        auth()->user()->notifications()->delete();

        return $this->buildResponse()
            ->setMessage('Success cleared the notification')
            ->present();

    }
}
