<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;

class OndemandController extends Controller
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

            if ($user_points >= 1)
            {
                $sufficient_points = true;
            }
    
            if ($user_streams and $sufficient_points)
            {
                $account_balance->balance_value = (string)((int)$user_points - 0);
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
    
                $account_balance->balance_value = (string)((int)$user_points - 0);
    
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
                $account_balance->balance_value = (string)((int)$user_points - 5);
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

        } else if ( $video_type == 'squash' ) {
            $user_streams = \App\SquashUserStream::where('user_id', $user->id)->first();

            if ($user_points >= 10)
            {
                $sufficient_points = true;
            }
    
            if ($user_streams and $sufficient_points)
            {
                $account_balance->balance_value = (string)((int)$user_points - 10);
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
                $new_user_streams = \App\SquashUserStream::create(['user_id' => $user->id, 'stream_ids' => $stream_id]);
                $user_streams = \App\SquashUserStream::where('user_id', $user->id)->first();
                $account_balance = \App\AccountBalance::where('user_id', $user->id)->first();
    
                $account_balance->balance_value = (string)((int)$user_points - 10);
    
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
        }

    }

    // Views
    public function index(Request $request)
    {
        $channels = \App\Venue::where('active_status', 'active')->get();

        if($request->channel and $request->channel !== 'all')
        {
            $vods = \App\Stream::orderBy('created_at', 'desc')->where('venue_id', $request->channel)->paginate(30);
            
        } else {
            $vods = \App\Stream::orderBy('created_at', 'desc')->paginate(30);
        }
        
        
        return view('public.on_demand', ['vods' => $vods, 'channels' => $channels, 'request' => $request]);
    }

    public function index_squash(Request $request)
    {
        $vods = \App\SquashStream::orderBy('created_at', 'desc')->where('stream_type', 'vod')->paginate(30);

        return view('public.on_demand_squash', ['vods' => $vods]);
    }

    public function vod_watch($id, $streamfile_name)
    {
        $user = Auth::user();
        $vod = \App\Stream::where(['id' => $id, 'name' => $streamfile_name])->first();
        $current_venue = \App\Venue::where('id', $vod->venue_id)->first();
        $user_streams = \App\UserStreams::where('user_id', $user->id)->first();
        $account_balance = \App\AccountBalance::where('user_id', $user->id)->first();
        $fixture = \App\Fixture::where("stream_id", $vod->id)->first();
        $vods_from_venue = \App\Stream::where('venue_id', $vod->venue_id)->paginate(15);

        $stream_available = false;

        if($user_streams)
        {
            $user_purchased_streams = $user_streams->stream_ids;
            $purchased_streams_array = explode(',', $user_purchased_streams);
            if (in_array((string)$vod->id, $purchased_streams_array))
            {
                $stream_available = true;
            }

        } else {
            $user_purchased_streams = false;
            $stream_available = false;
        }

        // Subscribed user single or full access

        $is_subscribed_user = \App\SubscribedUser::where('user_id', $user->id)->first();

        if($is_subscribed_user)
        {
            if($is_subscribed_user->status == 'active')
            {
                $is_single_access_user = \App\SingleAccessUser::where('user_id', $user->id)->first();
                $is_full_access_user = \App\FullAccessUser::where('user_id', $user->id)->first();

                if($is_single_access_user)
                {
                    if($is_single_access_user->venue_id == $vod->venue_id) {
                        $stream_available = true;
                        if($user_streams)
                        {
                            if($user_streams->stream_ids)
                            {
                                $streams_array = explode(",", $user_streams->stream_ids);
                                if(!in_array((string)$vod->id, $streams_array))
                                {
                                    $user_streams->stream_ids = $user_streams->stream_ids.",".$vod->id;
                                    $user_streams->save();
                                }
                                
                            } else {
                                $user_streams->stream_ids = $vod->id;
                            } 
                        } else {
                            $new_user_streams = \App\UserStreams::create(['user_id' => $user->id, 'stream_ids' => $vod->id]);
                        }
                    } 
                } else if($is_full_access_user) {
                    $stream_available = true;
                }
            }
        }

        return view('public.on_demand_view', [
         'vod' => $vod,
         'user_purchased_streams' => $user_purchased_streams,
         'stream_available' => $stream_available,
         'fixture' => $fixture,
         'vods_from_venue' => $vods_from_venue,
         'current_venue' => $current_venue,
         'account_balance' => $account_balance]);

    }

    public function vod_squash_watch($id, $streamfile_name)
    {
        $user = Auth::user();
        $vod = \App\SquashStream::where(['id' => $id, 'name' => $streamfile_name])->first();
        $current_venue = \App\Venue::where('id', $vod->venue_id)->first();
        $user_streams = \App\SquashUserStream::where('user_id', $user->id)->first();
        $account_balance = \App\AccountBalance::where('user_id', $user->id)->first();
        $fixture = \App\SquashFixture::where("squash_stream_id", $vod->id)->first();
        $vods_from_venue = \App\SquashStream::where('venue_id', $vod->venue_id)->paginate(15);
        $rounds = json_decode($fixture->rounds, true);
        $round_points = json_decode($fixture->round_points, true);

        $stream_available = false;

        if($user_streams)
        {
            $user_purchased_streams = $user_streams->stream_ids;
            $purchased_streams_array = explode(',', $user_purchased_streams);
            if (in_array((string)$vod->id, $purchased_streams_array))
            {
                $stream_available = true;
            }

        } else {
            $user_purchased_streams = false;
            $stream_available = false;
        }

        return view('public.on_demand_squash_view', [
            'vod' => $vod,
            'fixture' => $fixture,
            'rounds' => $rounds,
            'round_points' => $round_points,
            'vods_from_venue' => $vods_from_venue,
            'current_venue' => $current_venue,
            'account_balance' => $account_balance,
            'user_purchased_streams' => $user_purchased_streams,
            'stream_available' => $stream_available]);
    }

    public function vod_purchase(Request $request)
    {
        $user = Auth::user();
        $vod = \App\Stream::where(['id' => $request->vod_id, 'name' => $request->vod_name])->first();
        $user_streams = \App\UserStreams::where('user_id', $user->id)->first();
        $current_venue = \App\Venue::where('id', $vod->venue_id)->first();
        $account_balance = \App\AccountBalance::where('user_id', $user->id)->first();
        $fixture = \App\Fixture::where("stream_id", $vod->id)->first();
        $vods_from_venue = \App\Stream::where('venue_id', $vod->venue_id)->paginate(15);

        $stream_available = false;
        $sufficient_points = false;

        return $this->purchase_access('/on-demand/'.$request->vod_id.'/'.$request->vod_name, 'action_sport', $request->vod_id, $request->vod_name);

    }

    public function vod_squash_purchase(Request $request)
    {    
        $user = Auth::user();
        $vod = \App\SquashStream::where(['id' => $request->vod_id, 'name' => $request->vod_name])->first();
        $user_streams = \App\SquashUserStream::where('user_id', $user->id)->first();
        $current_venue = \App\Venue::where('id', $vod->venue_id)->first();
        $account_balance = \App\AccountBalance::where('user_id', $user->id)->first();
        $fixture = \App\SquashFixture::where("squash_stream_id", $vod->id)->first();
        $vods_from_venue = \App\SquashStream::where('venue_id', $vod->venue_id)->paginate(15);

        $stream_available = false;
        $sufficient_points = false;

        return $this->purchase_access('/on-demand/squash/'.$request->vod_id.'/'.$request->vod_name, 'squash', $request->vod_id, $request->vod_name);
    }

    public function ondemand_event_watch($id, $event_name)
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
        
        return view('public.event_vod_view', ['event' => $event,
                                               'cameras' => $cameras,
                                               'user_purchased_streams' => $user_purchased_streams,
                                               'stream_available' => $stream_available,
                                               'account_balance' => $account_balance]);
    }
}
