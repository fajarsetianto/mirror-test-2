<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\Form;
use App\Models\Pivots\OfficerTarget;
use Illuminate\Notifications\Notifiable;

class Officer extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function createdBy(){
        return $this->belongsTo(User::class,'created_by');
    }

    public function targets(){
        return $this->belongsToMany(Target::class,OfficerTarget::class)
            ->withPivot(['id','is_leader','submited_at'])
            ->withTimestamps();
    }

    public function answers(){
        return $this->hasMany(OfficerAnswer::class);
    }

}
