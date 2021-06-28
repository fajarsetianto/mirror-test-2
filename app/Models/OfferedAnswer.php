<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OfferedAnswer extends Model
{
    protected $guarded = [];

    public function question(){
        return $this->belongsTo(Question::class);
    }

    public function scopeByInstrumentId($query, $id){
        return $query->whereHas('question.instrument', function($q) use($id){
            $q->where('id',$id);
        });
    }
}
