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

   public function update_squash_score(Request $request)
   {
       $fixture = \App\SquashFixture::find($request->fixture_id);

       $round_points = json_decode($fixture->round_points, true);
       $points = json_decode($fixture->points, true);
       $rounds = json_decode($fixture->rounds, true);

       $current_round = count($rounds);

       $player_current_score = $round_points[$current_round][$request->player];

       if ( $request->point == 'add' )
       {
        $round_points[$current_round][$request->player]++;
        $points[$current_round][$request->player][$player_current_score+1] = date('Y-m-d h:i:s');

       } else if ( $request->point == 'subtract' ) {
        $round_points[$current_round][$request->player]--;
        unset($points[$current_round][$request->player][$player_current_score+1]);
       } else {
           return response(500);
       }

       $fixture->round_points = json_encode($round_points);
       $fixture->points = json_encode($points);
       $response = $fixture->save();

       return response()->json(['data' => $response]);
   }

   public function get_squash_score(Request $request)
   {
       $fixture = \App\SquashFixture::find($request->fixture_id);
       $round_points = json_decode($fixture->round_points, true);
       $points = json_decode($fixture->points, true);
       $rounds = json_decode($fixture->rounds, true);

       $current_round = count($rounds);


       return response()->json(['data' => $round_points]);

   }

   public function get_indoor_soccer_score(Request $request)
   {
        $fixture = \App\Fixture::find($request->fixture_id);
        $current_duration = gmdate("H:i:s", $request->current_duration);
        $scores = json_decode($fixture->score_tracking, true);
        $current_score = ["1" => ["team_a_score" => 0, "team_b_score" => 0]];

        foreach($scores as $score_key=>$score)
        {   

            if($current_duration >= $scores[$score_key]["time_scored"]) 
            {
                $current_score[1]["team_a_score"] = $scores[$score_key]["team_a_score"];
                $current_score[1]["team_b_score"] = $scores[$score_key]["team_b_score"];
            }
            
        }

        return response()->json(['data' => $current_score]);
   }

   public function get_squash_score_odv(Request $request)
   {
    $fixture = \App\SquashFixture::find($request->fixture_id);
    $current_duration = gmdate("H:i:s", $request->current_duration);
    $video_duration = gmdate("H:i:s", (int) $request->video_duration);
    $rounds = json_decode($fixture->rounds, true);
    $round_points = json_decode($fixture->round_points, true);
    $current_score = ["1" =>  ["player_1_score" => 0, "player_2_score" => 0, "time_duration" => ""]];
    $rally_times = [];
    $round_start_time = $rounds["1"]["rallies"]["1"]["rally_start_time"];

    $total_round_time = strtotime("00:00:00");

    foreach($rounds as $round)
    {
        
        $single_round_duration = date_diff(date_create($round["round_start_time"]), date_create($round["round_end_time"]))->format('%H:%I:%s');
        $total_round_time += strtotime($single_round_duration) - strtotime("00:00:00");
        
    }

    $total_rounds = count($rounds);

    $total_rallies = 0;

    foreach($rounds as $round)
    {
        $total_rallies += count($round["rallies"]);
    }

    $total_round_time = date('H:i:s', $total_round_time);
    $time_lost = date_diff(date_create($video_duration), date_create($total_round_time))->format('%H:%I:%s');
    $time_to_add = gmdate("H:i:s", (strtotime($time_lost) - strtotime("00:00:00")) / $total_rallies);

    foreach($rounds as $round_key=>$round)
    {
        foreach($round["rallies"] as $rally_key=>$rally)
        {   
            $start_time = date_create($round_start_time);
            $rally_end_time = date_create($rally["rally_start_time"]);
            $rally_duration = date_diff($start_time, $rally_end_time)->format('%H:%I:%s');
            $single_rally_duration = date_diff(date_create($rally["rally_start_time"]), date_create($rally["rally_end_time"]))->format('%H:%I:%s');

            if($rally_key != 1 and $rally["rally_start_time"] != "")
            {
                
                $time = strtotime($rally_times[$round_key][$rally_key -1]) + (strtotime($single_rally_duration) - strtotime('00:00:00')) + (strtotime($time_to_add) - strtotime('00:00:00'));
                $rally_times[$round_key][$rally_key] = date("H:i:s", $time);
                
            } else if($rally["rally_start_time"] != "") {
                if ( $round_key != 1 ) {
                    $last_time = $rally_times[$round_key-1][count($rally_times[$round_key-1])];
                    $time = strtotime($single_rally_duration) + (strtotime($last_time) - strtotime('00:00:00') + (strtotime($time_to_add) - strtotime('00:00:00')));
                    $rally_times[$round_key][$rally_key] = date("H:i:s", $time);

                } else {
                    $time = strtotime($single_rally_duration) + (strtotime($time_to_add) - strtotime('00:00:00'));
                    $rally_times[$round_key][$rally_key] = date('H:i:s', $time);
                }
            } 

        }
        
    }

    foreach($rounds as $round_key=>$round)
    {
        foreach($round["rallies"] as $rally_key=>$rally)
        {   
            if($rally["rally_start_time"] != "")
            {
                if($current_duration >= $rally_times[$round_key][$rally_key]) 
                {
                    $current_score[$round_key]["player_1_score"] = $rally["player_1_score"];
                    $current_score[$round_key]["player_2_score"] = $rally["player_2_score"];
                    $current_score[$round_key]["time_duration"] = $rally_times[$round_key][$rally_key];
                }
            }
        }
    }
    return response()->json(['data' => $time_lost]);
   }
   
}