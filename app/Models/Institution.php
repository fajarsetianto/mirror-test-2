<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Institution extends Model
{
    protected $guarded = [];

    public function targets(){
        return $this->hasMany(Target::class);
    }

    public function createdBy(){
        return $this->belongsTo(User::class,'created_by');
    }
}
