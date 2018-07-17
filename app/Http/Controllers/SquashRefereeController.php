<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;

define("WOWZA_HOST","http://192.168.0.69:8087/v2");
define("WOWZA_SERVER_INSTANCE", "_defaultServer_");
define("WOWZA_VHOST_INSTANCE", "_defaultVHost_");
define("WOWZA_USERNAME", env('WOWZA_USERNAME'));
define("WOWZA_PASSWORD", env('WOWZA_PASSWORD'));

class SquashRefereeController extends Controller
{
    //

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

        // Function to check connected streamfile status. Wowza will return 'Recording in progress' if the stream is connected.
    // Before checking wait 5 seconds to give the connection time to connect.
    // The streamfile and recorder will be removed if the stream is not connected properly

    function checkStreamConncetion($setup, $wowza_app_name, $wowza_streamfile_name, $app_stream_id, $app_fixture_id)
    {
        sleep(5); // Wait five seconds
        $sf_recordings = new \Com\Wowza\Recording($setup, $wowza_app_name);
        $response_recordings = $sf_recordings->getAll();

        $check_stream_response = 'unsuccessfull';

        // loop through the streamrecorder array from the response from wowza strored in $repsonse_recordings
        foreach($response_recordings->streamrecorder as $recorder)
        {
            if($recorder->recorderName == $wowza_streamfile_name.".stream_source") {
                $check_stream_response = 'successfull';

                if($recorder->recorderState == 'Recording in Progress') {
                    \Session::flash('success', $wowza_streamfile_name." Stream has started.");

                    // $stream = \App\SquashStream::find($app_stream_id);
                    // $stream->stream_type = "live";
                    // $stream->save();

                    return $check_stream_response;
                } else {

                    \Session::flash('error', $wowza_streamfile_name." Camera is not connected.");
                    $sf_recording = new \Com\Wowza\Recording($setup, $wowza_app_name, "_definst_");
                    $response_recording = $sf_recording->stop($wowza_streamfile_name . '.stream_source');  

                    // $sf = new \Com\Wowza\StreamFile($setup, $wowza_app_name, $wowza_streamfile_name);
                    // $response_sf = $sf->disconnect();

                    // if($response_sf->success)
                    // {
                    //     $wowzaApplication = new \Com\Wowza\Application($setup, $wowza_app_name);
                    //     $sf_remove = new \Com\Wowza\StreamFile($setup, $wowzaApplication->getName(), $wowza_streamfile_name);
                    //     $response_remove =$sf_remove->remove();
                    // }

                    return $check_stream_response;

                }
            } 
        }                      
    }

    function startRally($fixture_id)
    {
        // Make post url for this function
        // Check if streamfile is connected else connect streamfile
        // Save time in row for fixture when starting squash rally

        $fixture = \App\SquashFixture::find($fixture_id);
        $stream = \App\SquashStream::find($fixture->squash_stream_id);
        $venue = \App\Venue::find($fixture->venue_id);

        $rounds = json_decode($fixture->rounds, true);
        $current_round = count($rounds);
        $rallies_in_round = $rounds[$current_round]["rallies"];
        $current_rally = count($rallies_in_round);

        \Session::flash('success', 'Rally has started.');
        $rounds[count($rounds)]["rallies"][$current_rally]["rally_start_time"] = date('Y-m-d H:i:s');
        $fixture->rounds = json_encode($rounds);
        $fixture->rally_running = 'running';
        $fixture->save();
        // return redirect()->to('/referee/squash/dashboard/fixture/'.$fixture->id.'/'.$stream->name)->send();        

    }

    public function stopRally(Request $request)
    {
        // Make post url for this function
        // Save time in row for fixture when stopping squash rally

        $fixture = \App\SquashFixture::find($request->fixture_id);
        $stream = \App\SquashStream::find($fixture->squash_stream_id);
        $venue = \App\Venue::find($fixture->venue_id);

        $rounds = json_decode($fixture->rounds, true);
        $round_points = json_decode($fixture->round_points, true);
        $current_round = count($rounds);
        $rallies_in_round = $rounds[$current_round]["rallies"];
        $current_rally = count($rallies_in_round);

        $rounds[$current_round]["rallies"][$current_rally]["rally_end_time"] = date('Y-m-d H:i:s');
        $rounds[$current_round]["rallies"][$current_rally]["player_1_score"] = $round_points[$current_round]["player_1"];
        $rounds[$current_round]["rallies"][$current_rally]["player_2_score"] = $round_points[$current_round]["player_2"];
        $rounds[$current_round]["rallies"][$current_rally + 1] = ["rally_start_time" => "", "rally_end_time" => "", "player_1_score" => 0, "player_2_score" => 0];

        $fixture->rounds = json_encode($rounds);
        $fixture->rally_running = 'not_running';

        $fixture->save();

        $this->startRally($fixture->id);

        \Session::flash('success', 'Rally Ended Successfully.');

        return redirect()->to('/referee/squash/dashboard/fixture/'.$fixture->id.'/'.$stream->name)->send();

    }

    public function index()
    {
        $setup = $this->getSettings();
        $user = Auth::user();
        $referee = \App\Referee::where('user_id', $user->id)->first();
        $venue = \App\Venue::find($referee->venue_id);
        $tomorrow = date("Y-m-d", strtotime('tomorrow'));
        $fixtures = \App\SquashFixture::where([['venue_id', $venue->id], ['date_time', '>=', date("Y-m-d")], ['date_time', '<',  $tomorrow = date("Y-m-d", strtotime('tomorrow'))]])->orderBy('date_time', 'asc')->get();
        $venue_live_streams = \App\Venue::find($referee->venue_id)->where('stream_type', 'live');
        $fields = \App\Field::where('venue_id', $venue->id)->first();
        $ports = explode(",", $fields->ports);
        $field_names = explode(",", $fields->port_names);
        
        $sf_recording = new \Com\Wowza\Recording($setup, $venue->wow_app_name);
        $response_recording = false;

        if($sf_recording)
        {
            $response_recording = $sf_recording->getAll();
        }

        return view('referee.referee_squash_dashboard', ['fixtures' => $fixtures, 'response_recording' => $response_recording,
                                                'ports' => $ports, 'field_names' => $field_names, 'venue' => $venue]);
    }

    public function startStream( Request $request )
    {
        $user = Auth::user();
        $referee = \App\Referee::where('user_id', $user->id)->first();
        $venue = \App\Venue::find($referee->venue_id);
        $tomorrow = date("Y-m-d", strtotime('tomorrow'));
        $fixtures = \App\SquashFixture::where([['venue_id', $venue->id], ['date_time', '>=', date("Y-m-d")], ['date_time', '<',  $tomorrow = date("Y-m-d", strtotime('tomorrow'))]])->orderBy('date_time', 'asc')->get();
        $venue_live_streams = \App\Venue::find($referee->venue_id)->where('stream_type', 'live');

        $validatedData = $request->validate([
            'player_1' => 'required',
            'player_2' => 'required',
            'camera_port' => 'required'
        ]);

        $uri = "rtsp://". $venue->username .":". $venue->password ."@" . $venue->venue_ip .":" .$request->camera_port. "/h264"; // ex. rtsp://admin:wel0v3mar@196.40.191.23:555/h264
        $stream_file_name = $request->name;
        $application_name = $venue->wow_app_name;

        $setup = $this->getSettings();

        $wowzaApplication = new \Com\Wowza\Application($setup, $application_name);
        $sf = new \Com\Wowza\StreamFile($setup, $application_name, $stream_file_name);
        $response = $sf->create(array("uri"=>$uri,"streamTimeout"=>1200,"rtspSessionTimeout"=>800), "rtp");

        if ($response->success) {

            $sf_connect = new \Com\Wowza\StreamFile($setup, $application_name, $stream_file_name);
            $response_connection = $sf_connect->connect();

                if ($response_connection->success)
                {
                    $stream_status = false;
                    sleep(5);
                    $ch = curl_init( 'http://127.0.0.1:8087/v2/servers/_defaultServer_/vhosts/_defaultVHost_/applications/'.$application_name.'/instances/_definst_');
                    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
                    curl_setopt( $ch, CURLOPT_HEADER, false );
                    curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
                    curl_setopt( $ch, CURLOPT_TIMEOUT, 60 );
                    curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, 'GET' );
                    curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Accept:application/json') );

                    $res = curl_exec( $ch );
                    if( curl_getinfo($ch)['http_code'] == '200')
                    {
                        $array_res = json_decode($res, true);
                        foreach($array_res["incomingStreams"] as $stream)
                        {
                            if ($stream["name"] == $stream_file_name . '.stream')
                            {
                                if($stream["isConnected"])
                                {
                                    $stream_status = true;
                                }
                            } 
                        }
                    }
                    $response_curl = curl_getinfo($ch)['http_code'];
                    curl_close( $ch );  
                    
                    if($stream_status)
                    {
                        // Condition for adding or updating stream in Database.
                        if($request->squash_stream_id and \App\SquashStream::find($request->squash_stream_id)) {
                            //$this->checkStreamConncetion($this->getsettings(), $venue->wow_app_name, $stream_file_name, $request->squash_stream_id, $request->fixture_id);
                            return redirect()->to('/referee/squash/dashboard/fixture/'.$request->fixture_id.'/'.$request->name)->send();
                        } else {
                            $new_stream = \App\SquashStream::create(['name' => $request->name, 'stream_type' => 'live', 'camera_port' => $request->camera_port,
                                                                    'venue_id' => $venue->id, 'uri' => 'rtsp://'.$venue->username.':'.$venue->password.'@'.$venue->venue_ip.':'.$request->camera_port.'/h264',
                                                                    'http_url' => 'http://192.168.0.69:1935/'.$venue->wow_app_name.'/'.$request->name.'.stream_source/playlist.m3u8',
                                                                    'storage_location' => 'VOD_STORAGE_1']);
                            if($request->fixture_id)
                            {
                                $update_fixture = \App\SquashFixture::find($request->fixture_id);
                                $update_fixture->squash_stream_id = $new_stream->id;
                                $update_fixture->save();
                                $updated_fixture = \App\SquashFixture::find($request->fixture_id);

                                //$this->checkStreamConncetion($this->getsettings(), $venue->wow_app_name, $new_stream->name, $new_stream->id, $updated_fixture->id);
                                return redirect()->to('/referee/squash/dashboard/fixture/'.$request->fixture_id.'/'.$request->name)->send();

                            } else {
                                
                                $new_fixture = \App\SquashFixture::create(['squash_stream_id' => $new_stream->id,
                                                                    'player_1' => $request->player_1, 'player_2' => $request->player_2,
                                                                    'date_time' => $request->date_time, 'venue_id' => $venue->id,
                                                                    'rounds' => '{"1": {"winner": "", "round_start_time": "", "round_end_time": "",  "rallies": { "1": {"rally_start_time": "", "rally_end_time": "", "player_1_score": 0, "player_2_score": 0} } }}',
                                                                    'round_points' => '{"1": {"player_1": 0, "player_2": 0} }',
                                                                    'points' => '{ "1": { "player_1": {}, "player_2": {} } }']);

                                //$this->checkStreamConncetion($this->getsettings(), $venue->wow_app_name, $new_stream->name, $new_stream->id, $new_fixture->id);
                                return redirect()->to('/referee/squash/dashboard/fixture/'.$new_fixture->id.'/'.$request->name)->send();

                            }
                            
                        }
                    } else {

                        \Session::flash('error', $request->name." There is an error connecting the stream. Check camera connection. " . $response_curl);
                        $sf = new \Com\Wowza\StreamFile($setup, $application_name, $stream_file_name);
                        $response_sf = $sf->disconnect();

                        if($response_sf->success)
                        {
                            $wowzaApplication = new \Com\Wowza\Application($setup, $application_name);
                            $sf_remove = new \Com\Wowza\StreamFile($setup, $wowzaApplication->getName(), $stream_file_name);
                            $response_remove =$sf_remove->remove();

                            \Session::flash('success', 'Stream has successfully stopped');

                        } else {
                            \Session::flash('error', 'Could not disconnect stream.');

                        }
                        return redirect()->to('/referee/squash/dashboard')->send();

                    }

                    
                } else {
                    \Session::flash('error', $request->name." There is an error starting the stream. Check status of stream connection.");
                }

        } else {
            \Session::flash('error', $request->name." There is an error creating the stream file.");
        }
        
     return redirect()->to('/referee/squash/dashboard')->send();
        
    }

    public function stopStream(Request $request)
    {
        // Stop and disconnect stream
        $fixture = \App\SquashFixture::find($request->fixture_id);
        $stream = \App\SquashStream::find($fixture->squash_stream_id);
        $venue = \App\Venue::find($fixture->venue_id);

        $setup = $this->getSettings();
        $sf_recording = new \Com\Wowza\Recording($setup, $venue->wow_app_name, "_definst_");
        $response_recording = $sf_recording->stop($stream->name . '.stream_source');        

        if($response_recording->success)
        {
            $sf = new \Com\Wowza\StreamFile($setup, $venue->wow_app_name, $stream->name);
            $response_sf = $sf->disconnect();

            if($response_sf->success)
            {
                $wowzaApplication = new \Com\Wowza\Application($setup, $venue->wow_app_name);
                $sf_remove = new \Com\Wowza\StreamFile($setup, $wowzaApplication->getName(), $stream->name);
                $response_remove =$sf_remove->remove();

                \Session::flash('success', 'Stream has successfully stopped');

            } else {
                \Session::flash('error', 'Could not disconnect stream.');

            }
        } else {
            $sf = new \Com\Wowza\StreamFile($setup, $venue->wow_app_name, $stream->name);
            $response_sf = $sf->disconnect();

            if($response_sf->success)
            {
                $wowzaApplication = new \Com\Wowza\Application($setup, $venue->wow_app_name);
                $sf_remove = new \Com\Wowza\StreamFile($setup, $wowzaApplication->getName(), $stream->name);
                $response_remove =$sf_remove->remove();
            } else {
                \Session::flash('error', 'There was an issue stopping the Recording. Contact PaperclipSA to report the issue.');
            }
        }

        // Change stream_type to vod
        $stream->stream_type = "vod";
        $stream->save();
        $rounds = json_decode($fixture->rounds, true);

        foreach($rounds as $round_key=>$value)
        {
            if ( $rounds[$round_key]["winner"] == "" )
            {
                unset($rounds[$round_key]);
            } else {
                foreach($rounds[$round_key]["rallies"] as $rally_key=>$rally_value)
                {   
                    if(($rounds[$round_key]["rallies"][$rally_key]["player_1_score"] == 0))
                    {
                        unset($rounds[$round_key]["rallies"][$rally_key]);
                    }
                }
            }
            
        }
        
        foreach($rounds as $round_key=>$round_value)
        {
            
        }
        
        $fixture->rounds = json_encode($rounds);
        $fixture->save();

        \Session::flash('success', 'Match ended successfully');

        return redirect()->to('/referee/squash/dashboard');
        
    }

    public function viewStream($id, $stream_name)
    {
        $player_1_rounds_won = 0;
        $player_2_rounds_won = 0;

        $fixture = \App\SquashFixture::find($id);
        $stream = \App\SquashStream::find($fixture->squash_stream_id);
        $venue = \App\Venue::find($fixture->venue_id);

        // From SquashFixtures data stored as json string.
        $rounds = json_decode($fixture->rounds, true); // Rounds array given to winning player.
        $round_points = json_decode($fixture->round_points, true); // Keep Record of points per round.
        $points = json_decode($fixture->points, true); // Keep record of all points and time scored.

        $current_round = count($rounds);

        $player_1_round_points = $round_points[$current_round]["player_1"];
        $player_2_round_points = $round_points[$current_round]["player_2"];

        // Loop through rounds to find count for rounds won bt players.
        foreach($rounds as $key=>$round)
        {
            if ($round["winner"] == "player_1")
            {
                $player_1_rounds_won++;
            } else if($round["winner"] == "player_2") {
                $player_2_rounds_won++;
            }
        }

        return view('referee.squash_view_stream', ['stream' => $stream, 'fixture' => $fixture, 'venue' => $venue,
                    'rounds' => $rounds, 'round_points' => $round_points, 'points' => $points,
                    'player_1_rounds_won' => $player_1_rounds_won, 'player_2_rounds_won' => $player_2_rounds_won,
                    'player_1_round_points' => $player_1_round_points, 'player_2_round_points' => $player_2_round_points ]);
    }

    public function startRoundRecording(Request $request)
    {
        //Save round start time.

        $fixture = \App\SquashFixture::find($request->fixture_id);
        $stream = \App\SquashStream::find($fixture->squash_stream_id);
        $venue = \App\Venue::find($fixture->venue_id);
        //Convert to php array to update values.
        $rounds = json_decode($fixture->rounds, true);
        $round_points = json_decode($fixture->round_points, true);
        $points = json_decode($fixture->points, true);

        $current_round = count($rounds);

        $settings = $this->getSettings();

        $sf = new \Com\Wowza\StreamFile($settings, $venue->wow_app_name, $stream->name);
        $response = $sf->get();
        
        $sf_recordings = new \Com\Wowza\Recording($settings, $venue->wow_app_name);
        $response_recordings = $sf_recordings->getAll();

        $recording_status = true;

        if($response_recordings)
        {  
            foreach($response_recordings->streamrecorder as $recorder)
            {
                if($recorder->recorderName == $stream->name.".stream_source") 
                {
                    if($recorder->recorderState == 'Recording in Progress') {
                        $recording_status = false;
                    } else {
                        $recording_status = true;
                    }
                }
            }
        }

        if ($recording_status)
        {
            if($response->name == $stream->name)
            {
                $sf_record = new \Com\Wowza\Recording($settings, $venue->wow_app_name, "_definst_");
    
                $recordName= $stream->name.".stream_source";
                $instanceName= "_definst_";
                $recorderState= "Waiting for stream";
                $defaultRecorder= true;
                $segmentationType= "None";
                $outputPath= "C:/wowza/content/";
                $baseFile= $stream->name.".mp4";
                $fileFormat= "MP4"; // or FLV
                $fileVersionDelegateName= "com.wowza.wms.livestreamrecord.manager.StreamRecorderFileVersionDelegate";
                $fileTemplate= "\${BaseFileName}_\${RecordingStartTime}_\${SegmentNumber}";
                $segmentDuration= "900000";
                $segmentSize= "10485760";
                $segmentSchedule= "";
                $recordData= true;
                $startOnKeyFrame= true;
                $splitOnTcDiscontinuity= false;
                $option= "Append existing file";
                $moveFirstVideoFrameToZero= true;
                $currentSize= 0;
                $currentDuration= 0;
                $recordingStartTime = "";
    
                $response_recording = $sf_record->create($recordName, $instanceName, $recorderState, $defaultRecorder,
                                    $segmentationType, $outputPath, $baseFile, $fileFormat, $fileVersionDelegateName, $fileTemplate,
                                    $segmentDuration, $segmentSize, $segmentSchedule, $recordData, $startOnKeyFrame, $splitOnTcDiscontinuity,
                                    $option, $moveFirstVideoFrameToZero, $currentSize, $currentDuration, $recordingStartTime);
    
                $check_stream_response = $this->checkStreamConncetion($settings, $venue->wow_app_name, $stream->name, $stream->id, $fixture->id);          
                
                if($check_stream_response == 'successfull')
                {
                    \Session::flash('success', 'Game: '.$current_round.' has started.');
                    $rounds[count($rounds)]["round_start_time"] = date('Y-m-d H:i:s');
                    $fixture->rounds = json_encode($rounds);
                    $fixture->round_running = 'running';
                    $fixture->save();

                    $this->startRally($fixture->id);

                    return redirect()->to('/referee/squash/dashboard/fixture/'.$fixture->id.'/'.$stream->name)->send();
                } else {
                    \Session::flash('error', 'The recording could not be started.');
                    return redirect()->to('/referee/squash/dashboard/fixture/'.$fixture->id.'/'.$stream->name)->send();
                }
            } else {
                \Session::flash('error', 'The stream file is missing.');
                return redirect()->to('/referee/squash/dashboard/fixture/'.$fixture->id.'/'.$stream->name)->send();
            }
        } else {
            \Session::flash('error', 'The game has already started. End the game first.');
            return redirect()->to('/referee/squash/dashboard/fixture/'.$fixture->id.'/'.$stream->name)->send();
        }
        
    }

    public function endRoundRecording(Request $request) 
    {
        // End round and stop recording
        // Update next round

        $fixture = \App\SquashFixture::find($request->fixture_id);
        $stream = \App\SquashStream::find($fixture->squash_stream_id);
        $venue = \App\Venue::find($fixture->venue_id);
        //Convert to php array to update values.
        $rounds = json_decode($fixture->rounds, true);
        $round_points = json_decode($fixture->round_points, true);
        $points = json_decode($fixture->points, true);

        $current_round = count($rounds);    

        $settings = $this->getSettings();
        
        $sf_recordings = new \Com\Wowza\Recording($settings, $venue->wow_app_name);
        $response_recordings = $sf_recordings->getAll();

        $recording_status = false;

        if($response_recordings)
        {  
            foreach($response_recordings->streamrecorder as $recorder)
            {
                if($recorder->recorderName == $stream->name.".stream_source") 
                {
                    if($recorder->recorderState == 'Recording in Progress') {
                        $recording_status = true;
                    } else {
                        $recording_status = false;
                    }
                }
            }
        }

        if($recording_status)
        {
            //Check Round Winner for points total.

            //Save round end time to current round

            $sf_recording = new \Com\Wowza\Recording($settings, $venue->wow_app_name, "_definst_");
            $response_recording = $sf_recording->stop($stream->name . '.stream_source');

            if($round_points[$current_round]["player_1"] > $round_points[$current_round]["player_2"])
            {
                $rounds[$current_round]["winner"] = "player_1";
            } else {
                $rounds[$current_round]["winner"] = "player_2";
            }

            $rounds_count = count($rounds);
            $rounds[$rounds_count]["round_end_time"] = date('Y-m-d H:i:s');
            $rounds[$rounds_count + 1]["winner"] = "";
            $rounds[$rounds_count + 1]["rallies"]["1"]["rally_start_time"] = "";
            $rounds[$rounds_count + 1]["rallies"]["1"]["rally_end_time"] = "";
            $rounds[$rounds_count + 1]["rallies"]["1"]["player_1_score"] = 0;
            $rounds[$rounds_count + 1]["rallies"]["1"]["player_2_score"] = 0;

            $round_points[$rounds_count + 1] = ["player_1" => 0, "player_2" => 0];
            // convert back to string for column in database.
            $fixture->round_points = json_encode($round_points); 
            $fixture->rounds = json_encode($rounds);
            $fixture->round_running = 'not_running';
            $fixture->save();

            \Session::flash('success', "Scores have been updated. " . "Game: " . $current_round . " has ended.");

        } else {
            \Session::flash('error', "A game is not running. Start the game first.");
        }
        
        return redirect()->to('/referee/squash/dashboard/fixture/'.$fixture->id.'/'.$stream->name);
    }
}
