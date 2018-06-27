<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;

class CheckSuperuser
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

        $user = Auth::user();

        $user_is_referee = \App\Superuser::where('user_id', $user->id)->latest()->first();

        if(!$user_is_referee) {
            return redirect('/');
        } 

        return $next($request);
    }
}
