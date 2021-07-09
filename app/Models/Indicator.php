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

    // PERHATIAN
    // RELASI INI TIDAK BISA DIGUNAKAN BERSAMA EAGERLOAD

    public function targetsIn(){
        $min = $this->minimum;
        $max = $this->maximum;
        return $this->targets()->addScores()
        ->groupBy('targets.id')
        ->havingRaw('score >= '.$min)
        ->havingRaw('score <= '.$max);
    }
    
}


