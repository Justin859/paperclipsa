<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

define("WOWZA_HOST","http://192.168.0.21:8087/v2");
define("WOWZA_SERVER_INSTANCE", "_defaultServer_");
define("WOWZA_VHOST_INSTANCE", "_defaultVHost_");
define("WOWZA_USERNAME", "Justin");
define("WOWZA_PASSWORD", "Monkeywrench@1");

class AjaxController extends Controller {

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

   public function index()
   {
      $msg = "This is a simple message.";
      return response()->json(array('msg'=> $msg), 200);
   }

   public function get_scores(Request $request)
   {
        $fixture = \App\Fixture::find($request->fixture_id);

        return response()->json([
            'data' => $fixture->toArray(),
        ]);
   }

   public function get_streamfile_status(Request $request)
   {

    $setup = $this->getSettings();

    // $sf_recording = new \Com\Wowza\Recording($setup, $event->applicationName);
    $sf_streamfiles = new \Com\Wowza\StreamFile($setup, 'Fast_Sport_Fusion_Old_Parks');
    $wowzaApplication = new \Com\Wowza\Application($setup, 'Fast_Sport_Fusion_Old_Parks');
    $sf = new \Com\Wowza\Statistics($setup);

    // $response_recording = false;
    $response_streamfiles = false;
    $response_incomming = false;

    // if($sf_recording)
    // {
    //     $response_recording = $sf_recording->getAll();
    // }
    if($sf_streamfiles)
    {
        $response_streamfiles = $sf_streamfiles->getAll();
    }
    if($wowzaApplication)
    {
        $response_incomming = $sf->getIncomingApplicationStatistics($wowzaApplication, 'testStream.stream', $appInstance = '_definst_');
    }

    return response()->json([
        'data' => $response_incomming,
    ]);

   }
   
}