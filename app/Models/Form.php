<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Form extends Model
{
    protected $guarded = [];

    protected $dates = [
        'supervision_start_date',
        'supervision_end_date',
    ];

    public function instruments(){
        return $this->hasMany(Instrument::class);
    }

    public function indicators(){
        return $this->hasMany(Indicator::class);
    }

    public function targets(){
        return $this->hasMany(Target::class);
    }

    public function createdBy(){
        return $this->belongsTo(User::class);
    }
}
