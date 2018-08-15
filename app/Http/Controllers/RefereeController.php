<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;

define("WOWZA_HOST","http://192.168.1.69:8087/v2");
define("WOWZA_SERVER_INSTANCE", "_defaultServer_");
define("WOWZA_VHOST_INSTANCE", "_defaultVHost_");
define("WOWZA_USERNAME", env('WOWZA_USERNAME'));
define("WOWZA_PASSWORD", env('WOWZA_PASSWORD'));

class RefereeController extends Controller
{
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

    function checkStreamConncetion($setup, $wowza_app_name, $wowza_streamfile_name, $app_stream_id, $app_fixture_id)
    {
        sleep(5);
        $sf_recordings = new \Com\Wowza\Recording($setup, $wowza_app_name);
        $response_recordings = $sf_recordings->getAll();

        foreach($response_recordings->streamrecorder as $recorder)
        {
            if($recorder->recorderName == $wowza_streamfile_name.".stream_source") {
                if($recorder->recorderState == 'Recording in Progress') {
                    \Session::flash('success', $wowza_streamfile_name." Stream has started.");

                    $stream = \App\Stream::find($app_stream_id);
                    $stream->stream_type = "live";
                    $stream->save();

                    return redirect()->to('/referee/dashboard/fixture/'.$app_fixture_id.'/'.$wowza_streamfile_name)->send();
                } else {

                    \Session::flash('error', $wowza_streamfile_name." Camera is not connected.");
                    $sf_recording = new \Com\Wowza\Recording($setup, $wowza_app_name, "_definst_");
                    $response_recording = $sf_recording->stop($wowza_streamfile_name . '.stream_source');  

                    $sf = new \Com\Wowza\StreamFile($setup, $wowza_app_name, $wowza_streamfile_name);
                    $response_sf = $sf->disconnect();

                    if($response_sf->success)
                    {
                        $wowzaApplication = new \Com\Wowza\Application($setup, $wowza_app_name);
                        $sf_remove = new \Com\Wowza\StreamFile($setup, $wowzaApplication->getName(), $wowza_streamfile_name);
                        $response_remove =$sf_remove->remove();
                    }

                    return redirect()->to('/referee/dashboard/')->send();

                }
            } 
        }                      
    }

// Views

    function index()
    {
        $setup = $this->getSettings();
        $user = Auth::user();
        $referee = \App\Referee::where('user_id', $user->id)->first();
        $venue = \App\Venue::find($referee->venue_id);
        $tomorrow = date("Y-m-d", strtotime('tomorrow'));
        $fixtures = \App\Fixture::where([['venue_id', $venue->id], ['date_time', '>=', date("Y-m-d")], ['date_time', '<',  $tomorrow = date("Y-m-d", strtotime('tomorrow'))]])->orderBy('date_time', 'asc')->get();
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


        $teams = \App\Team::where('active_status', 'active')->orderby('name', 'ASC')->get();

        $response_stream_file_list = false;

        return view('referee.referee_dashboard', ['fixtures' => $fixtures, 'teams' => $teams, 'response_recording' => $response_recording,
                                                'ports' => $ports, 'field_names' => $field_names, 'venue' => $venue]);
    }

    function startStream(Request $request)
    {

        $user = Auth::user();
        $referee = \App\Referee::where('user_id', $user->id)->first();
        $venue = \App\Venue::find($referee->venue_id);
        $tomorrow = date("Y-m-d", strtotime('tomorrow'));
        $fixtures = \App\Fixture::where([['venue_id', $venue->id], ['date_time', '>=', date("Y-m-d")], ['date_time', '<',  $tomorrow = date("Y-m-d", strtotime('tomorrow'))]])->orderBy('date_time', 'asc')->get();
        $venue_live_streams = \App\Venue::find($referee->venue_id)->where('stream_type', 'live');

        $validatedData = $request->validate([
            'team_a' => 'required',
            'team_b' => 'required',
            'camera_port' => 'required'
        ]);

        $uri = "rtsp://". $venue->username .":". $venue->password ."@" . $venue->venue_ip .":" .$request->camera_port. "/h264"; // ex. rtsp://admin:wel0v3mar@196.40.191.23:555/h264
        $stream_file_name = $request->name;
        $application_name = $venue->wow_app_name;
        
        $setup = $this->getSettings();

        // Start Stream on Wowza Streaming Engine.

        // Create Stream File on Wowza server engine
        $wowzaApplication = new \Com\Wowza\Application($setup, $application_name);
        $sf = new \Com\Wowza\StreamFile($setup, $application_name, $stream_file_name);
        $response = $sf->create(array("uri"=>$uri,"streamTimeout"=>1200,"rtspSessionTimeout"=>800), "rtp");
        
        if ($response->success) {
            $response = "Stream File added successfully.";

            $sf_connect = new \Com\Wowza\StreamFile($setup, $application_name, $stream_file_name);
            $response_connection = $sf_connect->connect();

            if ($response_connection->success) {
                $response_connection = "Stream is Live";

                $sf_record = new \Com\Wowza\Recording($setup, $application_name, "_definst_");

                $recordName= $stream_file_name.".stream_source";
                $instanceName= "_definst_";
                $recorderState= "Waiting for stream";
                $defaultRecorder= true;
                $segmentationType= "None";
                $outputPath= 'C:/wowza/content';
                $baseFile= $stream_file_name.".mp4";
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

                if ($response_recording->success)
                {
                    // Condition for adding or updating stream in Database.
                    if($request->stream_id and \App\Stream::find($request->stream_id)) {
                        $this->checkStreamConncetion($this->getsettings(), $venue->wow_app_name, $stream_file_name, $request->stream_id, $request->fixture_id);
                    } else {
                        $new_stream = \App\Stream::create(['name' => $request->name, 'stream_type' => 'none', 'field_port' => $request->camera_port,
                                                           'venue_id' => $venue->id, 'uri' => 'rtsp://'.$venue->username.':'.$venue->password.'@'.$venue->venue_ip.':'.$request->camera_port.'/h264',
                                                           'http_url' => 'http://192.168.1.69:1935/'.$venue->wow_app_name.'/'.$request->name.'.stream_source/playlist.m3u8',
                                                           'storage_location' => "VOD_STORAGE_1"]);
                        if($request->fixture_id)
                        {
                            $update_fixture = \App\Fixture::find($request->fixture_id);
                            $update_fixture->stream_id = $new_stream->id;
                            $update_fixture->save();
                            $updated_fixture = \App\Fixture::find($request->fixture_id);
    
                            $this->checkStreamConncetion($this->getsettings(), $venue->wow_app_name, $new_stream->name, $new_stream->id, $updated_fixture->id);

                        } else {
                            
                            $new_fixture = \App\Fixture::create(['type' => 'match', 'stream_id' => $new_stream->id,
                                                                 'team_a' => $request->team_a, 'team_b' => $request->team_b,
                                                                 'date_time' => $request->date_time, 'venue_id' => $venue->id ]);

                            $this->checkStreamConncetion($this->getsettings(), $venue->wow_app_name, $new_stream->name, $new_stream->id, $new_fixture->id);

                        }
                        
                        
                    }
                } else {
                    \Session::flash('error', $request->name." There is an error starting the recording. Check status of stream connection.");
                }

            } else {
                $response_connection = "Stream is not Live";
                \Session::flash('error', $request->name." There is an error starting the stream. Could not connect stream file.");
            }

        } else {
            //$response = "Could not add stream check stream files name if it already exists.";
            \Session::flash('error', $request->name." There is an error creating the stream file.");
        }
        
     return redirect()->to('/referee/dashboard')->send();   
    }

    public function stopStream(Request $request)
    {
        // Stop and disconnect stream
        $setup = $this->getSettings();
        $sf_recording = new \Com\Wowza\Recording($setup, $request->app_name, "_definst_");
        $response_recording = $sf_recording->stop($request->stream_name . '.stream_source');        

        if($response_recording->success)
        {
            $sf = new \Com\Wowza\StreamFile($setup, $request->app_name, $request->stream_name);
            $response_sf = $sf->disconnect();

            if($response_sf->success)
            {
                $wowzaApplication = new \Com\Wowza\Application($setup, $request->app_name);
                $sf_remove = new \Com\Wowza\StreamFile($setup, $wowzaApplication->getName(), $request->stream_name);
                $response_remove =$sf_remove->remove();

                // // Get Highlights with custom python api

                // $client = new \GuzzleHttp\Client();
                // $highlights = \App\ClipHighlight::where('stream_id', $request->stream_id)->get();

                // foreach($highlights as $cliphighlight)
                // {
                //     if(!$cliphighlight->clip_extracted)
                //     {
                //         ProcessHighlights::dispatch($cliphighlight->stream_id, $cliphighlight->clip_name, $cliphighlight->time);
                //     }
                // }

                \Session::flash('success', 'Stream has successfully stopped');

            } else {
                \Session::flash('error', 'Could not disconnect stream.');

            }
        } else {
            $sf = new \Com\Wowza\StreamFile($setup, $request->app_name, $request->stream_name);
            $response_sf = $sf->disconnect();

            if($response_sf->success)
            {
                $wowzaApplication = new \Com\Wowza\Application($setup, $request->app_name);
                $sf_remove = new \Com\Wowza\StreamFile($setup, $wowzaApplication->getName(), $request->stream_name);
                $response_remove =$sf_remove->remove();
            } else {
                \Session::flash('error', 'There was an issue stopping the Recording. Contact PaperclipSA to report the issue.');
            }
            \Session::flash('error', 'Recording could not be stopped');
        }

        // Change stream_type to vod
        $stream = \App\Stream::find($request->stream_id);
        $stream->stream_type = "vod";
        $stream->save();


        return redirect()->to('/referee/dashboard');
        
    }

    public function reconnectStream()
    {
        $setup = $this->getSettings();


    }

    public function viewStream($id, $stream_name)
    {
        $fixture = \App\Fixture::find($id);
        $stream = \App\Stream::find($fixture->stream_id);
        $venue = \App\Venue::find($fixture->venue_id);

        return view('referee.view_stream', ['stream' => $stream, 'fixture' => $fixture, 'venue' => $venue]);
    }

    public function updateScores(Request $request)
    {
        $fixture = \App\Fixture::find($request->fixture_id);
        $stream = \App\Stream::find($fixture->stream_id);
        $venue = \App\Venue::find($fixture->venue_id);
        
        // Add highlight to database

        $ch = curl_init( 'http://127.0.0.1:8087/v2/servers/_defaultServer_/vhosts/_defaultVHost_/applications/'.$venue->wow_app_name.'/instances/_definst_/incomingstreams/'.$stream->name.'.stream/monitoring/current');
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
            
            $new_highlight = \App\ClipHighlight::create(['stream_id' => $fixture->stream_id,
                                                         'clip_name' => $stream->name .",". gmdate("H:i:s", $array_res['uptime']),
                                                         'time' => gmdate("H:i:s", $array_res['uptime']), 'clip_extracted' => false]);
    
            $highlight_time = $request->highlight_time;
    
            if($fixture->score_tracking)
            {
                $scores = json_decode($fixture->score_tracking, true);
                $current_scores = count($scores);

                $scores[$current_scores + 1]["team_a_score"] = $request->team_a_scored;
                $scores[$current_scores + 1]["team_b_score"] = $request->team_b_scored;
                $scores[$current_scores + 1]["time_scored"] = gmdate("H:i:s", $array_res['uptime']);

                $fixture->score_tracking = json_encode($scores);

            } else {
                $fixture->score_tracking = '{"1": {"team_a_score": "'.$request->team_a_scored.'", "team_b_score": "'.$request->team_b_scored.'", "time_scored": "'.gmdate("H:i:s", $array_res['uptime']).'"}}';
            }

            $fixture->team_a_goals = $request->team_a_scored;
            $fixture->team_b_goals = $request->team_b_scored;
    
            $fixture->save();
    
            \Session::flash('success', "Scores have been updated");
        } else {
            \Session::flash('error', "Scores could not be updated.");

        }
        return redirect()->to('/referee/dashboard/fixture/'.$fixture->id.'/'.$stream->name);

    }
}
