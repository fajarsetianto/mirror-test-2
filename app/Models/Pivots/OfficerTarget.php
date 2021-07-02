<?php

namespace App\Models\Pivots;

use App\Models\Officer;
use App\Models\OfficerAnswer;
use App\Models\OfficerNote;
use App\Models\Target;
use Illuminate\Database\Eloquent\Relations\Pivot;

class OfficerTarget extends Pivot
{
    protected $guarded = [];

    protected $table = 'officer_targets';

    protected $dates = [
        'submited_at'
    ];

    public function target(){
        return $this->belongsTo(Target::class);
    }

    public function officer(){
        return $this->belongsTo(Officer::class);
    }

    public function officerNote(){
        return $this->hasMany(OfficerNote::class,'officer_target_id')->orderBy('type');
    }
    
    public function isLeader(){
        return $this->is_leader == '1';
    }

    public function answers(){
        return $this->hasMany(OfficerAnswer::class,'officer_id','officer_id')->where('target_id',$this->target_id)->orderBy('question_id');
    }

    public function isSubmited(){
        return $this->submited_at != null;
    }

    public function getRouteKeyName()
    {
        return 'id';
    }
}
