<?php

namespace App\Notifications;

use App\Models\Tenants\User;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\App;
use NotificationChannels\Fcm\FcmChannel;
use NotificationChannels\Fcm\FcmMessage;
use NotificationChannels\Fcm\Resources\Notification as FcmNotification;

class StockRunsOut extends Notification
{
    public function __construct(private array $data)
    {
    }

    public function via()
    {
        return [FcmChannel::class, 'database'];
    }

    public function toArray($notifiable)
    {
        $convertedArray = [];
        foreach ($this->data as $data) {
            $data = array_merge($data, [
                'name' => __('notifications.stocks.single-runs-out', [
                    'product' => $data['name'],
                ]),
                'stock' => __('notifications.stocks.field_stock', [
                    'stock' => $data['stock'],
                ]),
                'route' => '/menu/product/stock',
            ]);
            $convertedArray[] = $data;
        }

        return $convertedArray;
    }

    public function toFcm(User $notifiable): FcmMessage
    {
        $locale = $notifiable?->profile?->locale ?? 'en';
        App::setLocale($locale);
        $body = __('notifications.stocks.single-runs-out', [
            'product' => $this->data[0]['name'],
        ]);
        $title = __('notifications.stocks.title');
        if (count($this->data) > 0) {
            $body = __('notifications.stocks.multiple-runs-out', [
                'count' => count($this->data),
            ]);
        }

        return (
            new FcmMessage(
                notification: new FcmNotification(
                    title: $title,
                    body: $body,
                )
            ))
                ->data([
                    'title' => $title,
                    'body' => $body,
                ])
                ->custom([
                    'android' => [
                        'notification' => [
                            'color' => '#0A0A0A',
                        ],
                        'fcm_options' => [
                            'analytics_label' => 'analytics',
                        ],
                    ],
                    'apns' => [
                        'fcm_options' => [
                            'analytics_label' => 'analytics',
                        ],
                    ],
                ]);
    }
}
