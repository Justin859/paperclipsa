<?php

namespace App\Http\Controllers\auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;

class RegistrationController extends Controller
{
    //

    public function registration_view()
    {

        Auth::logout();

        return view('auth.registration');
    }

    public function register(Request $request)
    {        
        $validatedData = $request->validate([
            'firstname' => 'required|max:255',
            'surname' => 'required|max:255',
            'email' => 'required|email|unique:users|max:255',
            'country' => 'required|max:255',
            'city' => 'required|max:255',
            'province' => 'required|max:255',
            'tel' => 'required|regex:/^[0-9]{1,45}$/|max:10',
            'id_number' => 'required|regex:/^[0-9]{1,45}$/|max:13',
            'gender' => 'required|max:1',
            'password' => 'required|string|min:6|confirmed'

        ]);

        $new_user = \App\User::create(['password' => \Hash::make($request->password),
                           'firstname' => $request->firstname, 'surname' => $request->surname,
                           'email' => $request->email, 'country' => $request->country,
                           'city' => $request->city, 'province' => $request->province,
                           'tel' => $request->phone_number, 'id_number' => $request->id_number,
                           'last_login' => time(), 'gender' => $request->gender, 'verifyToken' => bin2hex(random_bytes(13))]);
        if($new_user)
        {
            $add_verification = \App\VerifiedUser::create(['user_id' => $new_user->id, 'email' => $new_user->email, 'verified' => 0]);

            $credentials = $request->only('email', 'password');
            if (Auth::attempt($credentials)) {
                // Authentication passed...
                $data = ['email' => $request->email, 'name' => $request->firstname];
                // Send Mail
                $beautymail = app()->make(\Snowfire\Beautymail\Beautymail::class);
                $beautymail->send('emails.welcome', $data, function($message) use($data)
                {
                    $message
                        ->from('noreply@paperclipsa.co.za')
                        ->to($data['email'], $data['name'])
                        ->subject('Paperclip SA Registration');
                });

                \Session::flash('success', 'Registration complete.');
                return redirect()->to('/user-profile/');

            }
        } else {
            \Session::flash('error', 'Error');
            return redirect()->back();

        }
        

    }
}
