<?php

namespace App\Http\Middleware;

use Closure;

class AccountStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */

    // Checks if the account of the user is activated
    public function handle($request, Closure $next)
    {
        if ($request->user() && $request->user()->isActivated !== 1){
           
            return redirect('/deactivated');
        }
        return $next($request);
    }
}
