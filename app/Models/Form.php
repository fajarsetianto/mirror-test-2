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

    public function questions(){
        return $this->hasManyThrough(Question::class, Instrument::class);
    }

    public function targets(){
        return $this->hasMany(Target::class);
    }

    public function createdBy(){
        return $this->belongsTo(User::class);
    }

    public function supervisionDaysRemaining(){
        return $this->supervision_start_date->diffInDays($this->supervision_end_date);
    }

    public function isEditable(){
        return $this->status == 'draft';
    }

    public function isPublishable(){
        return $this->questions()->exists() && !$this->instruments()->whereStatus('draft')->exists() && $this->targets()->exists();
    }
}
