<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Instrument extends Model
{
    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();

        Instrument::creating(function ($model) {
            $model->position = Instrument::whereFormId($model->form_id)->max('position') + 1;
        });
    }

    public function form(){
        return $this->belongsTo(Form::class);
    }
    
    public function questions(){
        return $this->hasMany(Question::class)->orderBy('id');
    }

    public function question(){
        return $this->hasOne(Question::class);
    }

    public function offeredAnswers(){
        return $this->hasManyThrough(OfferedAnswer::class, Question::class,'instrument_id','question_id');
    }
    
    public function officerAnswer(){
        return $this->hasManyThrough(OfficerAnswer::class, Question::class, 'instrument_id', 'question_id');
    }

    public function maxScore(){
        return $this->questions()->whereHas('offeredAnswer')->get()->sum(function($item){
            if($item->questionType->name == "Kotak Centang"){
                return $item->offeredAnswer()->sum('score');
            }
            return $item->offeredAnswer()->max('score');
        });
    }
}
