<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;

class LiveNowController extends Controller
{

    // Methods

    public function purchase_access($redirect_url, $video_type, $stream_id, $stream_name)
    {
        $user = Auth::user();
        $account_balance = \App\AccountBalance::where('user_id', $user->id)->first();
        $user_points = (int)$account_balance->balance_value;

        $stream_available = false;
        $sufficient_points = false;

        if ($video_type == 'action_sport')
        {
            $user_streams = \App\UserStreams::where('user_id', $user->id)->first();

            if ($user_points >= 5)
            {
                $sufficient_points = true;
            }
    
            if ($user_streams and $sufficient_points)
            {
                $account_balance->balance_value = (string)((int)$user_points - 5);
                if($user_streams->stream_ids)
                {
                    $user_streams->stream_ids = $user_streams->stream_ids.",".$stream_id;
                } else {
                    $user_streams->stream_ids = $stream_id;
                }
    
                $user_streams->save();
                $account_balance->save();
    
                $account_balance = \App\AccountBalance::where('user_id', $user->id)->first();
                $user_purchased_streams = $user_streams->stream_ids;
                $purchased_streams_array = explode(',', $user_purchased_streams);
    
                if (in_array((string)$stream_id, $purchased_streams_array))
                {
                    $stream_available = true;

                    return redirect()->to($redirect_url);
                }
    
            } else if ($user_points and $sufficient_points) {
                // Create Row for user streams
                $new_user_streams = \App\UserStreams::create(['user_id' => $user->id, 'stream_ids' => $stream_id]);
                $user_streams = \App\UserStreams::where('user_id', $user->id)->first();
                $account_balance = \App\AccountBalance::where('user_id', $user->id)->first();
    
                $account_balance->balance_value = (string)((int)$user_points - 5);
    
                $account_balance->save();
    
                $user_purchased_streams = $user_streams->stream_ids;
                $purchased_streams_array = explode(',', $user_purchased_streams);
                
                if (in_array((string)$stream_id, $purchased_streams_array))
                {
                    $stream_available = true;
                    return redirect()->to($redirect_url);
                }
    
            } else {
                // Exception for no account balance
                $stream_available = false;
            }

        } else if ($video_type == 'event') {
            $user_event_streams = \App\UserEventStream::where('user_id', $user->id)->first();

            if ($user_points >= 25)
            {
                $sufficient_points = true;
            }
    
            if ($user_event_streams and $sufficient_points)
            {
                $account_balance->balance_value = (string)((int)$user_points - 25);
                if($user_event_streams->stream_ids)
                {
                    $user_event_streams->stream_ids = $user_event_streams->event_stream_ids.",".$stream_id;
                } else {
                    $user_event_streams->event_stream_ids = $stream_id;
                }
    
                $user_event_streams->save();
                $account_balance->save();
    
                $account_balance = \App\AccountBalance::where('user_id', $user->id)->first();
                $user_purchased_streams = $user_event_streams->event_stream_ids;
                $purchased_streams_array = explode(',', $user_purchased_streams);
    
                if (in_array((string)$id, $purchased_streams_array))
                {
                    $stream_available = true;
                    return redirect()->to($redirect_url);
                }
    
            } else if ($user_points and $sufficient_points) {
                // Create Row for user streams
                $new_user_event_streams = \App\UserEventStream::create(['user_id' => $user->id, 'event_stream_ids' => (string)$stream_id]);
                $user_event_streams = \App\UserEventStream::where('user_id', $user->id)->first();
                $account_balance = \App\AccountBalance::where('user_id', $user->id)->first();
    
                $account_balance->balance_value = (string)((int)$user_points - 25);
    
                $account_balance->save();
    
                $user_purchased_streams = $user_event_streams->event_stream_ids;
                $purchased_streams_array = explode(',', $user_purchased_streams);
                
                if (in_array((string)$stream_id, $purchased_streams_array))
                {
                    $stream_available = true;
                    return redirect()->to($redirect_url);
                }
    
            } else {
                // Exception for no account balance
                $stream_available = false;
            }

        }

    }

    // Views
    public function index()
    {
        $live_streams = \App\Stream::all();

        return view('public.live_now', ['live_streams' => $live_streams]);
    }

    public function watch($stream_id, $stream_name)
    {
        $user = Auth::user();
        $live = \App\Stream::where(['id' => $stream_id, 'name' => $stream_name])->first();
        $user_streams = \App\UserStreams::where('user_id', $user->id)->first();
        $current_venue = \App\Venue::where('id', $live->venue_id)->first();
        $account_balance = \App\AccountBalance::where('user_id', $user->id)->first();
        $fixture = \App\Fixture::where("stream_id", $live->id)->latest()->first();
        $more_live_streams = \App\Stream::all();

        $stream_available = false;
        

        if($user_streams)
        {
            $user_purchased_streams = $user_streams->stream_ids;
            $purchased_streams_array = explode(',', $user_purchased_streams);
            if (in_array((string)$live->id, $purchased_streams_array))
            {
                $stream_available = true;
            }

        } else {
            $user_purchased_streams = false;
            $stream_available = false;
        }

        return view('public.live_now_view', ['live' => $live,
         'user_purchased_streams' => $user_purchased_streams,
         'stream_available' => $stream_available,
         'fixture' => $fixture,
         'more_live_streams' => $more_live_streams,
         'current_venue' => $current_venue,
         'account_balance' => $account_balance]);
    }

    public function vod_purchase(Request $request, $vod_id, $vod_name)
    {
        $user = Auth::user();
        $live = \App\Stream::where(['id' => $vod_id, 'name' => $vod_name])->first();
        $user_streams = \App\UserStreams::where('user_id', $user->id)->first();
        $current_venue = \App\Venue::where('id', $live->venue_id)->first();
        $account_balance = \App\AccountBalance::where('user_id', $user->id)->first();
        $fixture = \App\Fixture::where("stream_id", $live->id)->first();
        $more_live_streams = \App\Stream::all();

        $stream_available = false;
        $sufficient_points = false;

        return $this->purchase_access('/live-now/'.$vod_id.'/'.$vod_name, 'action_sport', $vod_id, $vod_name);

    }

    public function event_purchase(Request $request, $id, $event_name)
    {
        $user = Auth::user();
        $event = \App\Event::where(['id' => $id, 'eventName' => $event_name])->first();
        $user_event_streams = \App\UserEventStream::where('user_id', $user->id)->first();
        $cameras = explode(",", $event->cameras);
        $account_balance = \App\AccountBalance::where('user_id', $user->id)->first();

        $stream_available = false;
        $sufficient_points = false;

        $user_points = (int)$account_balance->balance_value;

        if ($user_points >= 25)
        {
            $sufficient_points = true;
        }

        if ($user_event_streams and $sufficient_points)
        {
            $account_balance->balance_value = (string)((int)$user_points - 5);
            if($user_event_streams->stream_ids)
            {
                $user_event_streams->stream_ids = $user_event_streams->event_stream_ids.",".$id;
            } else {
                $user_event_streams->event_stream_ids = $id;
            }

            $user_event_streams->save();
            $account_balance->save();

            $account_balance = \App\AccountBalance::where('user_id', $user->id)->first();
            $user_purchased_streams = $user_event_streams->event_stream_ids;
            $purchased_streams_array = explode(',', $user_purchased_streams);

            if (in_array((string)$id, $purchased_streams_array))
            {
                $stream_available = true;
            }

        } else if ($user_points and $sufficient_points) {
            // Create Row for user streams
            $new_user_event_streams = \App\UserEventStream::create(['user_id' => $user->id, 'event_stream_ids' => (string)$event->id]);
            $user_event_streams = \App\UserEventStream::where('user_id', $user->id)->first();
            $account_balance = \App\AccountBalance::where('user_id', $user->id)->first();

            $account_balance->balance_value = (string)((int)$user_points - 25);

            $account_balance->save();

            $user_purchased_streams = $user_event_streams->event_stream_ids;
            $purchased_streams_array = explode(',', $user_purchased_streams);
            
            if (in_array((string)$id, $purchased_streams_array))
            {
                $stream_available = true;
            }

        } else {
            // Exception for no account balance
            $stream_available = false;
        }

        return view('public.event_live_view', ['event' => $event,
            'cameras' => $cameras,
         'user_purchased_streams' => $user_purchased_streams,
         'stream_available' => $stream_available,
         'account_balance' => $account_balance]);

    }

    public function event_live_watch($id)
    {
        $user = Auth::user();
        $event = \App\Event::find($id);
        $cameras = explode(",", $event->cameras);
        $account_balance = \App\AccountBalance::where('user_id', $user->id)->first();

        $user_event_streams = \App\UserEventStream::where('user_id', $user->id)->first();

        $stream_available = false;

        if($user_event_streams)
        {
            $user_purchased_streams = $user_event_streams->event_stream_ids;
            $purchased_streams_array = explode(',', $user_purchased_streams);
            if (in_array((string)$event->id, $purchased_streams_array))
            {
                $stream_available = true;
            }

        } else {
            $user_purchased_streams = false;
            $stream_available = false;
        }
        
        return view('public.event_live_view', ['event' => $event,
                                               'cameras' => $cameras,
                                               'user_purchased_streams' => $user_purchased_streams,
                                               'stream_available' => $stream_available,
                                               'account_balance' => $account_balance]);
    }
}