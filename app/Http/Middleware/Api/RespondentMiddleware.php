<?php

namespace App\Http\Middleware\Api;

use App\Traits\ApiResponerWrapper;

class RespondentMiddleware
{
    use ApiResponerWrapper;
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (auth()->user()->tokenCan('role:respondent')) {
            return $next($request);
        }

        return $this->error('Not Autorized', 401);

    }
}