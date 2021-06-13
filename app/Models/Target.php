<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Target extends Model
{
    protected $guarded = [];

    public function form(){
        return $this->belongsTo(Form::class);
    }

    public function officer(){
        return $this->belongsTo(User::class, 'officer_id');
    }

    public function institutionable()
    {
        return $this->morphTo();
    }

    public function officerName(){
        return $this->officer == null ? '-' : $this->officer->name;
    }

    public function respondent(){
        return $this->hasOne(Respondent::class,'target_id');
    }
}
