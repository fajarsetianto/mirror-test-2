<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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

    public function userAnswerRespondent(){
        return $this->hasOne(UserAnswer::class)->where('respondent_id',auth('respondent')->user()->id);
    }
    
    public function userAnswer(){
        return $this->hasOne(UserAnswer::class);
    }

    public function userAnswers(){
        return $this->hasMany(UserAnswer::class);
    }
    
    public function scopeByTargetId($query, $id){
        return $query->whereHas('officerAnswer', function($q) use($id){
            $q->where('target_id', $id);
        });
    }

    public function officerAnswer(){ 
        return $this->hasMany(OfficerAnswer::class);
    }
    
    public function officerAnswerOfficer(){
        return $this->hasOne(OfficerAnswer::class)->where('officer_id',auth('officer')->user()->id);
    }

    public function scopeWithMaxScore($q){
        return $q->addSelect([
            'max_score' => OfferedAnswer::select(DB::raw('CASE WHEN question_type.name = "Kotak Centang" THEN sum(score) ELSE max(score) END'))
                            ->whereColumn('questions.id', '=','offered_answers.question_id')
                            ->join('questions as q','offered_answers.question_id','=','q.id')
                            ->join('question_type','question_type.id','q.question_type_id')
        ]);
    }

}
