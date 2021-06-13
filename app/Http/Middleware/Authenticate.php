<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Route;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if (!$request->expectsJson()) {
            if(Route::is('respondent.*')){
                return route('respondent.login');
            }elseif(Route::is('officer.*')){
                return route('officer.login');
            }
            return route('login');
        }
    }
}
