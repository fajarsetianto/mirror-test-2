<?php

namespace App\Notifications\Admin;

use App\Models\Officer;
use App\Models\Target;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RespondentSubmitedAnswer extends Notification
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
        $this->target = $target->load(['respondent','institutionable','form']);
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail','database'];
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
            ->subject($this->target->institutionable->name.' telah selesai mengisi formulir '.$this->target->form->name)
            ->line($this->target->institutionable->name.' telah mengisi formulir '.$this->target->form->name)
            ->line('Pada: '.$this->target->respondent->submited_at->format('d-m-Y'))
            ->line('Silahkan klik tautan dibawah ini untuk mulai meninjau jawaban responden')
            ->action('Tinjau',  route('admin.monev.inspection.form.detail',[$this->target->form->id,$this->target->id]))
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
            'url' => route('admin.monev.inspection.form.detail',[$this->target->form->id,$this->target->id]),
            'title' => $this->target->respondent->name,
            'content' => $this->target->respondent->name.' selesai mengisi formulir '.$this->target->form->name
        ];
    }
}
