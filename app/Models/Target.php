<?php

namespace App\Models;

use App\Models\Pivots\OfficerTarget;
use App\Models\UserAnswer;
use Illuminate\Database\Eloquent\Model;
use App\Models\Instrument;

class Target extends Model
{

    protected $guarded = [];

    public static $institutionalbeClass = [
        'non satuan pendidikan' => NonEducationalInstitution::class,
        'satuan pendidikan' => EducationalInstitution::class
    ];

    public function form(){
        return $this->belongsTo(Form::class);
    }

    public function officers(){
        return $this->belongsToMany(Officer::class,OfficerTarget::class)
            ->withPivot(['id','is_leader','submited_at'])
            ->withTimestamps();
    }

    public function isSubmitedByOfficer(){
        return $this->officers()->whereNotNull('submited_at')->exists();
    }

    public function officerLeader(){
        return $this->officers()->whereIsLeader(true);
    }

    public function institutionable(){
        return $this->morphTo();
    }

    public function respondent(){
        return $this->hasOne(Respondent::class,'target_id','id');
    }

    public function officersAnswers(){
        return $this->hasManyThrough(
            OfficerAnswer::class,
            OfficerTarget::class,
            'target_id',
            'target_id',
            'id',
            'target_id'
        );
    }

    public function respondentAnswers(){
        return $this->hasManyThrough(
            UserAnswer::class,
            Respondent::class,
            'target_id',
            'respondent_id'
        );
    }

    public function scopeByIndicator($q, $min, $max){
        $q->addSelect([
            'respondent_score' => OfferedAnswer::selectRaw('COALESCE(sum(offered_answers.score), 0)')
                ->join('user_answers','offered_answers.id','=','user_answers.offered_answer_id')
                ->join('respondents','respondents.id','=','user_answers.respondent_id')
                ->whereColumn('respondents.target_id','=', 'targets.id')
                ->where('respondents.submited_at','<>',null)
                ->where('targets.type','=','responden'),
            'officers_score' => OfferedAnswer::selectRaw('COALESCE(sum(offered_answers.score), 0)')
                ->join('officer_answers','offered_answers.id','=','officer_answers.offered_answer_id')
                ->join('officer_targets','officer_targets.target_id','=','officer_answers.target_id')
                ->where('officer_targets.submited_at','<>',null)
                ->whereColumn('officer_targets.target_id','=', 'targets.id')
                ->where('targets.type','<>','responden')
        ])->groupBy('targets.id')
            ->havingRaw('sum(respondent_score + officers_score) >= ?',[$min])
            ->havingRaw('sum(respondent_score + officers_score) <= ?',[$max]);
    }

    public function score(Instrument $instrument = null){
        if($this->type == 'responden'){
            $query = $this->respondentAnswers()
                ->join('offered_answers','offered_answers.id','=','user_answers.offered_answer_id');
        }else{
            $query = $this->officersAnswers()
                ->whereNotNull('officer_targets.submited_at')
                ->join('offered_answers','offered_answers.id','=','officer_answers.offered_answer_id');
        }
        return $query->when($instrument, function($q) use ($instrument){
            $q->join('questions','offered_answers.question_id','=','questions.id')
                ->where('questions.instrument_id','=',$instrument->id);
        })->sum('offered_answers.score');
    }
}
