<?php

namespace App\Http\Middleware;

use Closure;

class HumanResources
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
        if (auth()->user()->role == "Human Resources") {
            
            return redirect()->to('list_of_penalties');
        }
        
        return $next($request);
    }
}
