<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\OfferedAnswer;

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

    public function scopeWithTargetScore($q, Target $target){
        $q->addSelect([
            'respondent_score' => OfferedAnswer::selectRaw('COALESCE(sum(offered_answers.score), 0)')
                ->join('questions','questions.id','=','offered_answers.question_id')
                ->join('user_answers','offered_answers.id','=','user_answers.offered_answer_id')
                ->join('respondents','respondents.id','=','user_answers.respondent_id')
                ->join('targets','respondents.target_id','=','targets.id')
                ->where('respondents.target_id','=', $target->id)
                ->whereColumn('questions.instrument_id','=','instruments.id')
                ->where('respondents.submited_at','<>',null)
                ->where('targets.type','=','responden'),
            'officers_score' => OfferedAnswer::selectRaw('COALESCE(sum(offered_answers.score), 0)')
                ->join('questions','questions.id','=','offered_answers.question_id' )
                ->join('officer_answers','offered_answers.id','=','officer_answers.offered_answer_id')
                ->join('officer_targets','officer_targets.target_id','=','officer_answers.target_id')
                ->join('targets','officer_targets.target_id','=','targets.id')
                ->where('officer_targets.submited_at','<>',null)
                ->where('officer_targets.target_id','=', $target->id)
                ->whereColumn('questions.instrument_id','=','instruments.id')
                ->where('targets.type','<>','responden')
        ]);
    }

    public function getMaxScoreAttribute(){
        return $this->questions()
                    ->withMaxScore()
                    ->get()
                    ->sum('max_score');
    }
}
