@extends('layouts.app')

@section('header')
<script type="text/javascript" src="//player.wowza.com/player/latest/wowzaplayer.min.js"></script>
@endsection

@section('styles')
<style>
    .score-container
    {
        padding: 20px;
        text-align: center;
        background-color: #FFCC00;
        font-size: 25px;
        width: 100%;
    }
    .team-container
    {
        color: #ffffff;
        padding: 20px;
        width: 100%;
        background-color: #505050;
    }
    .fixture-date-time, .fixture-venue
    {
        color:#FFCC00;
    }
    hr
    {
        background-color: #505050;
    }
    .venue-link
    {
        color:#FFCC00;
    }
    .venue-link:hover {
        color:#FFFFFF;
    }
</style>
@endsection

@section('content')
<div class="container" align="center">
    @if($stream_available)
    <div class="row" style="background-color: #000000;">
        <div class="col-md-8 offset-md-2" style="padding: 0px;">
            <div id="playerElement" style="width:100%; height:0; padding:0 0 56.25% 0"></div>
            <img src="{{asset('images/vid_logo_1.jpg')}}" class="img-fluid" height="auto" width="50" style="position: absolute; top:10px; right: 10px;" />        
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12" style="padding: 0px;">
        <h2 class="main-heading" align="left">{{$session->age_group_id}}</h2>
            <p  class="fixture-date-time" align="left">{{$session->date_time}}</p>
            <p class="session-venue" align="left"><a class="venue-link" href="/channel/{{$current_venue->id}}/{{$current_venue->name}}">{{"@" . $current_venue->name}}</a></p>
        </div>
    </div>
    @else
    <div class="row" style="background-color: #000000;">
        <div class="col-md-8 offset-md-2" style="padding: 0px;">
            <div style="width:100%; height:454.35px; padding:0 0 56.25% 0 0; background-color:black; color:white;">
                <div class="btn-group" style="postion:absolute; top:45%;" role="group" aria-label="User Actions">
                    <a href="/subscription/checkout" id="subscribeButton" class="btn btn-outline-warning">Subscribe&nbsp;&nbsp;<i class="fas fa-credit-card"></i>&nbsp;&nbsp;<i class="fas fa-arrows-alt-h"></i> &nbsp;&nbsp;<i class="fas fa-id-card"></i></a>
                </div>
            </div>        
        </div>
    </div>
    <br />
    <div class="row">
        <div class="col-sm-12" style="padding: 0px;">
        <h2 class="main-heading" align="left">{{$session->age_group_id}}</h2>
            <p  class="fixture-date-time" align="left">{{$session->date_time}}</p>
            <p class="session-venue" align="left"><a class="venue-link" href="/channel/{{$current_venue->id}}/{{$current_venue->name}}">{{"@" . $current_venue->name}}</a></p>
        </div>
    </div>
    @endif
    @if($more_live_streams->count())
    </br>
    <div class="row">
        <div class="col-md-12">
            <h2 class="main-heading" align="left">More Live Streams</h2>
            <hr />
        </div>
    </div>
    <div class="row d-flex justify-content-center">
            @foreach($more_live_streams as $live_item)
            <div class="col-xs-2 col-md-3 vod-item vod-items">
                <a href="/live-now/soccer-schools/{{$live_item->id}}/{{$live_item->name}}/" class="js-item">
                    <img src="{{ asset('images/livestream_1.png')}}" height="auto" width="100%" />
                    <i class="far fa-play-circle play-icon" style="display:none;"></i>
                </a>
                <p align="left" style="color: #ffffff; margin: 5px;">{{str_replace("_", " ", $live_item->name)}}</p>
                <p align="left" style="color: #FFCC00; margin: 5px;">@<a href="/channel/{{$live_item->venue_id}}" style="color: #FFCC00;">{{\App\Venue::find($live_item->venue_id)->name}}</a></p>
            </div>
            @endforeach
    </div>

    @endif

</div>

@endsection
@section('modal')
<!-- Modal for purchase request -->

<!-- End of Modal -->
<!-- Modal for buyying stream -->

<!-- End of Modal -->
@endsection
@section('scripts')
<?php $ip_address = \App\GlobalSetting::find(1)->wowza_server_ip; 
      $app_name = $current_venue->wow_app_name;
?>

<script type="text/javascript">
$( document ).ready(function() {
    WowzaPlayer.create('playerElement',
    {
    "license":"PLAY1-h6N7A-zkt3G-We8UE-Dyxcn-G4Pnb",
    "title":"",
    "description":"",
    "sourceURL":"http%3A%2F%2F192.168.1.69%3A1935%2F{{$app_name}}%2Fstream%3A"+encodeURI("<?php echo $live->name ?>")+".stream_source%2Fplaylist.m3u8",
    "autoPlay":false,
    "useFlash": true,
    "uiShowDurationVsTimeRemaining": true,
    "volume":"75",
    "mute":false,
    "loop":false,
    "audioOnly":false,
    "uiShowQuickRewind":true,
    "uiQuickRewindSeconds":"30"
    }
    
    );

});

</script>
@endsection