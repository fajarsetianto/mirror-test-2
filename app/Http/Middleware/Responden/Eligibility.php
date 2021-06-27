<?php

namespace App\Http\Middleware\Responden;

use Closure;
use Illuminate\Support\Facades\Auth;

class Eligibility
{
    
    public function handle($request, Closure $next)
    {
        $user = auth('respondent')->user()->load('target.form');
        if($user->isSubmited() || $user->target->form->isExpired()){
            return \redirect()->route('respondent.stop');
        }
        return $next($request);
    }
}
