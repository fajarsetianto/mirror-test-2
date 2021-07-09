<?php

namespace App\Models;

use App\Models\Pivots\OfficerTarget;
use App\Models\UserAnswer;
use Illuminate\Database\Eloquent\Model;
use App\Models\Instrument;
use Illuminate\Support\Facades\DB;

class Target extends Model
{
    public static $institutionalbeClass = [
        'non satuan pendidikan' => NonEducationalInstitution::class,
        'satuan pendidikan' => EducationalInstitution::class
    ];
    
    protected $guarded = [];

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

    public function officerName(){
        return $this->officerLeader->isEmpty() ? '-' : $this->officerLeader->first()->name;
    }

    public function respondent(){
        return $this->hasOne(Respondent::class,'target_id','id');
    }


    public function scopeWithAndWhereHas($query, $relation, $constraint){
        return $query->whereHas($relation, $constraint)
                     ->with([$relation => $constraint]);
    }

    public function score(Instrument $instrument = null){
        $respondentScore = OfferedAnswer::selectRaw('COALESCE(sum(offered_answers.score), 0) as score')
                            ->join('user_answers','offered_answers.id','=','user_answers.offered_answer_id')
                            ->join('respondents','respondents.id','=','user_answers.respondent_id');
        if($instrument != null){
            $respondentScore = $respondentScore->join('questions','offered_answers.question_id','=','questions.id')
                            ->where('questions.instrument_id' , $instrument->id);
        }
        $respondentScore = $respondentScore->where('respondents.target_id', $this->id)->first()->score;
        $officerScore =  OfferedAnswer::selectRaw('COALESCE(sum(offered_answers.score), 0) as score')
            ->join('officer_answers','offered_answers.id','=','officer_answers.offered_answer_id')
            ->join('officer_targets','officer_targets.target_id','=','officer_answers.target_id')
            ->where('officer_targets.is_leader', true)
            ->where('officer_targets.target_id', $this->id)
            ->where('officer_answers.target_id', $this->id);
        
        if($instrument != null){
        $officerScore = $officerScore->join('questions','offered_answers.question_id','=','questions.id')
                ->where('questions.instrument_id' , $instrument->id);
        }
        $officerScore = $officerScore->first()->score;
        switch($this->getAttribute('type')){
            case 'responden':
                return $respondentScore;
                break;
            default:
                return $officerScore;
                break;
        }
        // return $respondentScore + $officerScore;
    }

    public function scopeAddScores($q){
        switch($this->getAttribute('type')){
            case 'responden':
                $q->addSelect([
                    'score' => OfferedAnswer::selectRaw('COALESCE(sum(offered_answers.score), 0) as score')
                                    ->join('user_answers','offered_answers.id','=','user_answers.offered_answer_id')
                                    ->join('respondents','respondents.id','=','user_answers.respondent_id')
                                    ->whereColumn('respondents.target_id', 'targets.id'),
                ]);
                break;
            default:
                $q->addSelect([
                    'score' => OfferedAnswer::selectRaw('COALESCE(sum(offered_answers.score), 0) as score')
                            ->join('officer_answers','offered_answers.id','=','officer_answers.offered_answer_id')
                            ->join('officer_targets','officer_targets.target_id','=','officer_answers.target_id')
                            ->where('officer_targets.is_leader', true)
                            ->whereColumn('officer_targets.target_id', 'targets.id')
                            ->whereColumn('officer_answers.target_id', 'targets.id')
                ]);
                break;
        }
        // $q->addSelect([
        //     'respondent_score' => OfferedAnswer::selectRaw('COALESCE(sum(offered_answers.score), 0) as score')
        //                     ->join('user_answers','offered_answers.id','=','user_answers.offered_answer_id')
        //                     ->join('respondents','respondents.id','=','user_answers.respondent_id')
        //                     ->whereColumn('respondents.target_id', 'targets.id'),
        //     'officer_score' => OfferedAnswer::selectRaw('COALESCE(sum(offered_answers.score), 0) as score')
        //             ->join('officer_answers','offered_answers.id','=','officer_answers.offered_answer_id')
        //             ->join('officer_targets','officer_targets.target_id','=','officer_answers.target_id')
        //             ->where('officer_targets.is_leader', true)
        //             ->whereColumn('officer_targets.target_id', 'targets.id')
        //             ->whereColumn('officer_answers.target_id', 'targets.id')
        // ]);
    }

    public function respondentScore(){
        return $this->respondent()->exists() ? 
            $this->respondent()->with('answers.offeredAnswer')->get()
        : 0;
    }

    public function customScore(){
        switch($this->getAttribute('type')){
            case 'responden':
                return $this->hasManyThrough(UserAnswer::class,Respondent::class,'target_id','respondent_id','id','id')
                    ->join('offered_answers','user_answers.offered_answer_id','=','offered_answers.id')->select('*');
                break;
            default:
                return $this->hasManyThrough(OfficerAnswer::class,OfficerTarget::class,'target_id','target_id','id','target_id')
                        ->where('officer_targets.is_leader',true)
                        ->join('offered_answers','officer_answers.offered_answer_id','=','offered_answers.id')->select('*');
                break;
        }
    }
}
