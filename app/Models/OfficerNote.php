<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OfficerNote extends Model
{
    protected $guarded = [];

    public function target(){
        return $this->belongsTo(Target::class);
    }

    public function officer(){
        return $this->belongsTo(Officer::class);
    }
}
