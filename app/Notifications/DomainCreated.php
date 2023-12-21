<?php

namespace App\Notifications;

use App\TenantUser;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DomainCreated extends Notification
{
    use Queueable;

    public function via(TenantUser $notifiable)
    {
        return ['mail'];
    }

    public function toMail(TenantUser $notifiable)
    {
        return (new MailMessage)
            ->line('Selamat datang di Lakasir')
            ->line('Terima kasih telah menggunakan aplikasi kami!')
            ->line('Kami telah membuatkan domain untuk anda, silahkan daftarkan domain '. $notifiable->tenant->domains->first()->domain .' ke aplikasi di menu domain')
            ->line('dan domain anda akan aktif dalam waktu 30 hari untuk masa percobaan')
            ->salutation('Lakasir');
    }

    public function toArray(TenantUser $notifiable)
    {
        return [
            //
        ];
    }
}
