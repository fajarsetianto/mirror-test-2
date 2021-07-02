<?php

namespace App\Models;

use App\Models\Pivots\OfficerTarget;
use App\Models\UserAnswer;
use Illuminate\Database\Eloquent\Model;
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

    public function score(){
        $respondentScore = OfferedAnswer::selectRaw('COALESCE(sum(offered_answers.score), 0) as score')
                            ->join('user_answers','offered_answers.id','=','user_answers.offered_answer_id')
                            ->join('respondents','respondents.id','=','user_answers.respondent_id')
                            ->where('respondents.target_id', $this->id)->score;
        $officerScore = OfferedAnswer::selectRaw('COALESCE(sum(offered_answers.score), 0) as score')
                            ->join('officer_answers','offered_answers.id','=','officer_answers.offered_answer_id','left outer')
                            ->join('officer_targets','officer_targets.target_id','=','officer_answers.target_id','left outer')
                            ->where('officer_targets.target_id', $this->id)->score;
        return $respondentScore + $officerScore;
    }

    public function scopeAddScores($q){
        $q->addSelect([
            'respondent_score' => OfferedAnswer::selectRaw('COALESCE(sum(offered_answers.score), 0) as score')
                            ->join('user_answers','offered_answers.id','=','user_answers.offered_answer_id')
                            ->join('respondents','respondents.id','=','user_answers.respondent_id')
                            ->whereColumn('respondents.target_id', 'targets.id'),
            'officer_score' => OfferedAnswer::selectRaw('COALESCE(sum(offered_answers.score), 0) as score')
                    ->join('officer_answers','offered_answers.id','=','officer_answers.offered_answer_id','left outer')
                    ->join('officer_targets','officer_targets.target_id','=','officer_answers.target_id','left outer')
                    ->whereColumn('officer_targets.target_id', 'targets.id'),
        ]);
    }
}
