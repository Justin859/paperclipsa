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

        return view('users.edit_details', ['user' => $user]);
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
        return view('users.buy_credits');
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

        $credit_cart = \App\CreditsCart::find($cart_id);
        
        if($user_id == $credit_cart->user_id)
        {
            return view('users.buy_credits_confirm', ['credit_cart' => $credit_cart]); 

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

        if($user_id == $user->id)
        {
            return view('users.buy_credits_done', ['account_balance' => $account_balance ]);
        } else {
            return abort(404);
        }

    }

    public function buy_credit_cancel()
    {
        $user = \Auth::user();
        $credit_cart = \App\CreditsCart::where('user_id', $user->id)->first();

        if($credit_cart) {
            return view('users.buy_credits_cancel', ['cart_id' => $credit_cart->id, 'user_id' => $user->id]);
    
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

        return view('admin_users.teams_view', ['teams' => $teams]);
    }

    public function add_team(Request $request)
    {
        $user = \Auth::user();
        $venue_id = null;
        $is_referee = \App\Referee::where('user_id', $user->id)->first();
        $is_admin = \App\Admin::where('user_id', $user->id)->first();

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

    public function notification_view()
    {
        $user = \Auth::user();
        $is_referee = \App\Referee::where('user_id', $user->id)->first();
        $is_admin = \App\Admin::where('user_id', $user->id)->first();
        $venue = null;
        $admin_notifications = null;

        if($is_admin)
        {
            $venue = \App\Venue::find($is_admin->venue_id);
        } else if($is_referee) {
            $venue = \App\Venue::find($is_referee->venue_id);
        }
        if($venue)
        {
            $admin_notifications = \App\TeamAdminRequest::where(['venue_id' => $venue->id, 'status' => 'pending'])->orderBy('created_at', 'DSC')->get();
        }

        return view('admin_users.notifications', ['admin_notifications' => $admin_notifications]);
    }

    public function notification_request(Request $request)
    {
        $user = \Auth::user();
        $notification = \App\TeamAdminRequest::find($request->notification_id);
        $notification_user = \App\User::find($notification->user_id);

        if($request->response == 'accept')
        {
            $notification->update(['status' => 'complete']);
            $new_club_admin = \App\TeamAdmin::create(['user_id' => $notification->user_id, 'team_id' => $notification->team_id, 'checked_by' => $user->id, 'active_status' => 'active']);
            $new_player = \App\TeamPlayer::create(['user_id' => $notification->user_id, 'team_id' => $notification->team_id, 'checked_by' => $user->id, 'active_status' => 'active']);

            \Session::flash("success", "You have accepted " . $notification_user->firstname . "'s request.");

        } else if($request->response == 'decline') {
            $notification->update(['status' => 'dismissed']);
            \Session::flash("warning", "You have declined " . $notification_user->firstname . "'s request.");
        }

        return redirect()->back();
    }

    // End Admin And Referees

    // Venue Admins

    public function referees_view()
    {
        $user = \Auth::user();
        $admin_user = \App\Admin::where('user_id', $user->id)->first();
        $referee_ids = [];
        $referees = \App\Referee::where('venue_id', $admin_user->venue_id)->get();
        $venue_id = $admin_user->venue_id;
        
        foreach($referees as $referee)
        {
            array_push($referee_ids, $referee->user_id);
        }

        $referee_users = \App\User::whereIn('id', $referee_ids)->orderBy('firstname')->paginate(10);

        return view('admin.referees_view', ['venue_id' => $venue_id, 'referee_users' => $referee_users]);
    }

    public function referee_edit($referee_user_id, $referee_user_name)
    {
        $user = \Auth::user();
        $referee_user = \App\User::where(['id' => $referee_user_id, 'firstname' => $referee_user_name])->first();
        $referee = \App\Referee::where('user_id', $referee_user_id)->first();

        if($referee_user)
        {
            return view('admin.referee_edit', ['referee_user' => $referee_user, 'referee' => $referee]);

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

    // Selling credits

    public function add_credits()
    {

        return view('admin.sell_credits');
    }

    public function add_credits_request(Request $request)
    {
        $validatedData = $request->validate([
            'email' => 'required|email|exists:users',
            'credits' => 'required|numeric|max:999999|min:5'
        ]);

        $admin_user_account = \Auth::user();
        $admin_account = \App\Admin::where('user_id', $admin_user_account->id)->first();
        $user_is_superuser = \App\SuperUser::where('user_id', $user->id)->first();

        $user_account = \App\User::where('email', $request->email)->first();
        $user_has_balance = \App\AccountBalance::where('user_id', $user_account->id)->first();

        if($user_has_balance)
        {
            $user_has_balance->balance_value = (string)((int)$user_has_balance->balance_value + $request->credits);
            $user_has_balance->save();

        } else {
            $user_new_account = \App\AccountBalance::create(['user_id' => $user_account->id, 'balance_value' => $request->credits, 'balance_currency' => 'points']);
        }

        // create credit invoice to venue 

        if(!$user_is_superuser)
        {
            $new_invoice = \App\VenueCreditInvoice::create(['venue_id' => $admin_account->venue_id, 'user_id' => $admin_user_account->id, 'user_invoiced' => $user_account->id,
            'amount_given' => $request->credits, 'date_time' => date("Y-m-d H:i:s")]);
        }

        \Session::flash('success', $request->credits . ' credits have been added to ' . $user_account->email . ' account.'); 

        return redirect()->back();
    }

    public function balance_statistics()
    {
        $user = \Auth::user();
        $admin_user = \App\Admin::where('user_id', $user->id)->first();

        $credit_invoices_owed = \App\VenueCreditInvoice::where(['venue_id' => $admin_user->venue_id, 'status' => 'owed'])->get()->sum('amount_given');
        $credit_invoices_paid = \App\VenueCreditInvoice::where(['venue_id' => $admin_user->venue_id, 'status' => 'paid'])->get()->sum('amount_given');

        $statistics_jan = 0; $statistics_feb = 0; $statistics_mar = 0; $statistics_apr = 0;
        $statistics_may = 0; $statistics_jun = 0; $statistics_jul = 0; $statistics_aug = 0;
        $statistics_sep = 0; $statistics_oct = 0; $statistics_nov = 0; $statistics_dec = 0;

        $purchased_streams = \App\IndoorSoccerPurchase::where(['venue_id' => $admin_user->venue_id])->whereYear('created_at', '=', date('Y'))->get(); // Sort by month first..
        $purchased_streams_value = \App\IndoorSoccerPurchase::where(['venue_id' => $admin_user->venue_id])->whereYear('created_at', '=', date('Y'))->get()->sum('amount_paid'); // Sort by month first..

        foreach($purchased_streams as $stream)
        {
            switch (date("m", strtotime($stream->created_at))) {
                case  "01":
                    $statistics_jan += $stream->amount_paid;
                    break;
                case "02":
                    $statistics_feb += $stream->amount_paid;
                    break;
                case "03":
                    $statistics_mar += $stream->amount_paid;
                    break;
                case "04":
                    $statistics_apr += $stream->amount_paid;
                    break;
                case "05":
                    $statistics_may += $stream->amount_paid;
                    break;
                case "06":
                    $statistics_jun += $stream->amount_paid;
                    break;
                case "07":
                    $statistics_jul += $stream->amount_paid;
                    break;
                case "08":
                    $statistics_aug += $stream->amount_paid;
                    break;
                case "09":
                    $statistics_sep += $stream->amount_paid;
                    break;
                case "10":
                    $statistics_oct += $stream->amount_paid;
                    break;
                case "11":
                    $statistics_nov += $stream->amount_paid;
                    break;
                case "12":
                    $statistics_dec += $stream->amount_paid;
                    break;
                default:
                    $statistics_jan += $stream->amount_paid;
            }
        }

        return view('admin.balance_statistics', ['credit_invoices_owed' => $credit_invoices_owed, 'credit_invoices_paid' => $credit_invoices_paid,
                                                 'purchased_streams' => $purchased_streams, 'purchased_streams_value' => $purchased_streams_value,
                                                 'statistics_jan' => $statistics_jan, 'statistics_feb' => $statistics_feb, 'statistics_mar' => $statistics_mar, 'statistics_apr' => $statistics_apr,
                                                 'statistics_may' => $statistics_may, 'statistics_jun' => $statistics_jun, 'statistics_jul' => $statistics_jul, 'statistics_aug' => $statistics_aug,
                                                 'statistics_sep' => $statistics_sep, 'statistics_oct' => $statistics_oct, 'statistics_nov' => $statistics_nov, 'statistics_dec' => $statistics_dec, 
                                                 ]);

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

        $venues = \App\Venue::orderBy('name')->paginate(5);

        return view('superuser.venues', ['venues' => $venues]);
    }

    public function venue_edit($venue_id, $venue_name)
    {
        $user = \Auth::user();
        
        $venue = \App\Venue::where([['id','=', $venue_id], ['name', '=', $venue_name]])->first();

        if($venue)
        {
            return view('superuser.venue_edit', ['venue' => $venue]);
        } else {
            return abort(404);
        }        
    }

    public function venue_new()
    {
        return view('superuser.venue_new');

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
        $coaches_ids = [];
        $coaches = \App\Coach::all();
        $soccer_schools = \App\Venue::where('venue_type', 'soccer_school')->get();

        foreach($coaches as $coach)
        {
            array_push($coaches_ids, $coach->user_id);
        }

        $coach_users = \App\User::whereIn('id', $coaches_ids)->orderBy('firstname')->paginate(10);

        return view('superuser.coaches', ['soccer_schools' => $soccer_schools, 'coach_users' => $coach_users]);

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

        if($request->coach_user_id)
        {
            if($request->password)
            {
                $validatedData = $request->validate([
                    'firstname' => 'required|string',
                    'surname' => 'required|string',
                    'email' => ['required', 'email', Rule::unique('users')->ignore($user->id), 'max:255'],
                    'gender' => 'required|string',
                    'venue_id' => 'required',
                    'password' => 'required|confirmed'
                ]);
                $updated_coach = \App\User::where('id', $user->id)->first()->update(['firstname' => $request->firstname, 'surname' => $request->surname, 'gender' => $request->gender, 'email' => $request->email, 'password' => \Hash::make($request->password)]);
            } else {
                $user = \App\User::find($request->coach_user_id);
                $validatedData = $request->validate([
                    'firstname' => 'required|string',
                    'surname' => 'required|string',
                    'email' => ['required', 'email', Rule::unique('users')->ignore($user->id), 'max:255'],
                    'gender' => 'required|string',
                ]);
                
                $updated_coach = \App\User::where('id', $user->id)->first()->update(['firstname' => $request->firstname, 'surname' => $request->surname, 'gender' => $request->gender, 'email' => $request->email]);
            }
            

            \Session::flash('success', 'Coach '. $user->firstname .' has been updated');

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
                \Session::flash('error', 'There was an internal server error. Please contact Paperclip SA');
            }
        }

        return redirect()->back();
    }

    public function coach_delete(Request $request)
    {
        $coach_user = \App\User::find($request->coach_user_id);
        $coach = \App\Coach::where('user_id', $request->coach_user_id)->first();

        $coach_user_delete = $coach_user->delete();
        $coach_delete = $coach->delete();

        if($coach_delete and $coach_user_delete)
        {
            \Session::flash('success', $coach_user->firstname .' ' . $coach_user->surname .' has been removed.');
        } else {
            \Session::flash('error', 'There was an internal server error. Please contact Paperclip SA.');
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

    public function admins()
    {
        $user = \Auth::user();
        $admin_ids = [];
        $admin = \App\Admin::all();
        $indoor_soccer_venues = \App\Venue::where('venue_type', 'indoor_soccer')->get();

        foreach($admin as $admin)
        {
            array_push($admin_ids, $admin->user_id);
        }

        $admin_users = \App\User::whereIn('id', $admin_ids)->orderBy('firstname')->paginate(10);

        return view('superuser.admins', ['indoor_soccer_venues' => $indoor_soccer_venues, 'admin_users' => $admin_users]);
    }

    public function admin_edit($admin_user_id, $admin_user_name)
    {
        
        $admin_user = \App\User::where(['id' => $admin_user_id, 'firstname' => $admin_user_name])->first();
        $admin = \App\Admin::where('user_id', $admin_user_id)->first();

        if($admin_user)
        {
            return view('superuser.admin_edit', ['admin_user' => $admin_user, 'admin' => $admin]);

        } else {
            return abort(404);
        }
    }

    public function admin_save(Request $request)
    {

        if($request->admin_user_id)
        {
            $user = \App\User::find($request->admin_user_id);

            if($request->password)
            {
                $validatedData = $request->validate([
                    'firstname' => 'required|string',
                    'surname' => 'required|string',
                    'email' => ['required', 'email', Rule::unique('users')->ignore($user->id), 'max:255'],
                    'gender' => 'required|string',
                    'venue_id' => 'required',
                    'password' => 'required|confirmed'
                ]);
                $updated_admin = \App\User::where('id', $user->id)->first()->update(['firstname' => $request->firstname, 'surname' => $request->surname, 'gender' => $request->gender, 'email' => $request->email, 'password' => \Hash::make($request->password)]);
            } else {
                $user = \App\User::find($request->admin_user_id);
                $validatedData = $request->validate([
                    'firstname' => 'required|string',
                    'surname' => 'required|string',
                    'email' => ['required', 'email', Rule::unique('users')->ignore($user->id), 'max:255'],
                    'gender' => 'required|string',
                ]);
                
                $updated_admin = \App\User::where('id', $user->id)->first()->update(['firstname' => $request->firstname, 'surname' => $request->surname, 'gender' => $request->gender, 'email' => $request->email]);
            }
            

            \Session::flash('success', 'Admin '. $user->firstname .' has been updated');

        } else {
            $new_admin_user = \App\User::create([
                'firstname' => $request->firstname,
                'surname' => $request->surname,
                'email' => $request->email,
                'gender' => $request->gender,
                'password' => \Hash::make($request->password),
                'last_login' => time()
            ]);

            if($new_admin_user)
            {
                $new_admin = \App\Admin::create([
                    'user_id' => $new_admin_user->id,
                    'active_status' => 'active',
                    'venue_id' => $request->venue_id,
                    'last_login' => time()
                ]);
                \Session::flash('success', 'Admin '. $new_admin_user->firstname . ' created.');

            } else {
                \Session::flash('error', 'There was an internal server error. Please contact Paperclip SA');
            }
        }

        return redirect()->to('/user-profile/superuser/admins');
    }

    public function admin_delete(Request $request)
    {
        $admin_user = \App\User::find($request->admin_user_id);
        $admin = \App\Admin::where('user_id', $request->admin_user_id)->first();

        $admin_user_delete = $admin_user->delete();
        $admin_delete = $admin->delete();

        if($admin_delete and $admin_user_delete)
        {
            \Session::flash('success', $admin_user->firstname .' ' . $admin_user->surname .' has been removed.');
        } else {
            \Session::flash('error', 'There was an internal server error. Please contact Paperclip SA.');
        }

        return redirect()->back();
    }

    public function set_active_admin(Request $request)
    {
        $admin = \App\admin::where('user_id', $request->admin_id)->first();
        $admin_user = \App\User::find($admin->user_id);

        if($admin->active_status == 'suspended' or $admin->active_status == 'banned')
        {
            $admin->update(['active_status' => 'active']);
            $admin_user->update(['active_status' => 'active']);
        } else {
            $admin->update(['active_status' => 'suspended']);
            $admin_user->update(['active_status' => 'suspended']);
        }

        return redirect()->back();
    }

    public function venue_anylitics($venue_id, $venue_name)
    {
        $venue = \App\Venue::find($venue_id);

        $statistics_jan = 0; $statistics_feb = 0; $statistics_mar = 0; $statistics_apr = 0;
        $statistics_may = 0; $statistics_jun = 0; $statistics_jul = 0; $statistics_aug = 0;
        $statistics_sep = 0; $statistics_oct = 0; $statistics_nov = 0; $statistics_dec = 0;

        $invoiced_jan = 0; $invoiced_feb = 0; $invoiced_mar = 0; $invoiced_apr = 0;
        $invoiced_may = 0; $invoiced_jun = 0; $invoiced_jul = 0; $invoiced_aug = 0;
        $invoiced_sep = 0; $invoiced_oct = 0; $invoiced_nov = 0; $invoiced_dec = 0;

        $purchased_streams = \App\IndoorSoccerPurchase::where(['venue_id' => $venue_id])->whereYear('created_at', '=', date('Y'))->get(); // Sort by month first..
        $venue_invoiced = \App\VenueCreditInvoice::where(['venue_id' => $venue_id, 'status' => 'owed'])->whereYear('created_at', '=', date('Y'))->get(); // Sort by month first..

        // Get total paid videos per month.

        foreach($purchased_streams as $stream)
        {
            switch (date("m", strtotime($stream->created_at))) {
                case  "01":
                    $statistics_jan += $stream->amount_paid;
                    break;
                case "02":
                    $statistics_feb += $stream->amount_paid;
                    break;
                case "03":
                    $statistics_mar += $stream->amount_paid;
                    break;
                case "04":
                    $statistics_apr += $stream->amount_paid;
                    break;
                case "05":
                    $statistics_may += $stream->amount_paid;
                    break;
                case "06":
                    $statistics_jun += $stream->amount_paid;
                    break;
                case "07":
                    $statistics_jul += $stream->amount_paid;
                    break;
                case "08":
                    $statistics_aug += $stream->amount_paid;
                    break;
                case "09":
                    $statistics_sep += $stream->amount_paid;
                    break;
                case "10":
                    $statistics_oct += $stream->amount_paid;
                    break;
                case "11":
                    $statistics_nov += $stream->amount_paid;
                    break;
                case "12":
                    $statistics_dec += $stream->amount_paid;
                    break;
                default:
                    $statistics_jan += $stream->amount_paid;
            }
        }

        // Get total invoiced credits per month.

        foreach($venue_invoiced as $invoice)
        {
            switch (date("m", strtotime($invoice->created_at))) {
                case "01":
                    $invoiced_jan += $invoice->amount_given;
                    break;
                case "02":
                    $invoiced_feb += $invoice->amount_given;
                    break;
                case "03":
                    $invoiced_mar += $invoice->amount_given;
                    break;
                case "04":
                    $invoiced_apr += $invoice->amount_given;
                    break;
                case "05":
                    $invoiced_may += $invoice->amount_given;
                    break;
                case "06":
                    $invoiced_jun += $invoice->amount_given;
                    break;
                case "07":
                    $invoiced_jul += $invoice->amount_given;
                    break;
                case "08":
                    $invoiced_aug += $invoice->amount_given;
                    break;
                case "09":
                    $invoiced_sep += $invoice->amount_given;
                    break;
                case "10":
                    $invoiced_oct += $invoice->amount_given;
                    break;
                case "11":
                    $invoiced_nov += $invoice->amount_given;
                    break;
                case "12":
                    $invoiced_dec += $invoice->amount_given;
                    break;
                default:
                    break;
            }
        }

        return view('superuser.venue_analytics', ['venue' => $venue, 
                                                 'statistics_jan' => $statistics_jan, 'statistics_feb' => $statistics_feb, 'statistics_mar' => $statistics_mar, 'statistics_apr' => $statistics_apr,
                                                 'statistics_may' => $statistics_may, 'statistics_jun' => $statistics_jun, 'statistics_jul' => $statistics_jul, 'statistics_aug' => $statistics_aug,
                                                 'statistics_sep' => $statistics_sep, 'statistics_oct' => $statistics_oct, 'statistics_nov' => $statistics_nov, 'statistics_dec' => $statistics_dec,
                                                 'invoiced_jan' => $invoiced_jan, 'invoiced_feb' => $invoiced_feb, 'invoiced_mar' => $invoiced_mar, 'invoiced_apr' => $invoiced_apr,
                                                 'invoiced_may' => $invoiced_may, 'invoiced_jun' => $invoiced_jun, 'invoiced_jul' => $invoiced_jul, 'invoiced_aug' => $invoiced_aug,
                                                 'invoiced_sep' => $invoiced_sep, 'invoiced_oct' => $invoiced_oct, 'invoiced_nov' => $invoiced_nov, 'invoiced_dec' => $invoiced_dec,
                                                ]);
    }

    public function find_stream()
    {

        return view('superuser.find_stream');
    }

    public function delete_stream(Request $request)
    {
        $validatedData = $request->validate([
            'stream_name' => 'required',
            'action' => 'required'
        ]);

        $stream = \App\Stream::where('name', $request->stream_name)->first();
        $fixture = \App\Fixture::where('stream_id', $stream->id)->first();

        if($stream and $fixture)
        {
            if($request->action == 'stream_only')
            {
                $stream_deleted = $stream->delete();
                $fixture_deleted = $fixture->delete();

                if($stream_deleted and $fixture_deleted)
                {
                    \Session::flash('success', 'Stream and Fixture records in the database have been removed.');
                } else {
                    \Session::flash('error', 'An error has occured Stream and Fixture records could not be removed from the database.');
                }
    
            } else if($request->action == 'stream_and_file') {

                $ch = curl_init();
                $data = array("stream_name" => $stream->name, "storage_location" => $stream->storage_location);                                                                    
                $data_string = json_encode($data); 
                curl_setopt($ch, CURLOPT_URL,"http://127.0.0.1:5002/remove-stream");
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);                                                                  
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  
                curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
                    'Content-Type: application/json',                                                                                
                    'Content-Length: ' . strlen($data_string))                                                                       
                );                                  
                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT ,3);
                curl_setopt($ch, CURLOPT_TIMEOUT, 20);

                $server_output = curl_exec($ch);
                if(curl_getinfo($ch, CURLINFO_HTTP_CODE) == '200')
                {
                    $stream_deleted = $stream->delete();
                    $fixture_deleted = $fixture->delete();

                    if($stream_deleted and $fixture_deleted)
                    {
                        \Session::flash('success', 'Stream and Fixture records in the database have been removed.');
                    } else {
                        \Session::flash('error', 'An error has occured Stream and Fixture records could not be removed from the database.');
                    }

                    \Session::flash('success', 'The video file has been removed from the server.');

                } else {
                    \Session::flash('error', 'There was an error removing the video file. Either the file does not exist anymore or it is curently streaming.');
                }
                
            } else {
                
                \Session::flash('error', 'An error has occured. Stream Could not be deleted');
            }          

                curl_close($ch);

                  
        } else {
            \Session::flash('error', 'The stream that you are looking for could not be found in the database.');
        }

        return redirect()->back();
    }

    public function venue_paid(Request $request)
    {

        $venue = \App\Venue::find($request->venue_id);
        $invoices = \App\VenueCreditInvoice::where(['venue_id' => $venue->id])->whereYear('created_at', '=', date('Y'))->whereMonth('created_at', '=', $request->month)->get();
        
        if($invoices->count() and $request->payment_status == "paid")
        {
            foreach($invoices as $invoice)
            {
                $invoice->update(['status' => 'paid']);
            }

        } else if($invoices->count() and $request->payment_status == "outstanding") {

            foreach($invoices as $invoice)
            {
                $invoice->update(['status' => 'owed']);
            }
            
        } else {
            \Session::flash('error', 'There are no invoices available for ' . date('F', mktime(0, 0, 0, $request->month, 10)) . " " . date('Y'));
        }

        return redirect()->back();
    }

    // Super Dashboard end

    // Coaches Dashboard

    public function age_groups_view()
    {
        $user = \Auth::user();
        $venue = \App\Venue::find($is_coach->venue_id);
        $age_groups = \App\SoccerSchoolsAgeGroup::where('venue_id', $is_coach->venue_id)->orderBy('name')->paginate(15);

        return view('coach.age_groups', ['age_groups' => $age_groups]);
    }

    public function add_age_group(Request $request) 
    {
        $user = \Auth::user();
        $venue_id = null;
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

    // End Coaches

    // Player Club views

    public function club_index()
    {
        $user = \Auth::user();
        $user_clubs_arr = [];
        $user_is_club_admin = \App\TeamAdmin::where('user_id', $user->id)->first();
        $user_is_club_player = \App\TeamPlayer::where('user_id', $user->id)->first();
        
        if($user_is_club_admin)
        {
            $user_club_ids = \App\TeamAdmin::where('user_id', $user_is_club_admin->user_id)->get();

        } else if($user_is_club_player) {
            $user_club_ids = \App\TeamPlayer::where('user_id', $user_is_club_player->user_id)->get();

        } else {
            $user_club_ids = [0];
        }

        foreach($user_club_ids as $user_club_id)
        {
            if($user_club_id)
            {
                array_push($user_clubs_arr, $user_club_id->team_id);
            }
        }

        $clubs = \App\Team::whereIn('id', $user_clubs_arr)->get();

        return view('clubs.my_clubs', ['clubs' => $clubs]);
    }

    public function club_view($club_id, $club_name)
    {
        $user = \Auth::user();
        $is_admin = \App\TeamAdmin::where(['team_id' => $club_id, 'user_id' => $user->id])->first();
        $club = \App\Team::find($club_id);
        $venue = \App\Venue::find($club->venue_id);
        $has_team_profile = \App\TeamProfile::where('team_id', $club->id)->first();
        $team_players = \App\TeamPlayer::where('team_id', $club->id)->get();
        $stream_ids = [];
        $club_fixtures  = \App\Fixture::where('team_a', $club->name)->orWhere('team_b', $club->name)->get();

        foreach($club_fixtures as $club_fixture)
        {
            array_push($stream_ids, $club_fixture->stream_id);
        }

        $club_vods = \App\Stream::whereIn('id', $stream_ids)->orderBy('created_at', 'DSC')->paginate(15);

        return view('clubs.club_view', ['club' => $club, 'venue' => $venue, 'has_team_profile' => $has_team_profile,
                                        'team_players' => $team_players, 'club_vods' => $club_vods, 'is_admin' => $is_admin]);
    }

    // End Player Club views

    // Start Club manager views

    public function team_admin_notifications()
    {
        $user = \Auth::user();
        $is_team_admins = \App\TeamAdmin::where('user_id', $user->id)->get();
        
        $team_ids = [];
        $notifications = null;

        foreach($is_team_admins as $team_admin)
        {
            array_push($team_ids, $team_admin->team_id);
        }

        $notifications = \App\TeamPlayerRequest::whereIn('team_id', $team_ids)->where(['status' => 'pending'])->orderBy('created_at', 'DSC')->get();

        return view('club_admins.notifications', ['notifications' => $notifications]);
    }

    public function player_notification_request(Request $request)
    {
        $user = \Auth::user();
        $notification = \App\TeamPlayerRequest::find($request->notification_id);
        $notification_user = \App\User::find($notification->user_id);

        if($request->response == 'accept')
        {
            $notification->update(['status' => 'complete']);
            $new_player = \App\TeamPlayer::create(['user_id' => $notification->user_id, 'team_id' => $notification->team_id, 'checked_by' => $user->id, 'active_status' => 'active']);

            \Session::flash("success", "You have accepted " . $notification_user->firstname . "'s request.");

        } else if($request->response == 'decline') {
            $notification->update(['status' => 'dismissed']);
            \Session::flash("warning", "You have declined " . $notification_user->firstname . "'s request.");
        }

        return redirect()->back();
    }

    public function club_edit($club_id, $club_name)
    {
        $user = \Auth::user();
        $club = \App\Team::find($club_id);
        $venue = \App\Venue::find($club->venue_id);
        $has_team_profile = \App\TeamProfile::where('team_id', $club->id)->first();
        $team_profile = \App\TeamProfile::where('team_id', $club->id)->first();
        $team_ids = [];
        $admin_to_teams = \App\TeamAdmin::where('user_id', $user->id)->get();

        foreach($admin_to_teams as $admin_to_team)
        {
            array_push($team_ids, $admin_to_team->team_id);
        }

        if(\App\TeamAdmin::whereIn('team_id', $team_ids)->where('user_id', $user->id)->count())
        {

            return view('club_admins.edit_team', ['club' => $club, 'team_profile' => $team_profile, 'venue' => $venue, 'has_team_profile' => $has_team_profile]);
        } else {

            return response('Unauthorized.', 401);
        }

    }

    public function club_player_edit($club_id, $club_name)
    {
        $user = \Auth::user();
        $club = \App\Team::find($club_id);
        $venue = \App\Venue::find($club->venue_id);
        $is_admin = \App\TeamAdmin::where(['team_id' => $club_id, 'user_id' => $user->id])->first();
        $team_players = \App\TeamPlayer::where('team_id', $club->id)->get();
        $has_team_profile = \App\TeamProfile::where('team_id', $club->id)->first();
        $team_profile = \App\TeamProfile::where('team_id', $club->id)->first();
        $team_ids = [];
        $admin_to_teams = \App\TeamAdmin::where('user_id', $user->id)->get();

        foreach($admin_to_teams as $admin_to_team)
        {
            array_push($team_ids, $admin_to_team->team_id);
        }

        if(\App\TeamAdmin::whereIn('team_id', $team_ids)->where('user_id', $user->id)->count())
        {

            return view('club_admins.edit_players', ['club' => $club, 'team_profile' => $team_profile, 'venue' => $venue,
                                                     'has_team_profile' => $has_team_profile, 'is_admin' => $is_admin, 'team_players' => $team_players]);
        } else {

            return response('Unauthorized.', 401);
        }

    }

    public function remove_player(Request $request)
    {
        $user = \Auth::user();
        $team_ids = [];
        $admin_to_teams = \App\TeamAdmin::where('user_id', $user->id)->get();

        foreach($admin_to_teams as $admin_to_team)
        {
            array_push($team_ids, $admin_to_team->team_id);
        }

        if(\App\TeamAdmin::whereIn('team_id', $team_ids)->where('user_id', $user->id)->count())
        {
            $team_player = \App\TeamPlayer::find($request->player_id);
            $team_admin = \App\TeamAdmin::where(['user_id' => $team_player->user_id, 'team_id' => $team_player->team_id])->first();
            $club = \App\Team::find($team_player->team_id);
            $player_user = \App\User::find($team_player->user_id);
            $team_player_request = \App\TeamPlayerRequest::where(['user_id' => $team_player->user_id, 'team_id' => $team_player->team_id])->first();
            $team_admin_request = \App\TeamAdminRequest::where(['user_id' => $team_player->user_id, 'team_id' => $team_player->team_id])->first();
            
            if($team_player_request)
            {
                $team_player_request->delete();
            }

            if($team_admin_request)
            {
                $team_admin_request->delete();
            }

            if($team_admin)
            {

                $team_admin->delete();
                $team_player->delete();

                \Session::flash('success', 'You have removed yourself from the club ' . $club->name);
                return redirect()->to('/user-profile');

            } else if($team_player) {
                $team_player->delete();

                \Session::flash('success', $player_user->firstname . ' ' . $player_user->surname . ' has been removed from the club.');
                return redirect()->back();
            } else {

                \Session::flash('success', 'player has already been removed from the club.');
                return redirect()->to('user-profile');
            }
            
        } else {

            return response('Unauthorized.', 401);
        }

    }

    public function add_player(Request $request)
    {
        $user = \Auth::user();
        $team_ids = [];
        $admin_to_teams = \App\TeamAdmin::where('user_id', $user->id)->get();

        $validatedData = $request->validate([
            'email' => 'required|email|exists:users',
            'club_id' => 'required',
        ]);

        foreach($admin_to_teams as $admin_to_team)
        {
            array_push($team_ids, $admin_to_team->team_id);
        }

        if(\App\TeamAdmin::whereIn('team_id', $team_ids)->where('user_id', $user->id)->count())
        {
            $player_is_user = \App\User::where('email', $request->email)->first();

            if($player_is_user)
            {
                $already_player = \App\TeamPlayer::where(['user_id' => $player_is_user->id, 'team_id' => $request->club_id])->first();

                if($already_player)
                {

                    \Session::flash('error', $player_is_user->email . ' has already been registered with the club.');
                } else {

                    $new_player = \App\TeamPlayer::create(['user_id' => $player_is_user->id, 'team_id' => $request->club_id, 'active_status' => 'active']);
                    if($new_player)
                    {
                        \Session::flash('success', $player_is_user->email . ' has been registered with the club.');

                    } else {
                        \Session::flash('error', 'An internal server error has occured please contact Paperclip SA.');

                    }
                }
            }

            return redirect()->back();
        } else {

            return response('Unauthorized.', 401);
        }
    }

    public function player_status(Request $request)
    {
        $user = \Auth::user();
        $team_ids = [];
        $admin_to_teams = \App\TeamAdmin::where('user_id', $user->id)->get();

        foreach($admin_to_teams as $admin_to_team)
        {
            array_push($team_ids, $admin_to_team->team_id);
        }

        if(\App\TeamAdmin::whereIn('team_id', $team_ids)->where('user_id', $user->id)->count())
        {
            $team_player = \App\TeamPlayer::find($request->player_id);
            $player_user = \App\User::find($team_player->user_id);

            if($team_player->active_status == 'active')
            {
                $team_player->update(['active_status' => 'suspended']);
                \Session::flash('warning', $player_user->firstname . ' ' . $player_user->surname . ' status has been set to suspended');
            } else {
                $team_player->update(['active_status' => 'active']);
                \Session::flash('success', $player_user->firstname . ' ' . $player_user->surname . ' status has been set to active');

            }

            return redirect()->back();
        } else {

            return response('Unauthorized.', 401);
        }

    }

    public function club_save(Request $request)
    {
        $user = \Auth::user();
        $team = \App\Team::find($request->team_id);
        $team_profile = \App\TeamProfile::where('team_id', $team->id)->first();
        $team_ids = [];
        $admin_to_teams = \App\TeamAdmin::where('user_id', $user->id)->get();

        foreach($admin_to_teams as $admin_to_team)
        {
            array_push($team_ids, $admin_to_team->team_id);
        }

        if(\App\TeamAdmin::whereIn('team_id', $team_ids)->where('user_id', $user->id)->count())
        {
            if($team_profile)
            {
                $logo_path = $request->file('logo_img')->store('/clubs/logos', 'public');
                $img_name = explode('/', $logo_path)[2];

                if($team_profile->logo)
                {
                    \Storage::disk('public')->delete('clubs/logos/'. $team_profile->logo);
                }

                $team_profile->update(['description' => $request->club_bio, 'logo' => $img_name]);
            } else {
                $logo_path = $request->file('logo_img')->store('/clubs/logos', 'public');
                $img_name = explode('/', $logo_path)[2];
                $new_team_profile = \App\TeamProfile::create(['team_id' => $team->id, 'description' => $request->club_bio, 'logo' => $img_name]);
            }

        } else {
            return abort(401);
        }

        return redirect()->to('/user-profile/my-soccer-clubs');

    }


    // End Club manager views

}
