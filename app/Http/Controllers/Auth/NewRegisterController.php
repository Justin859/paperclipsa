<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\verifyEmail;

class RegisterController extends Controller 
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => ['sendEmail', 'sendEmailDone']]);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {

        $messages =  [
            'fname.required' => 'Enter your firstname.',
            'sname.required'  => 'Enter your surname.',
            'email.required'  => 'Enter your email',
            'email.string'  => 'Email Invalid',
            'email.email'  => 'Email Invalid',
            'email.unique'  => 'Email is already taken by another user',
            'cemail.required'  => 'Confirm your email',
            'cemail.same'  => 'Your email and confirmation email are not the same',
            'password.required' => "Enter your password (Minimum of 6 characters, should contain at least a number)",
            'password.regex' => "Your password needs to be better (Minimum of 6 characters, should contain at least a number)",
            'password.confirmed' => "Your password is not same with your confirmation password",
        ];


        return Validator::make($data, [ 
            'fname' => 'required|string|max:255',
            'sname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'cemail' => 'required|string|max:40|same:email',
            //'password' => 'required|regex:/^(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z])([a-zA-Z0-9]{6,20})$/i', 
            'password' => 'required|regex:/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])[0-9a-zA-Z!@#$%^&*?]{6,20}$/i|confirmed', 
            //'password_confirmation' => 'required|min:6|same:password',
        ], $messages);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
       /* return User::create([
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'verifyToken' => Str::random(40),
        ]);*/

        // bcrypt($data['password'])

        $last_login = time();
        $token = $data['_token']; 

       $user = User::create([
            'firstname' => $data['fname'],
            'surname' => $data['sname'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'verifyToken' => Str::random(40),
            'last_login' =>  $last_login,
            'remember_token' => $token,
            'onboarding' => 'd',
        ]);
        
        $thisUser = User::findorFail($user->id);
        //$this->sendEmail($thisUser);

        return $user;
    }


    // function to send email
    public function sendEmail($thisUser)
    {
        Mail::to($thisUser['email'])->send(new verifyEmail($thisUser)); // verifyEmail is a custom Mailable object
    }

    // 
    public function sendEmailDone($email, $verifyToken, Request $req)
    {
        $user = User::where(['email' => $email, 'verifyToken' => $verifyToken])->first();

        // return $user;

        if($user)
        {
            User::where(['email' => $email, 'verifyToken' => $verifyToken])->update(['status' => 'y', 'verifyToken' => NULL]);
            $req->session()->put('status_verified', 'true');
            //return 'user is verified!';
            return redirect('/') -> with('pub_msg', 'Congratulations. Your email has been verified!');
        }
        else
        {
            //return 'User not found!';

            $req->session()->put('status_verified', 'false');
           // return redirect('/', ['msg' => 'User cannot be verified. Please try again later!']);
           //return 'User cannot be verified. Please try again later!';
           return redirect()->route('welcome')->with('pub_msg', 'The email cannot be verified. Please try again later!');
        }
    }



    // AJAX
    public function checkEmail()
    {
        $newVal = 'f';

        $input = request()->all();

        /*if (User::where(['email' => $req->input('email')])->exists()) {
            // user found
            $newVal = 't';
         }
        

        $u_email_status = User::where('email', Input::get('email'))->first();

        if (is_null($u_email_status)) {

        //print_r("email is exists");
        $newVal = 't';
        } */        

        if (User::where('email', $input['email'])->exists()) {
            $newVal = 't';
        }



        $res = array(
            'status' => 'success',
            'result1' => $newVal,
            'result2' => $input['email']
        );

        return response()->json($res);
    }

    public function checkUsername()
    {
        $newVal = 'f';
        $input = request()->all();
        
        if (User::where('username', $input['username'])->exists()) {
            $newVal = 't';
        }

        $res = array(
            'status' => 'success',
            'result1' => $newVal,
        );

        return response()->json($res);
    }
}
