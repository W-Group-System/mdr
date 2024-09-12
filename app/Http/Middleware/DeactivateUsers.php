<?php

namespace App\Http\Middleware;

use Closure;

class DeactivateUsers
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
        if (auth()->check() && auth()->user()->status == "Inactive") {

            auth()->logout();
            
            return redirect('/login')
                ->withErrors('Your account is deactivated. Please contact the administrator.');
        }
        
        return $next($request);
    }
}
