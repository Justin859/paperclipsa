<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class HomeController extends Controller
{
    // /**
    //  * Create a new controller instance.
    //  *
    //  * @return void
    //  */
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    // /**
    //  * Show the application dashboard.
    //  *
    //  * @return \Illuminate\Http\Response
    //  */

    public function index()
    {
        return view('welcome');
    }

    public function channels()
    {
        $venues = \App\Venue::where('active_status', 'active')->get();

        return view('public.channels.index', ['venues' => $venues]);

    }

    public function single_channel_main($venue_id, $venue_name)
    {
        $venue = \App\Venue::find($venue_id);

        return view('public.channels.single', ['venue' => $venue]);

    }

    public function single_channel_odv($venue_id, $venue_name)
    {

        $venue = \App\Venue::find($venue_id);

        $venue_odvs = \App\Stream::orderBy('created_at', 'desc')->where(['venue_id' => $venue_id, 'stream_type' => 'vod'])->paginate(20);

        if($venue->venue_type == 'squash')
        {
            $venue_odvs = \App\SquashStream::orderBy('created_at', 'desc')->where(['venue_id' => $venue_id, 'stream_type' => 'vod'])->paginate(20);
        }

        return view('public.channels.single_odv', ['venue' => $venue, 'venue_odvs' => $venue_odvs]);

    }

    public function single_channel_latest($venue_id, $venue_name)
    {
        
    }
}
