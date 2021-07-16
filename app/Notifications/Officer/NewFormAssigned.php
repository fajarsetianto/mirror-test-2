<?php

namespace App\Notifications\Officer;

use App\Models\Officer;
use App\Models\Target;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewFormAssigned extends Notification
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
        $this->target = $target->load(['respondent','institutionable','form','officers']);
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
        $newMail = (new MailMessage);
        $newMail->subject('Formulir Monev Baru diberikan kepada anda')
            ->line('Anda ditugaskan untuk mengisi formulir Monev '.$this->target->form->name)
            ->line('Pada lembaga '.$this->target->institutionable->name)
            ->line('Dengan batas akhir pengisian pada tanggal '.$this->target->form->supervision_end_date->format('d-m-Y'))
            ->line('Berikut struktur Tim anda : ');

            $i = 1;
            foreach($this->target->officers as $teamMate){
                $newMail->line($i.'. '.$teamMate->name.($teamMate->pivot->is_leader ? ' (Leader) ' : ''));
                $i++;
            }

        $newMail->line('Silahkan klik tautan dibawah ini untuk mulai pengisian formulir')
            ->action('Mulai', route('officer.monev.inspection.do.index',[$this->target->id]))
            ->line('Thank you for using our application!');

        return $newMail;
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
            'url' => route('officer.monev.inspection.do.index',[$this->target->id]),
            'title' => 'Formulir Monev Baru',
            'content' => 'Anda ditugaskan untuk mengisi formulir Monev '.$this->target->form->name .'pada lembaga '.$this->target->institutionable->name
        ];
    }
}
