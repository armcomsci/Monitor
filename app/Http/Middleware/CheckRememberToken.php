<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckRememberToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {   
        // dd(Auth::user()->getRememberToken(),Auth::check(), is_null(Auth::user()->getRememberToken()) );
        if (Auth::check() && Auth::user()->getRememberToken() == "" ) {
            Auth::logout();
            // $request->session()->invalidate();
            // $request->session()->regenerateToken();
            return redirect('Login');
        }
        return $next($request);
    }
}
