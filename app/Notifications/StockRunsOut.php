<?php

namespace App\Notifications;

use App\Filament\Tenant\Resources\ProductResource;
use App\Models\Tenants\User;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification as NotificationsNotification;
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

    public function via(User $user)
    {
        if ($user->fcm_token) {
            if (env('FIREBASE_CREDENTIALS')) {
                return [FcmChannel::class, 'database'];
            }
        }

        return ['database'];
    }

    public function toArray(User $notifiable)
    {
        $convertedArray = [];
        foreach ($this->data as $data) {
            $url = ProductResource::getUrl(
                name: 'view',
                parameters: [
                    'record' => $data['id'],
                ],
                isAbsolute: false,
                panel: 'tenant'
            );

            $productName = [
                'product' => $data['name'],
            ];
            $description = $data['stock'] > 0
                ? __('notifications.stocks.single-runs-out', $productName)
                : __('notifications.stocks.single-out-of-stock', $productName);

            $notifiable->notify(
                NotificationsNotification::make()
                    ->title($description)
                    ->body(__('notifications.stocks.field_stock', [
                        'stock' => $data['stock'],
                    ]))
                    ->warning()
                    ->actions([
                        Action::make(__('View'))
                            ->icon('heroicon-s-eye')
                            ->markAsRead()
                            ->url($url),
                    ])
                    ->toDatabase(),
            );
            $data = array_merge($data, [
                'name' => $description,
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
        if ($notifiable->fcm_token) {
            $locale = $notifiable?->profile?->locale ?? 'en';
            App::setLocale($locale);
            $productName = [
                'product' => $this->data[0]['name'],
            ];
            $description = $this->data[0]['stock'] > 0
                ? __('notifications.stocks.single-runs-out', $productName)
                : __('notifications.stocks.single-out-of-stock', $productName);
            $body = $description;

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
}
