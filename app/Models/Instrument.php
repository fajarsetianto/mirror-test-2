<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Instrument extends Model
{
    protected $guarded = [];

    public function form(){
        return $this->belongsTo(Form::class);
    }
    
    public function questions(){
        return $this->hasMany(Question::class);
    }

    public function maxScore(){
        return $this->questions()->whereHas('offeredAnswer')->get()->sum(function($item){
            if($item->questionType->name == "Multiple Choice"){
                return $item->offeredAnswer()->sum('score');
            }
            return $item->offeredAnswer()->max('score');
        });
    }
}
