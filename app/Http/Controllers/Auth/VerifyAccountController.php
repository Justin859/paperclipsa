<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Mail\VerifyEmail;

class VerifyAccountController extends Controller
{
    //

    public function verify_submission()
    {
        $user = \Auth::user();
        $user_verified = \App\VerifiedUser::where('user_id', $user->id)->first();

        if($user_verified)
        {
            if(!$user->verifyToken)
            {
                $update_user_verifytoken = \App\User::find($user->id)->update(['verifyToken' => bin2hex(random_bytes(13))]);
            } 

            $data = ['message' => 'Test Message', 'user_id' => $user->id, 'verifyToken' => $user->verifyToken];

            if($user_verified->verified)
            {
                \Session::flash('error', 'Your email address has already been verified.');
                return redirect()->back();
            } else {
                \Mail::to($user->email)->send(new VerifyEmail($data));
                \Session::flash('success', 'A verification email has been sent to: ' .$user->email);
                return redirect()->back();
            }
            
        } else {
            $update_user_verifytoken = \App\User::find($user->id)->update(['verifyToken' => bin2hex(random_bytes(13))]);
            $new_verified_user = \App\VerifiedUser::create(['user_id' => $user->id, 'email' => $user->email, 'verified' => 0]);
            \Mail::to($user->email)->send(new VerifyEmail($data));
            \Session::flash('success', 'A verification email has been sent to: '.$user->email);
            return redirect()->back();
        }
    }

    public function verify_user($user_id, $verify_token)
    {
        $user = \App\User::where(['id' => $user_id, 'verifyToken' => $verify_token])->first();
        $user_verified = \App\VerifiedUser::where('user_id', $user->id)->first();

        if($user->verifyToken == $verify_token)
        {
            if(!$user_verified->verified)
            {
                $user_verified->update(['verified' => 1]);
                return view('auth.user_verified');

            } else {
                return view('auth.user_verified');

            }

        } else {
            return abort(404);
        }

    }

}
