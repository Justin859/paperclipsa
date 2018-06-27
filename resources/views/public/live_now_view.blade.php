@extends('layouts.app')

@section('header')
<script type="text/javascript" src="//player.wowza.com/player/latest/wowzaplayer.min.js"></script>
<link href="{{asset('node_modules/video.js/dist/video-js.css')}}" rel="stylesheet">
<link href="{{asset('node_modules/videojs-watermark/dist/videojs-watermark.css')}}">
<link href="{{asset('node_modules/videojs-overlay/dist/videojs-overlay.css')}}"> 
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
<br />
<div class="container" align="center">
    @if($stream_available)
    <div class="row" style="background-color: #000000;">
    <div class="col-md-8 offset-md-2" style="padding: 0px;">
        <video id="video-id" class="video-js vjs-default-skin vjs-big-play-centered" width="640" height="268">

        </video>
    </div>
    </div>
    <div class="row">
        <div class="col-md-4 team-container"><h4>{{$fixture->team_a}}</h4></div>
        <div class="col-md-4 score-container"><span id="scoreA">{{$fixture->team_a_goals}}</span> - <span id="scoreB">{{$fixture->team_b_goals}}</span></div>
        <div class="col-md-4 team-container"><h4>{{$fixture->team_b}}</h4></div>
    </div>
    <div class="row">
        <div class="col-sm-12" style="padding: 0px;">
            <h2 class="main-heading" align="left">{{str_replace("_", " ",$live->name)}}</h2>
            <p  class="fixture-date-time" align="left">{{$fixture->date_time}}</p>
            <p class="fixture-venue" align="left"><a class="venue-link" href="#">{{"@" . $current_venue->name}}</a></p>
        </div>
    </div>
    @else
    <div class="row">
        <div class="col-md-9" style="padding: 0px;">
            <div style="width:100%; height:454.35px; padding:0 0 56.25% 0 0; background-color:black; color:white;">Stream Not Purchased</div>        
        </div>
    </div>
    <br />
    <div class="row">
        <div class="col-sm-12" style="padding: 0px;">
            <h2 class="main-heading" align="left">{{str_replace("_", " ",$live->name)}}</h2>
            <p  class="fixture-date-time" align="left">{{$fixture->date_time}}</p>
            <p class="fixture-venue" align="left"><a class="venue-link" href="#">{{"@" . $current_venue->name}}</a></p>
        </div>
    </div>
    <div class="row" align="right">
        <div class="col-md-9" style="padding: 0px;">
            <div class="btn-group" role="group" aria-label="User Actions">
            @if($account_balance)
                <button id="purchaseButton" class="btn btn-outline-warning" data-toggle="modal" data-target="#areYouSure">Use Credits&nbsp;&nbsp;<span class="fas fa-money-bill"></span></button>
            @else
                <button id="purchaseButton" class="btn btn-outline-warning disabled" aria-disabled="true">Use Credits&nbsp;&nbsp;<span class="fas fa-money-bill"></span></button>           
            @endif    
                <button id="purchaseButton" class="btn btn-outline-warning">Buy Credits&nbsp;&nbsp;<span class="fas fa-credit-card"></span></button>
            </div>
        </div>
    </div>
    @endif
    </br>
    <div class="row">
        <div class="col-md-12">
            <h2 class="main-heading" align="left">More Live Streams</h2>
            <hr />
        </div>
    </div>
    <div class="row d-flex justify-content-center">
            @foreach($more_live_streams as $live_item)
            @if($live_item->stream_type == "live")
            <div class="col-xs-2 col-md-3 vod-item vod-items">
                <a href="/live-now/{{$live_item->id}}/{{$live_item->name}}/" class="js-item">
                    <img src="{{ asset('images/livestream_1.png')}}" height="auto" width="100%" />
                    <i class="far fa-play-circle play-icon" style="display:none;"></i>
                    {{str_replace("_", " ", $live_item->name)}}
                </a>
            </div>
            @endif
        @endforeach
    </div>

</div>
<div id="msg"></div>

@endsection
@section('modal')
<!-- Modal for purchase request -->
<div id="areYouSure" class="modal" tabindex="-1" role="dialog">
    <form method="post" action="/live-now/{{$live->id}}/{{$live->name}}">
    {{ csrf_field() }}
    <input type="number" name="vod_id" hidden="true" value="{{$live->id}}" readonly/>
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Use Tokens To Access Video On Demand</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Are You Sure ?</p>
                </div>
                <div class="modal-footer">
                    <input type="submit" class="btn btn-warning" value="Yes" />
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </form>
</div>
<!-- End of Modal -->
@endsection
@section('scripts')

<script src="{{asset('node_modules/video.js/dist/video.js')}}"></script>
<script src="{{asset('node_modules/videojs-contrib-hls/dist/videojs-contrib-hls.min.js')}}"></script>
<script src="{{asset('node_modules/videojs-dynamic-overlay/dist/videojs-newoverlay.min.js')}}"></script>
<script src="{{asset('node_modules/videojs-watermark/dist/videojs-watermark.min.js')}}"></script>

<?php $ip_address = \App\GlobalSetting::find(1)->wowza_server_ip; 
      $app_name = $current_venue->wow_app_name;
?>

<script type="text/javascript">
$( document ).ready(function() {
    var app_name = "<?php echo $app_name ?>"
    var stream_name = "<?php echo $live->name ?>"

    var video = videojs('video-id', {"controls": true, "autoplay": true, "fluid": true, "preload": "auto"}).ready(function() {
        var player = this;

        player.src({
        src: 'http://192.168.0.69:1935/'+app_name+'/'+stream_name+'.stream_source/playlist.m3u8',
        type: 'application/x-mpegURL',
        withCredentials: false
        });

        player.newoverlay({
        contentOfOverlay:"<a href='#'><img class='img-fluid' height='auto' width='50px' src='http://paperclipsa.local/images/logo_2 PNG.png'></a>",
        changeDuration:10000
        });

        player.on('ended', function() {
            alert('video is done!');
        });

    });

    
    
    // WowzaPlayer.create('playerElement',
    // {
    // "license":"PLAY1-h6N7A-zkt3G-We8UE-Dyxcn-G4Pnb",
    // "title":"",
    // "description":"",
    // "sourceURL":"http%3A%2F%2F192.168.0.11%3A1935%2F" + encodeURI(app_name) + "%2F"+ encodeURI(stream_name) +".stream_source%2Fplaylist.m3u8",
    // "autoPlay":false,
    // "volume":"75",
    // "mute":false,
    // "loop":false,
    // "audioOnly":false,
    // "uiShowQuickRewind":true,
    // "uiQuickRewindSeconds":"30"
    // }
    
    // );

    $('#areYouSure').on('shown.bs.modal', function () {
        $('#purchaseButton').trigger('focus');
    });
    function get_scores(){
        $.ajax({
            type: "GET",
            url: "/api/fixture?fixture_id=<?php echo $fixture->id ?>",
            async: false,
            success: function(response) {
                console.log(response);
                $("#scoreA").html(response.data.team_a_goals);
                $("#scoreB").html(response.data.team_b_goals);
                setTimeout(function(){get_scores();}, 10000);
            }
        });  
    }    
    get_scores();
        
});

</script>
<style>
.vjs-emre{
    z-index:9999;
    color:black;
    position:absolute;
    top: 20px;
    right:20px;
    opacity: 0.5;
  }
  .vjs-control{
    z-index:9999;
  }

</style>
@endsection