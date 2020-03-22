<?php

namespace App\Http\Middleware;

use Closure;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */

    //Check if the authenticted user is an Administrator
    public function handle($request, Closure $next)
    {
             
        if ($request->user() && $request->user()->role !== 'admin'){
            return redirect('/unauthorized');
        }
        return $next($request);

    }
}
