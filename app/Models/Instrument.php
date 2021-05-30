<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Instrument extends Model
{
    protected $guarded = [];

    public function form(){
        return $this->belongsTo(Form::class);
    }
    
    public function questions(){
        return $this->hasMany(Question::class);
    }
}
