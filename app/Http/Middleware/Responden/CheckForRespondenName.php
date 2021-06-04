<?php

namespace App\Http\Middleware\Responden;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckForRespondenName
{
    
    public function handle($request, Closure $next)
    {
        if(Auth::guard('respondent')->user()->name == null){
            return \redirect()->route('respondent.checkpoint');
        }
            
        return $next($request);
    }
}
