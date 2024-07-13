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
        $notification = User::find(auth()->id())
            ->notifications()
            ->where('id', $notification)
            ->first();
        $data = array_values(Arr::where($notification->data, function ($data) use ($product) {
            return $data['id'] != $product->id;
        }));
        $notification->data = $data;
        $notification->save();

        if (count($notification->data) == 0) {
            $notification->delete();
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
