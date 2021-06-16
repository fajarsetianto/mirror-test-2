<?php

namespace App\Models\Pivots;

use App\Models\Officer;
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

    public function isSubmited(){
        return $this->submited_at != null;
    }

    public function getRouteKeyName()
    {
        return 'id';
    }
}
