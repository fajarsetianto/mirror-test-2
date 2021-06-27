<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->check()) {
            if($guard == null || $guard == 'web'){
                return redirect(RouteServiceProvider::HOME);
            }elseif($guard == 'respondent'){
                return redirect()->route('respondent.dashboard');
            }elseif($guard == 'officer'){
                return redirect()->route('officer.dashboard');
            }
        }
        return $next($request);
    }
}
