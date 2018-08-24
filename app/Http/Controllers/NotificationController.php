<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\OnDemandVideoEmail;


class NotificationController extends Controller
{
    // Notifications

    public function on_demand_notification(Request $request)
    {
        $user = \App\User::where('email', $request->email)->first();
        $notified_user = \App\NotifiedUser::where('user_id', $user->id)->first();
        $team = \App\Team::find($request->user_team);
        $fixture = \App\Fixture::find($request->fixture_id);
        $stream = \App\Stream::find($request->stream_id);
        $url_link = 'http://paperclipsa.local/on-demand/indoor-soccer/' . $stream->id . '/' . $stream->name;

        $main_message = $team->name . " has a new video, ".$fixture->team_a . " VS " . $fixture->team_b .", watch it now!";

        $data = ['email' => $request->email, 'name' => $request->name, 'main_message' => $main_message, 'url_link' => $url_link];

        $beautymail = app()->make(\Snowfire\Beautymail\Beautymail::class);
        $beautymail->send('emails.notification_odv', $data, function($message) use($data)
        {
            $email_address = $data['email'];
            $user_name = $data['name'];
            $message
                ->from('noreply@paperclipsa.co.za')
                ->to($email_address, $user_name)
                ->subject('Paperclip SA Notification');
        });

        return response(200);
    }

    public function subscribe_to_team(Request $request)
    {
        $user = \Auth::user();
        $team = \App\Team::find($request->team);
        $is_notified_user = \App\NotifiedUser::where('user_id', $user->id)->first();
        $is_verified_user = \App\VerifiedUser::where('user_id', $user->id)->first();

        if($is_verified_user and $is_verified_user->verified)
        {
            if(!$is_notified_user)
            {
                $new_notified_user = \App\NotifiedUser::create(['user_id' => $user->id, 'notifications' => '{"teams": ['.$team->id.']}', 'enabled' => 1]);
                \Session::flash('success', 'You have successfully subscribed for email notifications to '. $team->name.'.');
    
            } else {
                $notifications = json_decode($is_notified_user->notifications, true);
                $notf_array = $notifications["teams"];
    
                if(!in_array($team->id, $notf_array))
                {
                    array_push($notf_array, $team->id);
                    $notifications["teams"] = $notf_array;
                    $is_notified_user->update(['notifications' => json_encode($notifications)]);
    
                    \Session::flash('success', 'You have successfully subscribed for email notifications to '. $team->name.'.');
    
                } else {
                    \Session::flash('error', $team->name . ' has already been added to your subscriptions.');
    
                }
                
            }            
        } else {
            \Session::flash('error', 'Please verify your account email first before using email notifications.');
        }
        
        return redirect()->back();
    }

    public function cancel_team_notifications(Request $request)
    {
        $user = \Auth::user();
        $team = \App\Team::find($request->team_notf_id);
        $is_notified_user = \App\NotifiedUser::where('user_id', $user->id)->first();

        if($is_notified_user)
        {
            $notifications = json_decode($is_notified_user->notifications, true);
            $notf_array = $notifications["teams"];
            $new_array = [];

            if(in_array($team->id, $notf_array))
            {
                if(sizeof($notf_array) > 1)
                {
                    foreach($notf_array as $team_id)
                    {
                        if($team_id != $team->id)
                        {
                            array_push($new_array, $team_id);
                        }
                    }
                    $notifications["teams"] = $new_array;
                    $is_notified_user->update(['notifications' => json_encode($notifications)]);
                    \Session::flash('success', $team->name . ' has been removed from your email notifications.');                    
                } else {
                    $is_notified_user->delete();
                }

            }


        } else {
            \Session::flash('error', ' An error has occured please contact Paperclip SA for support. <a href="/contact">contact</a>');

        }

        return redirect()->back();

    }
}
