<?php

namespace App\Http\Middleware;

use App\Module;
use Closure;

class CheckAccess
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
        $url = $request->path();
        $module = Module::where('url', $url)->first();
        if ($module)
        {
            if(check_access($module->module_name,'read'))
            {
                return $next($request);
            }
            else
            {
                abort(403);
            }
        }
        else
        {
            return $next($request);
        }
    }
}
