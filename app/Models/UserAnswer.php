<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class UserAnswer extends Model
{
    protected $guarded = [];
    

    public function instrument(){
        return $this->hasOneThrough(Instrument::class, Question::class, 'id', 'id','question_id','instrument_id');
    }

    public function offeredAnswer(){
        return $this->belongsTo(OfferedAnswer::class);
    }

    public function question(){
        return $this->belongsTo(Question::class);
    }

    public function scopeByInstrumentId($query, $id){
        return $query->whereHas('question.instrument', function($q) use($id){
            $q->where('id',$id);
        });
    }

    public function questionInstrument($id){
        return $this->question()->where('instrument_id',$id)->first();
    }

    public function getScoreAttribute(){
        return $this->offeredAnswer()->exists() ? $this->offeredAnswer->score : 0;
    }

}
