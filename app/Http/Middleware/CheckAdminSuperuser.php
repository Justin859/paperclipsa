<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;

class CheckAdminSuperuser
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

        $user_is_admin = \App\Admin::where('user_id', $user->id)->first();
        $user_is_superuser = \App\SuperUser::where('user_id', $user->id)->first();
        $check_user = false;

        if($user_is_admin) {
            $check_user = true;
        } else if($user_is_superuser) {
            $check_user = true;
        }

        if(!$check_user) {
            return redirect('/');
        } 

        return $next($request);
    }
}
