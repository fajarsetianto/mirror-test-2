<?php

namespace App\Http\Middleware\Api;

use App\Traits\ApiResponerWrapper;
use Closure;

class OfficerMiddleware
{
    use ApiResponerWrapper;
    
    
    public function handle($request, Closure $next)
    {
        if (auth()->user('officer')->tokenCan('role:officer')) {
            return $next($request);
        }

        return $this->error('Not Autorized', 401);

    }
}