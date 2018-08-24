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

class CoachController extends Controller
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

    function checkStreamConncetion($setup, $wowza_app_name, $wowza_streamfile_name, $app_stream_id, $app_ss_session_id)
    {
        sleep(5);
        $sf_recordings = new \Com\Wowza\Recording($setup, $wowza_app_name);
        $response_recordings = $sf_recordings->getAll();

        foreach($response_recordings->streamrecorder as $recorder)
        {
            if($recorder->recorderName == $wowza_streamfile_name.".stream_source") {
                if($recorder->recorderState == 'Recording in Progress') {
                    \Session::flash('success', $wowza_streamfile_name." Stream has started.");

                    $stream = \App\SoccerSchoolsStream::find($app_stream_id);
                    $stream->stream_type = "live";
                    $stream->save();

                    return redirect()->to('/coach/dashboard/session/'.$app_ss_session_id.'/'.$wowza_streamfile_name)->send();
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

                    return redirect()->to('/coach/dashboard/')->send();

                }
            } 
        }                      
    }

    // Views
    
    function index()
    {
        $setup = $this->getSettings();
        $user = Auth::user();
        $coach = \App\Coach::where('user_id', $user->id)->first();
        $venue = \App\Venue::find($coach->venue_id);
        $tomorrow = date("Y-m-d", strtotime('tomorrow'));
        $ss_sessions = \App\SoccerSchoolsSession::where([['venue_id', $venue->id], ['date_time', '>=', date("Y-m-d")], ['date_time', '<',  $tomorrow = date("Y-m-d", strtotime('tomorrow'))]])->orderBy('date_time', 'asc')->get();
        $fields = \App\Field::where('venue_id', $venue->id)->first();
        $ports = explode(",", $fields->ports);
        $field_names = explode(",", $fields->port_names);
        
        $sf_recording = new \Com\Wowza\Recording($setup, $venue->wow_app_name);
        $response_recording = false;

        if($sf_recording)
        {
            $response_recording = $sf_recording->getAll();
        }

        $ss_age_groups = \App\SoccerSchoolsAgeGroup::where('active_status', 'active')->orderby('name', 'ASC')->get();

        $response_stream_file_list = false;

        return view('coach.coach_dashboard', ['ss_sessions' => $ss_sessions, 'ss_age_groups' => $ss_age_groups, 'response_recording' => $response_recording,
                                                'ports' => $ports, 'field_names' => $field_names, 'venue' => $venue]);
    }

    function startStream(Request $request)
    {

        $user = Auth::user();
        $coach = \App\Coach::where('user_id', $user->id)->first();
        $venue = \App\Venue::find($coach->venue_id);
        $tomorrow = date("Y-m-d", strtotime('tomorrow'));
        $sessions = \App\SoccerSchoolsSession::where([['venue_id', $venue->id], ['date_time', '>=', date("Y-m-d")], ['date_time', '<',  $tomorrow = date("Y-m-d", strtotime('tomorrow'))]])->orderBy('date_time', 'asc')->get();
        $venue_live_streams = \App\Venue::find($coach->venue_id)->where('stream_type', 'live');

        $validatedData = $request->validate([
            'ss_age_group' => 'required',
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
                    if($request->ss_stream_id and \App\SoccerSchoolsStream::find($request->ss_stream_id)) {
                        $this->checkStreamConncetion($this->getsettings(), $venue->wow_app_name, $stream_file_name, $request->ss_stream_id, $request->ss_stream_id);
                    } else {
                        $new_stream = \App\SoccerSchoolsStream::create(['name' => $request->name, 'stream_type' => 'none', 'camera_port' => $request->camera_port,
                                                           'venue_id' => $venue->id, 'uri' => 'rtsp://'.$venue->username.':'.$venue->password.'@'.$venue->venue_ip.':'.$request->camera_port.'/h264',
                                                           'http_url' => 'http://192.168.1.69:1935/'.$venue->wow_app_name.'/'.$request->name.'.stream_source/playlist.m3u8',
                                                           'storage_location' => "VOD_STORAGE_1"]);
                        if($request->ss_stream_id)
                        {
                            $update_session = \App\SoccerSchoolsSession::find($request->ss_stream_id);
                            $update_session->ss_stream_id = $new_stream->id;
                            $update_session->save();
                            $updated_session = \App\SoccerSchoolsSession::find($request->ss_stream_id);
    
                            $this->checkStreamConncetion($this->getsettings(), $venue->wow_app_name, $new_stream->name, $new_stream->id, $updated_session->id);

                        } else {
                            
                            $new_session= \App\SoccerSchoolsSession::create(['ss_stream_id' => $new_stream->id,
                                                                 'age_group_id' => $request->ss_age_group, 'coach_id' => $coach->id,
                                                                 'date_time' => $request->date_time, 'venue_id' => $venue->id ]);

                            $this->checkStreamConncetion($this->getsettings(), $venue->wow_app_name, $new_stream->name, $new_stream->id, $new_session->id);

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
        
     return redirect()->to('/coach/dashboard')->send();   
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
        $stream = \App\SoccerSchoolsStream::find($request->ss_stream_id);
        $stream->stream_type = "vod";
        $stream->save();


        return redirect()->to('/coach/dashboard');
        
    }

    public function viewStream($id, $stream_name)
    {
        $session = \App\SoccerSchoolsSession::find($id);
        $stream = \App\SoccerSchoolsStream::find($session->ss_stream_id);
        $venue = \App\Venue::find($session->venue_id);

        return view('coach.coach_stream_view', ['stream' => $stream, 'session' => $session, 'venue' => $venue]);
    }

    public function edit_session(Request $request)
    {

        $session = \App\SoccerSchoolsSession::find($request->ss_session_id);
        $stream = \App\SoccerSchoolsStream::find($session->ss_stream_id);
        $venue = \App\Venue::find($stream->venue_id);
        $age_group = \App\SoccerSchoolsAgeGroup::find($request->age_group_id)->name;

        $updated_session = $session->update([
            'age_group_id' => $age_group,
        ]);

        $stream_name = str_replace(" ", "_", $age_group) . "_" . str_replace("-", "_", $session->date_time); // Change before ":"
        $stream_name_comp = str_replace(" ", "_", str_replace(":", "-", $stream_name));
        $updated_stream = $stream->update([
            'name' => $stream_name_comp,
            'camera_port' => $request->camera_port,
            'uri' => 'rtsp://admin:wel0v3mar@reitvlienoip.ddns.net:' . $request->camera_port . "/h264",
            'http_url' => "http://192.168.1.69:1935/" . $venue->wow_app_name . "/" . $stream_name_comp . ".stream_source/playlist.m3u8",
        ]);

        \Session::flash('success', 'Session has been updated.');

        return redirect()->back();
    }

}
