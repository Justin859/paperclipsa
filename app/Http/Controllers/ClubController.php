<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ClubController extends Controller
{
    //

    public function join_club_request(Request $request)
    {
        $validateData = $request->validate([

            'profile_type' => ['required', 'string'],
            'club' => ['required', 'string'],

        ]);
        
        $user = \Auth::user();
        $club = \App\Team::find($request->club);
        $venue = \App\Venue::find($club->venue_id);
        $club_has_admin = \App\TeamAdmin::where('team_id', $club->id)->first();

        $player_str = "a " . $request->profile_type;
        $admin_str =  "an " . $request->profile_type;

        $already_player = \App\TeamPlayer::where(['user_id' => $user->id, 'team_id' => $club->id])->first(); 
        $already_admin = \App\TeamAdmin::where(['user_id' => $user->id, 'team_id' => $club->id])->first();

        $player_already_requested = \App\TeamPlayerRequest::where(['user_id' => $user->id, 'team_id' => $club->id])->first();
        $admin_already_requested = \App\TeamAdminRequest::where(['user_id' => $user->id, 'team_id' => $club->id])->first();

        if(!$already_player and !$already_admin)
        {
            if($request->profile_type == 'player')
            {
                if(!$player_already_requested)
                {
                    if($request->message)
                    {
                        \App\TeamPlayerRequest::create(['user_id' => $user->id, 'team_id' => $club->id, 'venue_id' => $venue->id, 'status' => 'pending', 'message' => $request->message]);
                    } else {
                        \App\TeamPlayerRequest::create(['user_id' => $user->id, 'team_id' => $club->id, 'venue_id' => $venue->id, 'status' => 'pending']);
                    }
        
                    \Session::flash('success', 'Your request to join ' . $club->name . ' as ' . $player_str . ' has been sent. Club admins can confirm the request.');
                } else {
                    if($player_already_requested->status == 'dismissed')
                    {
                        if($request->message)
                        {
                            $player_already_requested->update(['status' => 'pending', 'message' => $request->message]);

                        } else {
                            $player_already_requested->update(['status' => 'pending']);
                        }

                        \Session::flash('warning', 'Your request to join ' . $club->name . ' as ' . $player_str . ' was dismissed by the club admin. Your request has been set to pending.');

                    } else {
                        \Session::flash('error', 'Your request to join ' . $club->name . ' as ' . $player_str . ' has already been sent. The club Admin can confirm the request.');
                    }

                }

                if(!$club_has_admin)
                {
                    \Session::flash('warning', 'The club has no administrator yet. Your request is pending until an admin registers with the club. Contact the Venue or Paperclip SA for assistance.');
                }

            } else {

                if(!$admin_already_requested)
                {
                    if($request->message)
                    {
                        \App\TeamAdminRequest::create(['user_id' => $user->id, 'team_id' => $club->id, 'venue_id' => $venue->id, 'status' => 'pending', 'message' => $request->message]);
        
                    } else {
                        \App\TeamAdminRequest::create(['user_id' => $user->id, 'team_id' => $club->id, 'venue_id' => $venue->id, 'status' => 'pending']);
        
                    }
        
                    \Session::flash('success', 'Your request to join ' . $club->name . ' as ' . $admin_str . ' has been sent. Admins and Referees from ' . $venue->name . ' can confirm the request.');
        
                } else {
                    if($admin_already_requested->status == 'dismissed')
                    {
                        if($request->message)
                        {
                            $admin_already_requested->update(['status' => 'pending', 'message' => $request->message]);

                        } else {
                            $admin_already_requested->update(['status' => 'pending']);
                        }

                        \Session::flash('warning', 'Your request to join ' . $club->name . ' as ' . $admin_str . ' was dismissed by an admin or referee. Your request has been set to pending. Admins and Referees from ' . $venue->name . ' can confirm the request.');

                    } else {
                        \Session::flash('error', 'Your request to join ' . $club->name . ' as ' . $admin_str . ' has already been sent. Admins and Referees from ' . $venue->name . ' can confirm the request.');
                    }
                }
            }

        } else {
            if($already_admin)
            {
                \Session::flash('error', 'You are already registered as an admin at ' . $club->name);
            } else if($already_player) {

                \Session::flash('error', 'You have already been registered as a player at ' . $club->name);
            }
        }

        return redirect()->back();
    }
}
