<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;

class AdminOrRefController extends Controller
{
// Views

    public function edit_fixture($fixture_id)
    {
        $user = Auth::user();
        $fixture = \App\Fixture::find($fixture_id);
        $stream = \App\stream::find($fixture->stream_id);
        $venue = \App\Venue::find($fixture->venue_id);
        $fields = \App\Field::where('venue_id', $venue->id)->first();
        $ports = explode(",", $fields->ports);
        $field_names = explode(",", $fields->port_names);
        $teams = \App\Team::where('active_status', 'active')->orderby('name', 'ASC')->get();

        $user_is_admin = false;

        if(\App\Admin::where('user_id', $user->id)->first())
        {
            $user_is_admin = true;
        }



        return view('admin_users.edit_fixture', ['fixture' => $fixture, 'stream' => $stream, 
                                                 'teams' => $teams, 'ports' => $ports,
                                                 'user_is_admin' => $user_is_admin,
                                                 'field_names' => $field_names, 'venue' => $venue]);
    }

    public function edit_fixture_save(Request $request, $fixture_id)
    {
        $user = Auth::user();
        $user_is_admin = false;
        $fixture = \App\Fixture::find($fixture_id);
        $stream = \App\Stream::find($fixture->stream_id);
        $venue = \App\Venue::find($fixture->venue_id);

        if(\App\Admin::where('user_id', $user->id)->first())
        {
            $user_is_admin = true;
        }

        $validatedData = $request->validate([
            'team_a' => 'required',
            'team_b' => 'required',
            'fixture_type' => 'required',
            'date_time' => 'required',
            'venue_id' => 'required',
        ]);

        if($stream->stream_type !== 'live')
        {
            $stream->update(['name' => $request->name . "-00", 'stream_type' => 'none', 'venue_id' => $request->venue_id, 'field_port' => $request->camera_port,
                         'uri' => 'rtsp://'.$venue->username . ':' . $venue->password . '@' . $venue->venue_ip . ':' . $request->camera_port . '/h264',
                         'http_url' => 'http://192.168.0.20:1935/' . $venue->wow_app_name . '/' . $request->name . '.stream_source/playlist.m3u8',
                         'storage_location' => 'VOD_STORAGE_2']);
                         
            if($stream) {
                $fixture->update([
                    'venue_id' => $request->venue_id,
                    'stream_id' => $stream->id,
                    'type' => $request->fixture_type,
                    'team_a' => $request->team_a,
                    'team_b' => $request->team_b,
                    'date_time' => $request->date_time,
                ]);
    
                \Session::flash('success', "The fixture has been updated.");
    
                if($user_is_admin) 
                {
                    return redirect()->to('/admin/dashboard');
                } else {
                    return redirect()->to('/referee/dashboard');
                }  
                
    
            } else {
                \Session::flash('error', "An Error has occured creating stream for fixture");
                return redirect()->back();
    
            }
        } else {
            \Session::flash('warning', "The stream is currently live. Disconnect the stream before updating.");
            return redirect()->back();
        }
                

    }    
}
