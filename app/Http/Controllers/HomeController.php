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

    public function clubs($venue_id, $venue_name)
    {
        $venue = \App\Venue::find($venue_id);
        $all_clubs = \App\Team::where(['venue_id' => $venue_id, 'active_status' => 'active'])->where(function($query) {
                                        $query->where('name', 'NOT LIKE', "%test%");
                                     })->orderBy('name', 'ASC')->get();

        $clubs_total = \App\Team::where(['venue_id' => $venue_id, 'active_status' => 'active'])->where(function($query) {
                                            $query->where('name', 'NOT LIKE', "%test%");
                                        })->orderBy('name', 'ASC')->count();

        $clubs = \App\Team::where(['venue_id' => $venue_id, 'active_status' => 'active'])->where(function($query) {
                                    $query->where('name', 'NOT LIKE', "%test%");
                                  })->orderBy('name', 'ASC')->paginate(30);



        return view('public.channels.clubs', ['venue' => $venue, 'clubs' => $clubs, 'clubs_total' => $clubs_total, 'all_clubs' => $all_clubs]);
    }

    public function club($venue_id, $venue_name, $club_id, $club_name)
    {
        $club = \App\Team::find($club_id);
        $venue = \App\Venue::find($club->venue_id);
        $has_team_profile = \App\TeamProfile::where('team_id', $club->id)->first();
        $team_players = \App\TeamPlayer::where(['team_id' => $club->id, 'active_status' => 'active'])->get();
        $stream_ids = [];
        $club_fixtures  = \App\Fixture::where('team_a', $club->name)->orWhere('team_b', $club->name)->get();

        foreach($club_fixtures as $club_fixture)
        {
            array_push($stream_ids, $club_fixture->stream_id);
        }

        $club_vods = \App\Stream::whereIn('id', $stream_ids)->orderBy('created_at', 'DSC')->paginate(15);

        return view('public.channels.club', ['club' => $club, 'venue' => $venue, 'has_team_profile' => $has_team_profile,
        'team_players' => $team_players, 'club_vods' => $club_vods]);
    }
}
