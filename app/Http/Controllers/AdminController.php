<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\MessageBag;

define("WOWZA_HOST","http://10.0.0.5:8087/v2");
define("WOWZA_SERVER_INSTANCE", "_defaultServer_");
define("WOWZA_VHOST_INSTANCE", "_defaultVHost_");
define("WOWZA_USERNAME", env('WOWZA_USERNAME'));
define("WOWZA_PASSWORD", env('WOWZA_PASSWORD'));

class AdminController extends Controller
{
    // Methods

    // methods
    function getSettings()
    {
        // Settings for wowza connection 

        $response = new \Com\Wowza\Entities\Application\Helpers\Settings();

        $response->setHost(WOWZA_HOST);
        $response->setUsername(WOWZA_USERNAME);
        $response->setPassword(WOWZA_PASSWORD);

        return $response;
    }

    // Views

    public function index()
    {
        $user = Auth::user();
        $admin = \App\Admin::where('user_id', $user->id)->first();
        $venue = \App\Venue::find($admin->venue_id);
        $tomorrow = date("Y-m-d", strtotime('tomorrow'));
        $fields = \App\Field::where('venue_id', $venue->id)->first();
        $ports = explode(",", $fields->ports);
        $field_names = explode(",", $fields->port_names);
        $fixtures = \App\Fixture::where([['venue_id', $venue->id], ['date_time', '>=', date("Y-m-d")], ['date_time', '<',  $tomorrow = date("Y-m-d", strtotime('tomorrow'))]])->orderBy('date_time', 'asc')->get();
        $upcoming_fixtures = \App\Fixture::where([['venue_id', $venue->id], ['date_time', '>', date("Y-m-d")], ['date_time', '>',  $tomorrow = date("Y-m-d", strtotime('tomorrow'))]])->orderBy('date_time', 'asc')->get();
        $teams = \App\Team::where('active_status', 'active')->orderby('name', 'ASC')->get();

        return view('admin.dashboard', ['venue' => $venue, 'fixtures' => $fixtures,
                                        'teams' => $teams, 'ports' => $ports,
                                        'field_names' => $field_names, 'upcoming_fixtures' => $upcoming_fixtures]);

    }

    public function create_fixture(Request $request)
    {

        $venue = \App\Venue::find($request->venue_id);

        $validatedData = $request->validate([
            'team_a' => 'required',
            'team_b' => 'required',
            'fixture_type' => 'required',
            'date_time' => 'required',
            'venue_id' => 'required',

        ]);

       
        $new_stream = \App\Stream::create([
            'name' => preg_replace("/[^ \w]+/", "", $request->name),
            'stream_type' => 'none',
            'venue_id' => $request->venue_id,
            'field_port' => $request->camera_port,
            'uri' => 'rtsp://'.$venue->username . ':' . $venue->password . '@' . $venue->venue_ip . ':' . $request->camera_port . '/h264',
            'http_url' => 'http://192.168.0.20:1935/' . $venue->wow_app_name . '/' . $request->name . '.stream_source/playlist.m3u8',
            'storage_location' => 'VOD_STORAGE_2',                
        ]);
        if($new_stream) {
            $new_fixture = \App\Fixture::create([
                'venue_id' => $request->venue_id,
                'stream_id' => $new_stream->id,
                'type' => $request->fixture_type,
                'team_a' => $request->team_a,
                'team_b' => $request->team_b,
                'date_time' => $request->date_time . ":00",
            ]);
            \Session::flash('success', "The fixture has been created.");

            return redirect()->back();

        } else {
            \Session::flash('error', "An Error has occured creating stream for fixture");
            return redirect()->back();

        }
        

    }

    public function delete_fixture(Request $request)
    {
        $fixture = \App\Fixture::find($request->fixture_id);
        $stream = \App\Stream::find($fixture->stream_id);

        if($stream->stream_type !== 'live')
        {
            $fixture_deleted = $fixture->delete();
            $stream_deleted = $stream->delete();

            if($fixture_deleted and $stream_deleted) {
                \Session::flash('success', 'Fixture has been deleted');
            } else {
                \Session::flash('error', 'There was an error deleting the fixture. Please Contact PaperclipSA');
            }

        } else {
            \Session::flash('warning', "The stream is currently live. Disconnect the stream before deleting.");
        }

        return redirect()->back();
    }

}
