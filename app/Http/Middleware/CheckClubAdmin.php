<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;

class CheckClubAdmin
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

        $user_is_club_admin = \App\TeamAdmin::where('user_id', $user->id)->first();
        $check_user = false;

        if($user_is_club_admin) {
            $check_user = true;
        } else if($user_is_referee) {
            $check_user = true;
        }

        if(!$check_user) {
            return redirect('/');
        } 

        return $next($request);
    }
}
