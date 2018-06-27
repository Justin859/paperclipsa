<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;

define("WOWZA_HOST","http://192.168.0.6:8087/v2");
define("WOWZA_SERVER_INSTANCE", "_defaultServer_");
define("WOWZA_VHOST_INSTANCE", "_defaultVHost_");
define("WOWZA_USERNAME", env('WOWZA_USERNAME'));
define("WOWZA_PASSWORD", env('WOWZA_PASSWORD'));

class SuperuserController extends Controller
{
    // Methods

    function getSettings()
    {
        // Settings for wowza connection 

        $response = new \Com\Wowza\Entities\Application\Helpers\Settings();

        $response->setHost(WOWZA_HOST);
        $response->setUsername(WOWZA_USERNAME);
        $response->setPassword(WOWZA_PASSWORD);

        return $response;
    }

    function start_recordings($source_name, $applicationName)
    {
        $setup = $this->getSettings();
        $sf_record = new \Com\Wowza\Recording($setup, $applicationName, "_definst_");

                $recordName= $source_name."_source";
                $instanceName= "_definst_";
                $recorderState= "Waiting for stream";
                $defaultRecorder= true;
                $segmentationType= "None";
                $outputPath= "C:/Program Files (x86)/Wowza Media Systems/Wowza Streaming Engine 4.7.5/content/";
                $baseFile= $source_name."_source.mp4";
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

    }

    function generate_voucher_code()
    {
        $chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $res = "";

        do {

            for ($i = 0; $i < 10; $i++) {
                $res .= $chars[mt_rand(0, strlen($chars)-1)];
            }

        } while(\App\Voucher::where('voucher_key', $res)->first() !== null);
        
        return $res;
    }

    // Views

    public function index()
    {
        $channels = \App\Venue::where('active_status', 'active')->get();
        $events = \App\Event::all();

        $setup = $this->getSettings();

        return view('superuser.dashboard', ['channels' => $channels, 'events' => $events]);
    }

    public function startStream(Request $request)
    {
        $setup = $this->getSettings();

        $camera_array = [$request->cameraOne, $request->cameraTwo, $request->cameraThree, $request->cameraFour, $request->cameraFive, $request->cameraSix];
        $camera_str = "";

        foreach($camera_array as $camera)
        {
            if($camera != "")
            {
                $camera_str = $camera_str . $camera . ",";
            }
            
        }

        $new_event = \App\Event::create(['venue_id' => $request->venue_id, 'eventName' => $request->eventName, 'applicationName' => $request->applicationName, 'cameras' => substr($camera_str, 0, -1)]);
        if($new_event)
        {
            \Session::flash('success', $request->name." Event Created Successfully.");

            $cameras = explode(",", $new_event->cameras);

            foreach($cameras as $camera)
            {
                $this->start_recordings($camera, $new_event->applicationName);                
            }


        } else {
            \Session::flash('error', $request->name." There is an error creating the event");
        }

        return redirect()->to('/superuser/dashboard/event/'.$new_event->id);

    }

    public function view_event($id)
    {
        $event = \App\Event::find($id);
        $cameras = explode(",", $event->cameras);

        $response_recording = false;

        $setup = $this->getSettings();

        $sf_recording = new \Com\Wowza\Recording($setup, $event->applicationName);
        if($sf_recording)
        {
            $response_recording = $sf_recording->getAll();
        }

        return view('superuser.view_event', ['event' => $event, 'cameras' => $cameras, 'response_recording' => $response_recording, 'sf_recording' => $sf_recording]);
    }

    public function restart_recording(Request $request)
    {
        $event = \App\Event::find($request->event_id);
        $cameras = explode(",", $event->cameras);
        $setup = $this->getSettings();
        $sf_recording = new \Com\Wowza\Recording($setup, $event->applicationName);
        
        if($sf_recording)
        {
            $response_recording = $sf_recording->getAll();
        }


        foreach($cameras as $camera)
        {
            $this->start_recordings($camera, $event->applicationName);                
        }

        return redirect()->to('/superuser/dashboard/event/'.$event->id);

    }

    public function create_vouchers()
    {
        $channels = \App\Venue::where('active_status', 'active')->get();

        return view('superuser.create_vouchers', ['channels' => $channels]);
    }

    public function download_vouchers()
    {
        $venues = \App\Venue::where('active_status', 'active')->get();

        return view('superuser.download_vouchers', ['venues' => $venues]);
    }

    public function submit_vouchers(Request $request)
    {

        $venue = \App\Venue::find($request->venue_id);

        for ($i = 0; $i < $request->number_of_vouchers; $i++) {

            $new_voucher_key = $this->generate_voucher_code();

            \App\Voucher::create(['voucher_key' => $new_voucher_key, 'venue_id' => $request->venue_id, 'used' => false, 'points_value' => $request->points_value]);
        }

        \Session::flash('success', " ".$request->number_of_vouchers." created for, ". $venue->name);

        return redirect()->to('/superuser/dashboard/create-vouchers');
    }
}
