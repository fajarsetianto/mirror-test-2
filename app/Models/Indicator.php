<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Indicator extends Model
{
    protected $guarded = [];

    public function form(){
        return $this->belongsTo(Form::class);
    }

    public function targets(){
        return $this->hasManyThrough(Target::class,Form::class,'id','form_id','form_id','id');
    }

    public function targetsWithScore(){
        return $this->targets()->withAndWhereHas('respondent',function($q){
                    $q->withCount([
                        'answers as score' => function($q){
                                $q->leftJoin('offered_answers','offered_answers.id','=','user_answers.offered_answer_id')
                                    ->select(DB::raw('SUM(score) as score'));
                                }
                    ])
                    ->having('score', '>', 'indicators.minimum')
                    ->having('score', '<=', 'indicators.maximum');
                });
    }

    
}


