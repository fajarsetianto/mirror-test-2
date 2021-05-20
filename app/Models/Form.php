<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Form extends Model
{
    protected $guarded = [];

    public function instruments(){
        return $this->hasMany(Instrument::class);
    }

    public function createdBy(){
        return $this->belongsTo(User::class);
    }
}
