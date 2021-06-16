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

    public function reflection(){
        return $this->hasOne(Indicator::class,'id','id');
    }

    public function targets(){
        return $this->hasManyThrough(Target::class,Form::class,'id','form_id','form_id','id');
    }

    public function targetsWithScore(){
        return $this->reflection()->with(['targets' => function($q){
            $q->withAndWhereHas('respondent',function($q){
                $q->withCount([
                    'answers as scores' => function($q){
                            $q->join('offered_answers','offered_answers.id','=','user_answers.offered_answer_id')
                                ->select(DB::raw('SUM(score) as score'));
                            }
                ])
                ->having('scores', '>', 'indicators.minimum')
                ->having('scores', '<=', 'indicators.maximum');
            });
        }]);
        
        
        // ->targets()
    }

    
}


