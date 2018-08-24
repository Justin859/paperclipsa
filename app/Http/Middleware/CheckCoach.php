<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;

class CheckCoach
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

        $user_is_coach = \App\Coach::where('user_id', $user->id)->first();

        if(!$user_is_coach) {
            return redirect('/');
        } 

        return $next($request);
    }
}
