<?php

namespace App\Notifications\Officer;

use App\Models\Pivots\OfficerTarget;
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
    public function __construct(OfficerTarget $officerTarget)
    {
        $this->officerTarget = $officerTarget->load(['target.respondent','target.institutionable','target.form']);
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
            ->subject($this->officerTarget->target->institutionable->name.' telah selesai mengisi formulir '.$this->officerTarget->target->form->name)
            ->line($this->officerTarget->target->institutionable->name.' telah mengisi formulir '.$this->officerTarget->target->form->name)
            ->line('Pada: '.$this->officerTarget->target->respondent->submited_at->format('d-m-Y'))
            ->line('Silahkan klik tautan dibawah ini untuk mulai meninjau jawaban responden')
            ->action('Tinjau', route('officer.monev.inspection.do.index',[$this->officerTarget->id]))
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
            'url' => route('officer.monev.inspection.do.index',[$this->officerTarget->id]),
            'title' => $this->officerTarget->target->respondent->name,
            'content' => $this->officerTarget->target->respondent->name.' selesai mengisi formulir '.$this->officerTarget->target->form->name
        ];
    }
}
