<?php

namespace App\Notifications;

use App\Models\Tenants\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DomainCreated extends Notification implements ShouldQueue
{
    use Queueable;

    public function via(User $notifiable)
    {
        return ['mail'];
    }

    public function toMail(User $notifiable)
    {
        return (new MailMessage)
            ->line('Selamat datang di Lakasir')
            ->line('Terima kasih telah menggunakan aplikasi kami!')
            ->line('Kami telah membuatkan domain untuk anda, silahkan daftarkan domain '.tenant()->domains->first()->domain.' ke aplikasi di menu domain')
            ->line('dan domain anda akan aktif dalam waktu 30 hari untuk masa percobaan')
            ->salutation('Lakasir');
    }

    public function toArray(User $notifiable)
    {
        return [
            //
        ];
    }
}
