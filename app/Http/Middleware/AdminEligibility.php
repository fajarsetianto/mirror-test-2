<?php

namespace App\Http\Middleware;

use Closure;
use Route;

class AdminEligibility
{
    
    public function handle($request, Closure $next)
    {
        $user = auth()->user();
        if($user->isSuperAdmin()){
            if(Route::is('admin.*')){
                return abort(403);
            }
        }else{
            if(Route::is('superadmin.*')){
                return abort(403);
            }
        }
        return $next($request);
    }
}
