<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;

class CheckReferee
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

        $user_is_referee = \App\Referee::where('user_id', $user->id)->first();

        if(!$user_is_referee) {
            return redirect('/');
        } 
        
        return $next($request);
    }
}
