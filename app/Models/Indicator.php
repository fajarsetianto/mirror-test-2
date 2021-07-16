<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\OfficerAnswer;

class Indicator extends Model
{
    protected $guarded = [];

    public function form(){
        return $this->belongsTo(Form::class);
    }

    public function targets(){
        return $this->hasManyThrough(Target::class,Form::class,'id','form_id','form_id','id');
    }
    
}


