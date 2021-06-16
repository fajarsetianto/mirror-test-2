<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Respondent extends Authenticatable
{
    use Notifiable;

    protected $dates = [
        'submited_at'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        
    ];

        /**
     * Get the password for the user.
     *
     * @return string
     */
    public function getAuthPassword()
    {
        return $this->token;
    }

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
       
    ];

    public function target(){
        return $this->belongsTo(Target::class);
    }

    public function routeNotificationForMail(){
        return $this->target->institutionable->email;
    }

    public function isSubmited(){
        return $this->submited_at != null;
    }

    public function answers(){
        return $this->hasMany(UserAnswer::class);
    }

    public function scoreCountByInstrument(Instrument $instrument){    
        return $this->answers()->whereHas('instrument', function($item) use ($instrument){
            $item->where('instruments.id',$instrument->id);
        })->whereHas('offeredAnswer')->with('offeredAnswer')
            ->get()
            ->sum('offeredAnswer.score');
    }

    public function answeredQuestionsCountByInstrument(Instrument $instrument){
        return $this->answers()->whereHas('instrument', function($item) use ($instrument){
            $item->where('instruments.id',$instrument->id);
        })->distinct('question_id')->count();
    }

    public function isAllQuestionsAnswered(){
        $this->load('target.form');
        return $this->answers()->distinct('question_id')->count() == $this->target->form->questions()->count();
    }

}
