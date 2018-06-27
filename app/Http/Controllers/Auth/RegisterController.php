<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

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
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'firstname' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'date_of_birth' => 'required|string|max:10',
            'gender' => 'required|string|max:1',
            'country' => 'required|string|max:255',
            'password' => 'required|string|min:6|confirmed',
            'date_of_birth' => 'required|string',
            'tel' => 'required|string|min:10|max:10',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'province' => 'required|string|max:255',
            'id_number' => 'required|string|min:13|max:13'

            
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        return User::create([
            'firstname' => $data['firstname'],
            'surname' => $data['surname'],
            'email' => $data['email'],
            'date_of_birth' => $data['date_of_birth'], //new column
            'gender' => $data['gender'], //new
            'country' => $data['country'], //new column
            'password' => Hash::make($data['password']),
            'tel' => $data['tel'],
            'address' => $data['address'],
            'city' => $data['city'],
            'province' => $data['province'],
            'id_number' => $data['id_number'],
            'last_login' => strtotime("now"),
            'onboarding' => 'b',
            'status' => 'y'
        ]);
    }
}
