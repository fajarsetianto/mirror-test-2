<?php

namespace App\Models;

use App\Models\Pivots\OfficerTarget;
use Illuminate\Database\Eloquent\Model;

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
            ->withPivot(['is_leader','submited_at'])
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
        return $this->hasOne(Respondent::class,'target_id');
    }

    public function scopeWithAndWhereHas($query, $relation, $constraint){
        return $query->whereHas($relation, $constraint)
                     ->with([$relation => $constraint]);
    }
}
