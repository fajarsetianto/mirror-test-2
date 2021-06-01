<?php

namespace App\Notifications;

use App\Models\Target;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TokenNotification extends Notification
{
    use Queueable;

    protected $target;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Target $target)
    {
        $this->target = $target;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('Anda diudang untuk menjadi responden pada sistem monitoring dan evaluasi')
                    ->line('Token anda adalah ' . $this->target->respondent->plain_token)
                    ->line('Silahkan halaman berikut untuk mulai mengerjakan')
                    ->action('Notification Action', route('respondent.login'))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
