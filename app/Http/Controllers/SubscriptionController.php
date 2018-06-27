<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;

class SubscriptionController extends Controller
{
    // Methods

    function cancelSubscription($subscribed_user)
    {
        $timestamp = date( 'Y-m-d' ) . 'T' . date( 'H:i:s' );

        $pfData = ['merchant-id' => env('PAYFAST_MERCHANT_ID'), 'passphrase' => env('PAYFAST_PASSPHRASE'), 'timestamp' => $timestamp, 'version' => 'v1'];
        ksort( $pfData );

        $encoded_data = http_build_query($pfData);

        $signature = md5( $encoded_data );

        
        $ch = curl_init( 'https://api.payfast.co.za/subscriptions/' . $subscribed_user->token . '/cancel' );
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $ch, CURLOPT_HEADER, false );
        curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
        curl_setopt( $ch, CURLOPT_TIMEOUT, 60 );
        curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, 'PUT' );
        curl_setopt( $ch, CURLOPT_POSTFIELDS, $encoded_data );
        curl_setopt( $ch, CURLOPT_VERBOSE, 1 );
        curl_setopt( $ch, CURLOPT_HTTPHEADER, array(
            'version: ' . $pfData['version'],
            'merchant-id: ' . $pfData['merchant-id'],
            'signature: ' . $signature,
            'timestamp: ' . $timestamp
        ) );

        $res = curl_exec( $ch );
        if( curl_getinfo($ch)['http_code'] == '200')
        {
            $subscribed_user->delete();
        }
        curl_close( $ch );  
    }


    // Views

    public function checkout_subscription()
    {
        $user = Auth::user();     
        $channels = \App\Venue::where('active_status', 'active')->get();

        return view('subscription.checkout', ['user' => $user, 'channels' => $channels]);
    }

    public function post_subscription(Request $request)
    {
        $user = Auth::user();
        $unique_id = md5(uniqid(rand(), true));
        $pfOutput = "";
        $passPhrase = 'Payfastpaperclipsa';

        $item_name_str = '';

        if($request->subscription_type == 'full_access')
        {
            $item_name_str = 'Paperclip SA Full Access Subscription to indoor soccer channels.';
        } else {
            $item_name_str = 'Paperclip SA '. ucwords(str_replace('_', ' ', $request->subscription_type)) .' Subscription to ' . \App\Venue::find($request->channel)->name;
        }

        $recurring_amount = '30';

        if($request->subscription_type == 'full_access')
        {
            $recurring_amount = '60';
        }

        $data = array(
            // Merchant details
            'merchant_id' => env('PAYFAST_MERCHANT_ID'),
            'merchant_key' => env('PAYFAST_MERCHANT_KEY'),
            'return_url' => 'http://www.paperclipsa.co.za/subscription/success',
            'cancel_url' => 'http://www.paperclipsa.co.za/subscription/cancel',
            'notify_url' => 'http://www.paperclipsa.co.za/subscription/notify',
            // Buyer details
            'name_first' => $user->firstname,
            'name_last'  => $user->surname,
            'email_address'=> $user->email,
            'email_confirmation' => 1,
            'confirmation_address' => $user->email,
            // Transaction details
            'payment_method' => 'cc',
            'subscription_type' => 1,
            'billing_date' => date('Y-m-d'),
            'm_payment_id' => $unique_id, //Unique payment ID to pass through to notify_url
            // Amount needs to be in ZAR
            // If multicurrency system its conversion has to be done before building this array
            'amount' => number_format( sprintf( "%.2f", $recurring_amount ), 2, '.', '' ),
            'recurring_amount' => number_format( sprintf( "%.2f", $recurring_amount ), 2, '.', '' ),
            'frequency' => 3,
            'cycles' => 0,
            'item_name' => $item_name_str,
            'item_description' => 'Subscribtion type: '. $request->subscription_type,
            'custom_str1' => $user->id,
            'custom_str2' => $request->channel,
            'custom_str3' => $request->subscription_type,
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

    public function notify(Request $request)
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

        $is_subscriber = \App\SubscribedUser::where('user_id', $request->custom_str1)->first();

        if($request->payment_status == 'COMPLETE')
        {
            if($is_subscriber)
            {
                $is_subscriber->status = 'active';
                $is_subscriber->pf_payment_id = $request->pf_payment_id;
                $is_subscriber->payment_status = $request->payment_status;
                $is_subscriber->item_name = $request->item_name;
                $is_subscriber->amount_gross = $request->amount_gross;
                $is_subscriber->amount_fee = $request->amount_fee;
                $is_subscriber->amount_net = $request->amount_net;
                $is_subscriber->subscription_date = date('Y-m-d H:i:s');
                $is_subscriber->token = $request->token;
                $is_subscriber->signature = $request->signature;
                $is_subscriber->save();

                $is_full_access_user = \App\FullAccessUser::where('user_id', $request->custom_str1)->first();
                $is_single_access_user = \App\SingleAccessUser::where('user_id', $request->custom_str1)->first();

                if($is_full_access_user and $request->custom_str3 == 'single_channel')
                {
                    $this->cancelSubscription($is_full_access_user);

                    $new_subscribed_single_channel = \App\SingleAccessUser::create([
                        'user_id' => $request->custom_str1,
                        'venue_id' => $request->custom_str2,
                        'token' => $request->token 
                    ]);

                    $subscriber = \App\SubscribedUser::where('user_id', $request->custom_str1)->first();
                    $subscriber->status = 'active';
                    $subscriber->save();

                } else if($is_single_access_user and $request->custom_str3 == 'full_access') {
                    $this->cancelSubscription($is_single_access_user, 'single_access');
                    //$client = new \GuzzleHttp\Client();    

                    $new_subscribed_full_access = \App\FullAccessUser::create([
                        'user_id' => $request->custom_str1,
                        'token' => $request->token
                    ]);

                    $subscriber = \App\SubscribedUser::where('user_id', $request->custom_str1)->first();
                    $subscriber->status = 'active';
                    $subscriber->save();

                } else if($is_single_access_user and $request->custom_str3 == 'single_channel') {

                    $this->cancelSubscription($is_single_access_user);                   

                    $new_subscribed_single_channel = \App\SingleAccessUser::create([
                        'user_id' => $request->custom_str1,
                        'venue_id' => $request->custom_str2,
                        'token' => $request->token 
                    ]);

                    $subscriber = \App\SubscribedUser::where('user_id', $request->custom_str1)->first();
                    $subscriber->status = 'active';
                    $subscriber->save();
                }
            } else {
                        
                $new_subscribed_user = \App\SubscribedUser::create([
                    'user_id' => $request->custom_str1,
                    'pf_payment_id' => $request->pf_payment_id,
                    'payment_status' => $request->payment_status,
                    'item_name' => $request->item_name,
                    'amount_gross' => $request->amount_gross,
                    'amount_fee' => $request->amount_fee,
                    'amount_net' => $request->amount_net,
                    'token' => $request->token,
                    'signature' => $request->signature,
                    'subscription_date' => date('Y-m-d H:i:s'),
                    'status' => 'active'
                ]);

                if($request->custom_str3 == 'single_channel')
                {
                    $new_subscribed_single_channel = \App\SingleAccessUser::create([
                        'user_id' => $request->custom_str1,
                        'venue_id' => $request->custom_str2,
                        'token' => $request->token 
                    ]);
                    
                } else if($request->custom_str3 == 'full_access') {
                    $new_subscribed_full_access = \App\FullAccessUser::create([
                        'user_id' => $request->custom_str1,
                        'token' => $request->token
                    ]);
                } else {
                    die('Invalid user subscription');
                }
            }
        } else {
            if($is_subscriber)
            {
                $is_subscriber->status = 'inactive';
                $is_subscriber->save();
            }
        }

        return response(200);   

    }

    public function success() 
    {
        $user = \Auth::user();
        $is_single_access_user = \App\SingleAccessUser::where('user_id', $user->id)->first();
        $is_full_access_user = \App\FullAccessUser::where('user_id', $user->id)->first();
        $subscribed_user = \App\SubscribedUser::where('user_id', $user->id)->first();

        $venue = null;

        if ($is_single_access_user and $subscribed_user)
        {
            if($subscribed_user->status == 'active')
            {
                $venue = \App\Venue::find($is_single_access_user->venue_id);
                \Session::flash('success', 'You have been subscribed to ' . $venue->name); 
            } else {
                \Session::flash('error', 'There was an internal or external server error with the payment. Please contact info@paperclipsa.co.za for assistance');
                return redirect()->to('/subscription/error');
            }
         

        } else if ($is_full_access_user and $subscribed_user) {
            if($subscribed_user->status == 'active')
            {

                \Session::flash('success', 'You have subscribed to all indoor soccer channels');
            } else {
                \Session::flash('error', 'There was an internal or external server error with the payment. Please contact info@paperclipsa.co.za for assistance');
                return redirect()->to('/subscription/error');
            }
        } else {
            \Session::flash('error', 'There was an internal or external server error with the payment. Please contact info@paperclipsa.co.za for assistance');
            return redirect()->to('/subscription/error');
        }
        
        return view('subscription.success', ['full_access_user' => $is_full_access_user, 'single_access_user' => $is_single_access_user, 'venue' => $venue, 'subscribed_user' => $subscribed_user]);   

    }

    public function cancel() 
    {
        return view('subscription.cancel');
    }

    public function error()
    {
        return view('subscription.error');
    }
}
