<?php

namespace App\Models\Pivots;

use Illuminate\Database\Eloquent\Relations\Pivot;

class OfficerTarget extends Pivot
{
    protected $guarded = [];

    protected $table = 'officer_targets';

    public function question(){
        return $this->belongsTo(Question::class);
    }
}
