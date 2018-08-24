<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class DashboardController extends Controller
{
    // Methods

    // Views

    // User Dashboard
    /*
        Users Must be able to:
        
        - change their details, names, passwords, profile  images etc.
        - manage their credits/ subscription
        - view their purchased access videos
        - Redeem voucher codes
     */

    public function user_dashboard()
    {
        $user = \Auth::user();
        $watch_again = false;
        $account_balance = false;
        $subscribed_user = false;
        $user_streams = false;
        $email_verified = false;
        $notification_teams = false;

        $is_notified_user = \App\NotifiedUser::where('user_id', $user->id)->first();
        $teams = \App\Team::where('active_status', 'active')->get();
        $account_verified = \App\VerifiedUser::where('user_id', $user->id)->first();

        if($is_notified_user)
        {
            $teams_array = json_decode($is_notified_user->notifications, true)["teams"];
            $notification_teams = \App\Team::whereIn('id', $teams_array)->get();
        }

        if($account_verified)
        {
            if($account_verified->verified)
            {
                $email_verified = true;
            }
        }

        $is_referee = \App\Referee::where('user_id', $user->id)->first();
        $is_admin = \App\Admin::where('user_id', $user->id)->first();
        $is_superuser = \App\SuperUser::where('user_id', $user->id)->first();
        $is_coach = \App\Coach::where('user_id', $user->id)->first();

        if(\App\UserStreams::where('user_id', $user->id)->first())
        {
            if(\App\UserStreams::where('user_id', $user->id)->first()->stream_ids)
            {
                $user_streams = explode(",", \App\UserStreams::where('user_id', $user->id)->first()->stream_ids);
                $watch_again = \App\Stream::whereIn('id', $user_streams)->orderBy('created_at')->take(15)->get();
            }
        }

        if(\App\AccountBalance::where('user_id', \Auth::user()->id)->first())
        {
            $account_balance = \App\AccountBalance::where('user_id', \Auth::user()->id)->first();
        }

        if(\App\SubscribedUser::where('user_id', \Auth::user()->id)->first())
        {
            $subscribed_user = true;
        }
        return view('users.dashboard', ['account_balance' => $account_balance, 'subscribed_user' => $subscribed_user,
                                        'watch_again' => $watch_again, 'is_superuser' => $is_superuser, 'is_referee' => $is_referee,
                                        'is_admin' => $is_admin, 'is_coach' => $is_coach, 'email_verified' => $email_verified,
                                        'teams' => $teams, 'notification_teams' => $notification_teams]);
    }

    public function edit_user()
    {
        $user = \Auth::user();
        $is_referee = \App\Referee::where('user_id', $user->id)->first();
        $is_admin = \App\Admin::where('user_id', $user->id)->first();
        $is_superuser = \App\SuperUser::where('user_id', $user->id)->first();
        $is_coach = \App\Coach::where('user_id', $user->id)->first();

        return view('users.edit_details', ['user' => $user, 'is_superuser' => $is_superuser, 'is_referee' => $is_referee,
                                           'is_admin' => $is_admin, 'is_coach' => $is_coach]);
    }

    public function  update_user(Request $request)
    {
        $user = \Auth::user();

        $email_reg_pattern = "";

        $validatedData = $request->validate([
            'firstname' => 'required|max:255',
            'surname' => 'required|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id), 'max:255'],
            'country' => 'required|max:255',
            'city' => 'required|max:255',
            'province' => 'required|max:255',
            'phone_number' => 'required|regex:/^[0-9]{1,45}$/|max:10',
            'id_number' => 'required|regex:/^[0-9]{1,45}$/|max:13',
            'gender' => 'required|max:1',

        ]);

        if($request->email != $user->email)
        {
            $verified_user = \App\VerifiedUser::where('user_id', $user->id)->first();
            $user->update(['verifyToken' => bin2hex(random_bytes(13))]);

            if($verified_user)
            {
                $verified_user->update(['verified' => 0]);
            }
        }

        if($request->password_new)
        {
            $user->update(['password' => \Hash::make($request->password_new),
                           'firstname' => $request->firstname, 'surname' => $request->surname,
                           'email' => $request->email, 'country' => $request->country,
                           'city' => $request->city, 'province' => $request->province,
                           'tel' => $request->phone_number, 'id_number' => $request->id_number,
                           'gender' => $request->gender]);

            \Session::flash('success', 'Your profile has been updated and your password has been changed successfully');
            Auth::logout();
            return redirect()->to('/login');
        } else {
            $user->update(['firstname' => $request->firstname, 'surname' => $request->surname,
            'email' => $request->email, 'country' => $request->country,
            'city' => $request->city, 'province' => $request->province,
            'tel' => $request->phone_number, 'id_number' => $request->id_number,
            'gender' => $request->gender]);

            \Session::flash('success', 'Your profile has been updated.');
            return redirect()->back();
        }

    }

    public function change_image(Request $request)
    {
        $user = \Auth::user();
        $user_profile = \App\UserProfile::where('user_id', $user->id)->first();

        $path = $request->file('img_file')->store('/userprofile_imgs', 'public');
        $img_name = explode('/', $path)[1];

        if($user_profile)
        {
            if($user_profile->profile_image)
            {
                \Storage::disk('public')->delete('userprofile_imgs/'. $user_profile->profile_image);
                $user_profile->profile_image = $img_name;
                $user_profile->save();
            } else {
                $user_profile->profile_image = $img_name;
                $user_profile->save();
            }
            \Session::flash('success', 'Profile image has been updated.');    

        } else {
            $new_user_profile = \App\UserProfile::create(['user_id' => $user->id, 'profile_image' => $img_name]);
            \Session::flash('success', 'Profile image has been updated.');
        }

        return redirect()->back();
    }

    public function buy_credits_view()
    {
        $user = \Auth::user();
        $is_referee = \App\Referee::where('user_id', $user->id)->first();
        $is_admin = \App\Admin::where('user_id', $user->id)->first();
        $is_superuser = \App\SuperUser::where('user_id', $user->id)->first();
        $is_coach = \App\Coach::where('user_id', $user->id)->first();

        return view('users.buy_credits', ['is_superuser' => $is_superuser,'is_referee' => $is_referee, 'is_admin' => $is_admin, 'is_coach' => $is_coach]);
    }

    public function buy_credits(Request $request)
    {
        $user = \Auth::user();
        $validatedData = $request->validate([
            'credit_amount' => 'required|numeric|min:50'
        ]);

        $user_cart = \App\CreditsCart::where('user_id', $user->id)->first();

        if ($user_cart)
        {
            $user_cart->credits = $request->credit_amount;
            $user_cart->purchase_status = 'pending';
            $user_cart->save();

            return redirect()->to('/user-profile/buy-credit/confirm/'.$user->id .'/' .$user_cart->id);

        } else if($user_cart == null) {
            $new_cart = \App\CreditsCart::create(['user_id' => $user->id, 'credits' => $request->credit_amount]);

            return redirect()->to('/user-profile/buy-credit/confirm/'.$user->id .'/' .$new_cart->id);

        } else {
            \Session::flash('error', 'There was an error on server please contact Paperclip SA for assistance');

            return redirect()->back();
        }

    }

    public function buy_credits_confirm($user_id, $cart_id)
    {
        $user = \Auth::user();
        $is_referee = \App\Referee::where('user_id', $user->id)->first();
        $is_admin = \App\Admin::where('user_id', $user->id)->first();
        $is_superuser = \App\SuperUser::where('user_id', $user->id)->first();
        $is_coach = \App\Coach::where('user_id', $user->id)->first();

        $credit_cart = \App\CreditsCart::find($cart_id);
        
        if($user_id == $credit_cart->user_id)
        {
            return view('users.buy_credits_confirm', ['credit_cart' => $credit_cart, 'is_superuser' => $is_superuser, 'is_referee' => $is_referee, 'is_admin' => $is_admin,  'is_coach' => $is_coach]); 

        } else {
            return abort(404);
        }

    }

    public function redirect_to_payfast()
    {
        $user = \Auth::user();
        $unique_id = md5(uniqid(rand(), true));
        $credit_cart = \App\CreditsCart::where('user_id', $user->id)->first();
        $passPhrase = env('PAYFAST_PASSPHRASE');
        $pfOutput = "";
        $data = array(
            // Merchant details
            'merchant_id' => env('PAYFAST_MERCHANT_ID'),
            'merchant_key' => env('PAYFAST_MERCHANT_KEY'),
            'return_url' => 'http://www.paperclipsa.co.za/user-profile/buy-credit/done/'. $user->id . '/' . $credit_cart->id,
            'cancel_url' => 'http://www.paperclipsa.co.za/user-profile/buy-credit/cancel',
            'notify_url' => 'http://www.paperclipsa.co.za/user-profile/buy-credit-notify',
            // Buyer details
            'name_first' => $user->firstname,
            'name_last'  => $user->surname,
            'email_address'=> $user->email,
            'email_confirmation' => 1,
            'confirmation_address' => $user->email,
            // Transaction details
            'payment_method' => 'cc',
            'm_payment_id' => $unique_id, //Unique payment ID to pass through to notify_url
            // Amount needs to be in ZAR
            // If multicurrency system its conversion has to be done before building this array
            'amount' => number_format( sprintf( "%.2f", $credit_cart->credits ), 2, '.', '' ),
            'item_name' => 'Credits',
            'item_description' => 'PaperclipSA Purchase credits ',
            'custom_str1' => $user->id,
            'custom_int1' => $credit_cart->credits,
        );
        // Create GET string
        foreach( $data as $key => $val )
        {
            if(!empty($val))
            {
                $pfOutput .= $key .'='. urlencode( trim( $val ) ) .'&';
            }
        }

        // Remove last ampersand
        $getString = substr( $pfOutput, 0, -1 );

        $data['signature'] = md5( trim( $getString ) );

        if( isset( $passPhrase ) )
        {
            $getString .= '&passphrase='. urlencode( trim( $passPhrase ) );
        }   

        return redirect()->to('https://www.payfast.co.za/eng/process?'.$getString);
    
    }

    public function buy_credit_notify(Request $request)
    {
        $validHosts = array(
            'www.payfast.co.za',
            'sandbox.payfast.co.za',
            'w1w.payfast.co.za',
            'w2w.payfast.co.za',
        );
        
        $validIps = array();
        
        foreach( $validHosts as $pfHostname )
        {
            $ips = gethostbynamel( $pfHostname );
            if( $ips !== false )
            {
                $validIps = array_merge( $validIps, $ips );
            }
        }
        
        // Remove duplicates
        $validIps = array_unique( $validIps );
        
        if( !in_array( $_SERVER['REMOTE_ADDR'], $validIps ) )
        {
            die('Source IP not Valid');
        }

        if($request->payment_status == 'COMPLETE')
        {
            $credit_cart = \App\CreditsCart::where('user_id', $request->custom_str1)->first();
            $user_account = \App\AccountBalance::where('user_id', $request->custom_str1)->first();

            if($user_account)
            {
                $user_account->balance_value += $request->custom_int1;
                $credit_cart->purchase_status = 'successfull';
                $user_account->save();
                $credit_cart->save();
            } else {
                $new_user_account = \App\AccountBalance::create(['user_id' => $request->custom_str1, 'balance_value' => $request->custom_int1, 'balance_currency' => 'points']);
                $credit_cart->purchase_status = 'successfull';
                $credit_cart->save();
            }

        } else {
            die('payment unsuccessfull');
        }

        return response(200);
    }

    public function buy_credit_done($user_id, $cart_id)
    {
        $user = \Auth::user();
        $account_balance = \App\AccountBalance::where('user_id', $user->id)->first();
        $is_referee = \App\Referee::where('user_id', $user->id)->first();
        $is_admin = \App\Admin::where('user_id', $user->id)->first();
        $is_superuser = \App\SuperUser::where('user_id', $user->id)->first();
        $is_coach = \App\Coach::where('user_id', $user->id)->first();

        if($user_id == $user->id)
        {
            return view('users.buy_credits_done', ['account_balance' => $account_balance, 'is_superuser' => $is_superuser, 'is_referee' => $is_referee, 'is_admin' => $is_admin, 'is_coach' => $is_coach ]);
        } else {
            return abort(404);
        }

    }

    public function buy_credit_cancel()
    {
        $user = \Auth::user();
        $credit_cart = \App\CreditsCart::where('user_id', $user->id)->first();
        $is_referee = \App\Referee::where('user_id', $user->id)->first();
        $is_admin = \App\Admin::where('user_id', $user->id)->first();
        $is_superuser = \App\SuperUser::where('user_id', $user->id)->first();
        $is_coach = \App\Coach::where('user_id', $user->id)->first();

        if($credit_cart) {
            return view('users.buy_credits_cancel', ['cart_id' => $credit_cart->id, 'user_id' => $user->id,
                        'is_superuser' => $is_superuser, 'is_referee' => $is_referee, 'is_admin' => $is_admin]);
    
        } else {
            return abort(404);
        }
    }
    
    // Admin Dashboard

    /*
        Admins must be able to:
        - add referees for their venue
        - add teams for their venue
        - update their details, banner, links etc.
        - schedule matches
    */

    public function admin_dashboard()
    {
        return view();
    }

    public function update_venue(Request $request)
    {
        return redirect()->to('');
    }

    // Referee Dashboard
    /*
        Referees must be able to:
        - add teams for their venue
        - edit fixtures final score if score tracking is not enabled /and or remove score tracking

    */

    // Admins and Referee

    public function teams_view()
    {
        $user = \Auth::user();
        $is_referee = \App\Referee::where('user_id', $user->id)->first();
        $is_admin = \App\Admin::where('user_id', $user->id)->first();
        $is_superuser = \App\SuperUser::where('user_id', $user->id)->first();
        $is_coach = \App\Coach::where('user_id', $user->id)->first();

        $venue = false;
        $teams = false;
        
        if($is_referee)
        {
            $venue = \App\Venue::find($is_referee->venue_id);
        } else if($is_admin) {
            $venue = \App\Venue::find($is_admin->venue_id);
        }

        if($venue and $is_admin)
        {
            $teams = \App\Team::where('venue_id', $is_admin->venue_id)->orderBy('name')->paginate(15);
        } else if($venue and $is_referee) {
            $teams = \App\Team::where('venue_id', $is_referee->venue_id)->orderBy('name')->paginate(15);
        } else {
            return abort(404);
        }

        return view('admin_users.teams_view', ['is_superuser' => $is_superuser,'is_referee' => $is_referee, 'is_admin' => $is_admin, 'is_coach' => $is_coach,
                                               'teams' => $teams]);
    }

    public function add_team(Request $request)
    {
        $user = \Auth::user();
        $venue_id = null;
        $is_referee = \App\Referee::where('user_id', $user->id)->first();
        $is_admin = \App\Admin::where('user_id', $user->id)->first();
        $is_superuser = \App\SuperUser::where('user_id', $user->id)->first();
        $is_coach = \App\Coach::where('user_id', $user->id)->first();

        $validatedData = $request->validate([
            'name' => 'required|unique:teams|max:100'
        ]);

        if($is_admin)
        {
            $venue_id = $is_admin->venue_id;
        } else if($is_referee) {
            $venue_id = $is_referee->venue_id;
        }

        $new_team = \App\Team::create(['venue_id' => $venue_id, 'name' => $request->name, 'active_status' => 'active']);

        if($new_team)
        {
            \Session::flash('success', "'".$request->name."' has been added as a team.");
        } else {
            \Session::flash('error', "'".$request->name."' could not be added. An internal server error has occured.");
        }     
        return redirect()->back();
    }

    public function edit_team(Request $request)
    {
        $team = \App\Team::find($request->team_id);

        $validatedData = $request->validate([
            'team_name' => 'required|max:100|min:2'
        ]);

        $team->update(['name' => $request->team_name]);

        if($team)
        {
            \Session::flash('success', $request->team_name.' has successfully been updated.');
        } else {
            \Session::flash('error', $request->team_name.' could not be updated. An internal server error has occured');
        }

        return redirect()->back();
    }

    public function delete_team(Request $request)
    {
        $team = \App\Team::find($request->team_id);

        $team_deleted = \App\Team::find($request->team_id)->delete();

        if($team_deleted)
        {
            \Session::flash('success', 'The team '.$team->name.' has been deleted.');
        } else {
            \Session::flash('error', 'The selected team has been deleted');
        }
        
        return redirect()->back();
    }

    public function set_active_team(Request $request)
    {
        $team = \App\Team::find($request->team_id);

        if($team->active_status == 'suspended' or $team->active_status == 'banned')
        {
            $team->update(['active_status' => 'active']);
        } else {
            $team->update(['active_status' => 'suspended']);
        }

        return redirect()->back();
    }

    public function referees_view()
    {
        $user = \Auth::user();
        $admin_user = \App\Admin::where('user_id', $user->id)->first();
        $referee_ids = [];
        $referees = \App\Referee::where('venue_id', $admin_user->venue_id)->get();
        $is_referee = \App\Referee::where('user_id', $user->id)->first();
        $is_admin = \App\Admin::where('user_id', $user->id)->first();
        $is_coach = \App\Coach::where('user_id', $user->id)->first();
        $is_superuser = \App\SuperUser::where('user_id', $user->id)->first();
        $venue_id = $admin_user->venue_id;
        
        foreach($referees as $referee)
        {
            array_push($referee_ids, $referee->user_id);
        }

        $referee_users = \App\User::whereIn('id', $referee_ids)->orderBy('firstname')->paginate(10);

        return view('admin.referees_view', ['referee_users' => $referee_users, 'is_superuser' => $is_superuser,
                                            'is_referee' => $is_referee, 'is_admin' => $is_admin, 'is_coach' => $is_coach, 'venue_id' => $venue_id]);
    }

    public function referee_edit($referee_user_id, $referee_user_name)
    {
        $user = \Auth::user();
        $is_referee = \App\Referee::where('user_id', $user->id)->first();
        $is_admin = \App\Admin::where('user_id', $user->id)->first();
        $is_superuser = \App\SuperUser::where('user_id', $user->id)->first();
        $is_coach = \App\Coach::where('user_id', $user->id)->first();
        $referee_user = \App\User::where(['id' => $referee_user_id, 'firstname' => $referee_user_name])->first();
        $referee = \App\Referee::where('user_id', $referee_user_id)->first();

        if($referee_user)
        {
            return view('admin.referee_edit', ['is_superuser' => $is_superuser, 'referee_user' => $referee_user, 'referee' => $referee,
                                               'is_admin' => $is_admin, 'is_coach' => $is_coach, 'is_referee' => $is_referee]);

        } else {
            return abort(404);
        }
    }

    public function referee_save(Request $request)
    {
        $referee_user = \App\User::find($request->referee_user_id);
        $referee = \App\Referee::where('user_id', $request->referee_user_id)->first();

        $validatedData = $request->validate([
            'referee_user_id' => 'required|numeric',
            'firstname' => 'required|max:100',
            'surname' => 'required|max:100',
            'email' => ['required', 'email', Rule::unique('users')->ignore($referee_user->id), 'max:255'],
            'gender' => 'required'
        ]);

        if($request->password_new)
        {
            $referee_updated = $referee_user->update(['password' => \Hash::make($request->password_new), 'firstname' => $request->firstname,
                                   'surname' => $request->surname, 'email' => $request->email, 'gender' => $request->gender]);

            \Session::flash('success', 'Referee profile updated successfully');

        } else {
            $referee_updated = $referee_user->update(['firstname' => $request->firstname, 'surname' => $request->surname, 'email' => $request->email, 'gender' => $request->gender]);
            $referee_user = \App\User::find($request->referee_user_id);
            \Session::flash('success', 'Referee profile updated successfully');

        }

        return redirect()->to('/user-profile/admin/referees/edit/'. $referee_user->id .'/'.$referee_user->firstname);

    }

    public function referee_new(Request $request)
    {
        $validatedData = $request->validate([
            'firstname' => 'required|max:100',
            'surname' => 'required|max:100',
            'email' => 'required|email|unique:users|max:255',
            'gender' => 'required|string|max:1',
            'password' => 'required|min:6|confirmed'
        ]);

        $new_referee_user = \App\User::create(['firstname' => $request->firstname, 'surname' => $request->surname,
                                          'email' => $request->email, 'gender' => $request->gender, 'password' => \Hash::make($request->password), 'last_login' => time()]);

        $new_referee = \App\Referee::create(['user_id' => $new_referee_user->id, 'venue_id' => $request->venue_id, 'active_status' => 'active', 'last_login' => time()]);

        if($new_referee_user and $new_referee)
        {
            \Session::flash('success', 'Referee created successfully.');
        } else {
        
            \Session::flash('error', 'There was an internal server error. Please contact Paperclip SA for assistance.');
        }

        return redirect()->back();
    }

    public function delete_referee(Request $request)
    {
        $referee_user = \App\User::find($request->referee_user_id);
        $referee = \App\Referee::where('user_id', $request->referee_user_id)->first();

        $referee_user_delete = $referee_user->delete();
        $referee_delete = $referee->delete();

        if($referee_delete and $referee_user_delete)
        {
            \Session::flash('success', $referee_user->firstname .' ' . $referee_user->surname .' has been removed.');
        } else {
            \Session::flash('error', 'There was an internal server error. Please contact PaperclipSA.');
        }

        return redirect()->back();
    }

    public function set_active_referee(Request $request)
    {
        $referee = \App\Referee::where('user_id', $request->referee_id)->first();
        $referee_user = \App\User::find($referee->user_id);

        if($referee->active_status == 'suspended' or $referee->active_status == 'banned')
        {
            $referee->update(['active_status' => 'active']);
            $referee_user->update(['active_status' => 'active']);
        } else {
            $referee->update(['active_status' => 'suspended']);
            $referee_user->update(['active_status' => 'suspended']);
        }

        return redirect()->back();
    }

    // Super User Dashboard

    /* 
        Super Users must be able to:
        - add and update venues
        - update user credits
        - see current totals of users, subscribers, venues stats etc
        - create voucher keys and special events
        - delete or hide videos on demand.
        - edit, add, update referees and admins details
    */

    public function venues()
    {
        $user = \Auth::user();
        $is_referee = \App\Referee::where('user_id', $user->id)->first();
        $is_admin = \App\Admin::where('user_id', $user->id)->first();
        $is_superuser = \App\SuperUser::where('user_id', $user->id)->first();
        $is_coach = \App\Coach::where('user_id', $user->id)->first();

        $venues = \App\Venue::orderBy('name')->paginate(5);

        return view('superuser.venues', ['venues' => $venues, 'is_superuser' => $is_superuser, 'is_referee' => $is_referee, 'is_admin' => $is_admin, 'is_coach' => $is_coach ]);
    }

    public function venue_edit($venue_id, $venue_name)
    {
        $user = \Auth::user();
        $is_referee = \App\Referee::where('user_id', $user->id)->first();
        $is_admin = \App\Admin::where('user_id', $user->id)->first();
        $is_superuser = \App\SuperUser::where('user_id', $user->id)->first();
        $is_coach = \App\Coach::where('user_id', $user->id)->first();
        $venue = \App\Venue::where([['id','=', $venue_id], ['name', '=', $venue_name]])->first();

        if($venue)
        {
            return view('superuser.venue_edit', ['venue' => $venue, 'is_superuser' => $is_superuser, 'is_referee' => $is_referee, 'is_admin' => $is_admin, 'is_coach' => $is_coach ]);
        } else {
            return abort(404);
        }        
    }

    public function venue_new()
    {
        $user = \Auth::user();
        $is_referee = \App\Referee::where('user_id', $user->id)->first();
        $is_admin = \App\Admin::where('user_id', $user->id)->first();
        $is_superuser = \App\SuperUser::where('user_id', $user->id)->first();
        $is_coach = \App\Coach::where('user_id', $user->id)->first();

        return view('superuser.venue_new', ['is_superuser' => $is_superuser, 'is_referee' => $is_referee, 'is_admin' => $is_admin, 'is_coach' => $is_coach]);

    }

    public function venue_save(Request $request)
    {

        $path_banner = null;
        $path_logo = null;
        $phone_numbers = '';
        $port_names = '';
        $port_numbers = '';

        if($request->phone_number)
        {
            $phone_numbers = implode(",", $request->phone_number);
        }

        if($request->port_name)
        {
            $port_names .=  implode(",", $request->port_name);
        }

        if($request->port_number)
        {
            $port_numbers = implode(",", $request->port_number);
            
        }

        if($request->venue_id)
        {          
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'required|string|max:1500',
                'username' => 'required|string',
                'password' => 'required|string',
                'venue_ip' => 'required|string',
                'port_name' => 'required',
                'port_number' => 'required',
                'venue_type' => 'required|string',
                'wow_app_name' => 'required|string'
            ]);
            
            $venue = \App\Venue::find($request->venue_id);
            $venue_field = \App\Field::where('venue_id', $request->venue_id)->first();

            if($request->banner_img and $request->logo_img)
            {
                $path_banner = $request->file('banner_img')->store('/venues/banners', 'public');
                $path_logo = $request->file('logo_img')->store('/venues/logos', 'public');
                $banner_img_name = explode('/', $path_banner)[2];
                $logo_img_name = explode('/', $path_logo)[2];
    
                $venue->update([
                    'name' => $request->name,
                    'active_status' => 'active',
                    'wow_app_name' => $request->wow_app_name,
                    'username' => $request->username,
                    'password' => $request->password,
                    'description' => $request->description,
                    'phone' => $phone_numbers,
                    'venue_ip' => $request->venue_ip,
                    'logo_img' => $logo_img_name,
                    'banner_img' => $banner_img_name,
                    'intro_vid_url' => $request->intro_vid_url,
                    'twitter_url' => $request->twitter_url,
                    'fb_url' => $request->fb_url,
                    'venue_type' => $request->venue_type,
                ]);

                $venue_field->update([
                    'port_names' => $port_names,
                    'ports' => $port_numbers
                ]);
    
            } else if($request->banner_img) {
                $path_banner = $request->file('banner_img')->store('/venues/banners', 'public');
                $banner_img_name = explode('/', $path_banner)[2];
    
                $venue->update([
                    'name' => $request->name,
                    'active_status' => 'active',
                    'wow_app_name' => $request->wow_app_name,
                    'username' => $request->username,
                    'password' => $request->password,
                    'description' => $request->description,
                    'phone' => $phone_numbers, // comma separated string
                    'venue_ip' => $request->venue_ip,
                    'banner_img' => $banner_img_name,
                    'intro_vid_url' => $request->intro_vid_url,
                    'twitter_url' => $request->twitter_url,
                    'fb_url' => $request->fb_url,
                    'venue_type' => $request->venue_type
                ]);

                $venue_field->update([
                    'port_names' => $port_names,
                    'ports' => $port_numbers
                ]);
    
            } else if($request->logo_img) {
                $path_logo = $request->file('logo_img')->store('/venues/logos', 'public');
                $logo_img_name = explode('/', $path_logo)[2];
    
                $venue->update([
                    'name' => $request->name,
                    'active_status' => 'active',
                    'wow_app_name' => $request->wow_app_name,
                    'username' => $request->username,
                    'password' => $request->password,
                    'description' => $request->description,
                    'phone' => $phone_numbers, // comma separated string
                    'venue_ip' => $request->venue_ip,
                    'logo_img' => $logo_img_name,
                    'intro_vid_url' => $request->intro_vid_url,
                    'twitter_url' => $request->twitter_url,
                    'fb_url' => $request->fb_url,
                    'venue_type' => $request->venue_type
                ]);

                $venue_field->update([
                    'port_names' => $port_names,
                    'ports' => $port_numbers
                ]);
    
            } else {
                $venue->update([
                    'name' => $request->name,
                    'active_status' => 'active',
                    'wow_app_name' => $request->wow_app_name,
                    'username' => $request->username,
                    'password' => $request->password,
                    'description' => $request->description,
                    'phone' => $phone_numbers, // comma separated string
                    'venue_ip' => $request->venue_ip,
                    'intro_vid_url' => $request->intro_vid_url,
                    'twitter_url' => $request->twitter_url,
                    'fb_url' => $request->fb_url,
                    'venue_type' => $request->venue_type
                ]);

                $venue_field->update([
                    'port_names' => $port_names,
                    'ports' => $port_numbers
                ]);
            }
            \Session::flash('success', $venue->name . ' has been updated.');
        } else {
            // Incomplete add new venue 
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'required|string|max:1500',
                'username' => 'required|string',
                'password' => 'required|string',
                'venue_ip' => 'required|string',
                'banner_img' => 'required',
                'logo_img' => 'required',
                'port_name' => 'required',
                'port_number' => 'required',
                'venue_type' => 'required|string',
                'wow_app_name' => 'required|string'
            ]);

            $path_banner = $request->file('banner_img')->store('/venues/banners', 'public');
            $path_logo = $request->file('logo_img')->store('/venues/logos', 'public');
            $banner_img_name = explode('/', $path_banner)[2];
            $logo_img_name = explode('/', $path_logo)[2];

            $new_venue = \App\Venue::create([
                'name' => $request->name,
                'active_status' => 'active',
                'wow_app_name' => $request->wow_app_name,
                'username' => $request->username,
                'password' => $request->password,
                'description' => $request->description,
                'phone' => $phone_numbers, // comma separated string
                'venue_ip' => $request->venue_ip,
                'logo_img' => $logo_img_name,
                'banner_img' => $banner_img_name,
                'intro_vid_url' => $request->intro_vid_url,
                'twitter_url' => $request->twitter_url,
                'fb_url' => $request->fb_url,
                'venue_type' => $request->venue_type
            ]);

            $new_field = \App\Field::create([
                'venue_id' => $new_venue->id,
                'port_names' => $port_names,
                'ports' => $port_numbers,
            ]);

            \Session::flash('success', $new_venue->name . ' has been created successfully.');
        }

        return redirect()->to('/user-profile/superuser/venues');
    }

    public function set_active_venue(Request $request)
    {
        $venue = \App\Venue::find($request->venue_id);

        if($venue->active_status == 'suspended')
        {
            $venue->update(['active_status' => 'active']);
        } else {
            $venue->update(['active_status' => 'suspended']);
        }

        return redirect()->back();
    }

    public function venue_delete(Request $request)
    {
        $venue = \App\Venue::find($request->venue_id);

        $venue_deleted = $venue->delete();

        if($venue_deleted)
        {
            \Session::flash('success', $venue->name . ' has been deleted.');
        } else {
            \Session::flash('error', 'An internal server error has occured.' . $venue->name . ' has not been deleted. Please contact Paperlcip SA for assistance.');
        }

        return redirect()->back();
    }

    public function coaches()
    {
        $user = \Auth::user();
        $coaches_ids = [];
        $coaches = \App\Coach::all();
        $is_referee = \App\Referee::where('user_id', $user->id)->first();
        $is_admin = \App\Admin::where('user_id', $user->id)->first();
        $is_superuser = \App\SuperUser::where('user_id', $user->id)->first();
        $is_coach = \App\Coach::where('user_id', $user->id)->first();
        $soccer_schools = \App\Venue::where('venue_type', 'soccer_school')->get();

        foreach($coaches as $coach)
        {
            array_push($coaches_ids, $coach->user_id);
        }

        $coach_users = \App\User::whereIn('id', $coaches_ids)->orderBy('firstname')->paginate(10);

        return view('superuser.coaches', ['coach_users' => $coach_users, 'is_superuser' => $is_superuser,
        'is_referee' => $is_referee, 'is_admin' => $is_admin, 'is_coach' => $is_coach, 'soccer_schools' => $soccer_schools]);

    }

    public function coach_save(Request $request)
    {
        $validateedData = $request->validate([
            'firstname' => 'required|string',
            'surname' => 'required|string',
            'email' => 'required|unique:users|email',
            'gender' => 'required|string',
            'venue_id' => 'required',
            'password' => 'required|confirmed'
        ]);

        if($request->coach_id)
        {
            \Session::flash('success', 'Updated');

        } else {
            $new_coach_user = \App\User::create([
                'firstname' => $request->firstname,
                'surname' => $request->surname,
                'email' => $request->email,
                'gender' => $request->gender,
                'password' => \Hash::make($request->password),
                'last_login' => time()
            ]);

            if($new_coach_user)
            {
                $new_coach = \App\Coach::create([
                    'user_id' => $new_coach_user->id,
                    'active_status' => 'active',
                    'venue_id' => $request->venue_id,
                ]);
                \Session::flash('success', 'Coach '. $new_coach_user->firstname . ' created.');

            } else {
                \Session::flash('error', 'There was an internal server error. Please contact Paperclip');
            }
        }

        return redirect()->back();
    }

    public function set_active_coach(Request $request)
    {
        $coach = \App\Coach::where('user_id', $request->coach_id)->first();
        $coach_user = \App\User::find($coach->user_id);

        if($coach->active_status == 'suspended' or $coach->active_status == 'banned')
        {
            $coach->update(['active_status' => 'active']);
            $coach_user->update(['active_status' => 'active']);
        } else {
            $coach->update(['active_status' => 'suspended']);
            $coach_user->update(['active_status' => 'suspended']);
        }

        return redirect()->back();
    }

    // Coaches Dashboard

    public function age_groups_view()
    {
        $user = \Auth::user();
        $is_referee = \App\Referee::where('user_id', $user->id)->first();
        $is_admin = \App\Admin::where('user_id', $user->id)->first();
        $is_superuser = \App\SuperUser::where('user_id', $user->id)->first();
        $is_coach = \App\Coach::where('user_id', $user->id)->first();

        $venue = \App\Venue::find($is_coach->venue_id);

        $age_groups = \App\SoccerSchoolsAgeGroup::where('venue_id', $is_coach->venue_id)->orderBy('name')->paginate(15);

        return view('coach.age_groups', ['is_superuser' => $is_superuser,'is_referee' => $is_referee, 'is_admin' => $is_admin,
                                               'is_coach' => $is_coach, 'age_groups' => $age_groups]);
    }

    public function add_age_group(Request $request) 
    {
        $user = \Auth::user();
        $venue_id = null;
        $is_referee = \App\Referee::where('user_id', $user->id)->first();
        $is_admin = \App\Admin::where('user_id', $user->id)->first();
        $is_superuser = \App\SuperUser::where('user_id', $user->id)->first();
        $is_coach = \App\Coach::where('user_id', $user->id)->first();

        $validatedData = $request->validate([
            'name' => 'required|unique:soccer_schools_age_groups|string|regex:/(^[a-zA-Z0-9_\s\-]*$)/u|max:100|min:2'
        ]);

        $venue_id = $is_coach->venue_id;
        
        $new_age_group = \App\SoccerSchoolsAgeGroup::create(['venue_id' => $venue_id, 'name' => $request->name, 'active_status' => 'active']);

        if($new_age_group)
        {
            \Session::flash('success', "'".$request->name."' has been added as an age group.");
        } else {
            \Session::flash('error', "'".$request->name."' could not be added. An internal server error has occured.");
        }     

        return redirect()->back();
    }

    public function edit_age_group(Request $request)
    {
        $age_group = \App\SoccerSchoolsAgeGroup::find($request->age_group_id);

        $validatedData = $request->validate([
            'age_group_name' => 'required|max:100|string|regex:/(^[a-zA-Z0-9_\s\-]*$)/u|min:2'
        ]);

        $age_group->update(['name' => $request->age_group_name]);

        if($age_group)
        {
            \Session::flash('success', $request->age_group_name.' has successfully been updated.');
        } else {
            \Session::flash('error', $request->age_group_name.' could not be updated. An internal server error has occured');
        }

        return redirect()->back();
    }

    public function delete_age_group(Request $request)
    {
        $age_group = \App\SoccerSchoolsAgeGroup::find($request->age_group_id);

        $age_group_deleted = \App\SoccerSchoolsAgeGroup::find($request->age_group_id)->delete();

        if($age_group_deleted)
        {
            \Session::flash('success', 'The team '.$age_group->name.' has been deleted.');
        } else {
            \Session::flash('error', 'The selected team has been deleted');
        }
        
        return redirect()->back();
    }

    public function set_active_age_group(Request $request)
    {
        $age_group = \App\SoccerSchoolsAgeGroup::find($request->age_group_id);

        if($age_group->active_status == 'suspended' or $age_group->active_status == 'banned')
        {
            $age_group->update(['active_status' => 'active']);
        } else {
            $age_group->update(['active_status' => 'suspended']);
        }

        return redirect()->back();
    }

}
