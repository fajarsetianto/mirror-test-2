<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $guarded = [];

    public function instrument(){
        return $this->belongsTo(Instrument::class);
    }

    public function offeredAnswer(){
        return $this->hasMany(OfferedAnswer::class);
    }

    public function questionType(){
        return $this->belongsTo(QuestionType::class);
    }
}
