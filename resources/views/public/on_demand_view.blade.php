@extends('layouts.app')

@section('header')
<!-- <script type="text/javascript" src="//player.wowza.com/player/latest/wowzaplayer.min.js"></script> -->

<!-- If you'd like to support IE8 (for Video.js versions prior to v7) -->
<link href="{{asset('node_modules/video.js/dist/video-js.css')}}" rel="stylesheet">
<link href="{{asset('node_modules/videojs-watermark/dist/videojs-watermark.css')}}">
<link href="{{asset('node_modules/videojs-overlay/dist/videojs-overlay.css')}}"> 
  
@endsection

@section('styles')
<style>
    .score-container-overlay
    {
        padding: 0.6em;
        text-align: center;
        background-color: #FFCC00;
        font-size: 0.8em;
        font-weight: bold;
        width: 100%;
    }
    .team-container-overlay
    {
        color: #ffffff;
        padding: 0.6em;
        width: 100%;
        font-size: 0.8em;
        background-color: #505050;
    }

    .score-container
    {
        padding: 20px;
        text-align: center;
        background-color: #FFCC00;
        font-size: 24px;
        width: 100%;
    }
    .team-container
    {
        color: #ffffff;
        padding: 20px;
        width: 100%;
        font-size: 24px;
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
    /* .video-js 
    {
        width: 100% !important;
        height: auto !important;
        z-index: 9999;
    } */

.vjs-emre{
    z-index:9999;
    color:black;
    position:absolute;
    top: 0px;
    width: 100%;
    height: 10%;
  }
  .vjs-control{
    z-index:9999;
  }

  .vjs-emre a
  {
    z-index:9999;
    color:black;
    position:absolute;
    right:20px;
    width: 20%;
    opacity: 0.5; 
    top: 15px;

  }

.vjs-emre #score-overlay
  {
    z-index:9999;
    color:black;
    position:absolute;
    left:40%;
    width: 20%;
    top: 0px;
  }

</style>
@endsection

@section('content')
<div class="container" align="center">
    @if($stream_available)
    <div class="row" style="background-color: #000000;">
        <div class="col-md-8 offset-md-2" style="padding: 0px;">
        <!-- <video src="https://192.168.1.69:1935/VOD_STORAGE_2/mp4:{{$vod->name}}.mp4/playlist.m3u8" data-viblast-key="3234ee02-940e-4ee4-8a26-866bc45b4363" controls width="100%" height="auto"></video> -->
        <video id="video-id" class="video-js vjs-default-skin vjs-big-play-centered" width="640" height="268">

        </video>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4 team-container"><h4>{{$fixture->team_a}}</h4></div>
        <div class="col-md-4 score-container"><span id="teamAscoreID">{{$fixture->team_a_goals}}</span> - <span id="teamBscoreID">{{$fixture->team_b_goals}}<span></div>
        <div class="col-md-4 team-container"><h4>{{$fixture->team_b}}</h4></div>
    </div>
    <div class="row">
        <div class="col-sm-12" style="padding: 0px;">
            <h2 class="main-heading" align="left">{{str_replace("_", " ",$vod->name)}}</h2>
            <p  class="fixture-date-time" align="left">{{$fixture->date_time}}</p>
            <p class="fixture-venue" align="left"><a class="venue-link" href="#">{{"@" . $current_venue->name}}</a></p>
        </div>
    </div>
    @else
    <div class="row" style="background-color: #000000;">
        <div class="col-md-8 offset-md-2" style="padding: 0px;">
            <div style="width:100%; height:454.35px; padding:0 0 56.25% 0 0; background-color:black; color:white;">
                <div class="btn-group" style="postion:absolute; top:45%;" role="group" aria-label="User Actions">
                @if($account_balance)
                    <button id="purchaseButton" class="btn btn-outline-warning" data-toggle="modal" data-target="#areYouSure">Use Credits&nbsp;&nbsp;<span class="fas fa-money-bill"></span></button>
                @else
                    <button id="purchaseButton" class="btn btn-outline-warning disabled" aria-disabled="true">Use Credits&nbsp;&nbsp;<span class="fas fa-money-bill"></span></button>           
                @endif    
                    <button id="buyButton" class="btn btn-outline-warning" data-toggle="modal" data-target="#buyStream">Buy Credits&nbsp;&nbsp;<span class="fas fa-credit-card"></span></button>
                </div>
            </div>        
        </div>
    </div>
    <br />
    <div class="row">
        <div class="col-sm-9" style="padding: 0px;">
            <h2 class="main-heading" align="left">{{str_replace("_", " ",$vod->name)}}</h2>
            <p  class="fixture-date-time" align="left">{{$fixture->date_time}}</p>
            <p class="fixture-venue" align="left"><a class="venue-link" href="#">{{"@" . $current_venue->name}}</a></p>
        </div>
    </div>
    @endif
    </br>
    <div class="row">
        <div class="col-md-12">
            <h2 class="main-heading" align="left">More Videos From This Channel</h2>
            <hr />
        </div>
    </div>
    <div class="mx-auto px-1">
        <div class="row d-flex justify-content-center">
            @foreach($vods_from_venue as $vod_item)
                @if($vod->stream_type == "vod")
                <div class="col-xs-12 col-md-3 vod-item">
                    <div class="vod-wrapper">
                    <a href="/on-demand/indoor-soccer/{{$vod_item->id}}/{{$vod_item->name}}/" class="js-item">
                        <img src="{{ asset('images/vod_1.png')}}" height="auto" width="100%" />
                        <i class="far fa-play-circle play-icon" style="display:none;"></i>
                    </a>
                    @if($vod_item->duration)
                    <span class="video-duration">{{gmdate("H:i:s" ,$vod_item->duration)}}</span>
                    @endif
                    </div>
                    <p align="left" style="color: #ffffff; margin: 0px;">{{ucwords(\App\Fixture::where('stream_id', $vod_item->id)->first()->team_a)}} VS {{ucwords(\App\Fixture::where('stream_id', $vod_item->id)->first()->team_b)}}</p>
                    <p align="left" style="color: #D3D3D3; margin: 0px;">{{\App\Fixture::where('stream_id', $vod_item->id)->first()->date_time}}</p>
                    <p align="left" style="color: #FFCC00; margin: 0px; margin-bottom: 5px;">@<a href="/channel/{{$vod->venue_id}}/{{\App\Venue::find($vod_item->venue_id)->name}}" style="color: #FFCC00;">{{\App\Venue::find($vod_item->venue_id)->name}}</a></p>
                </div>
                @endif
            @endforeach
        </div>
    </div>
</div>

</div>

@endsection
@section('modal')
<!-- Modal for purchase request -->
<div id="areYouSure" class="modal" tabindex="-1" role="dialog">
    <form method="post" action="/on-demand/indoor-soccer/purchase">
    {{ csrf_field() }}
    <input type="number" name="vod_id" hidden="true" value="{{$vod->id}}" readonly/>
    <input type="text" name="vod_name" hidden="true" value="{{$vod->name}}" readonly/>
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Use Tokens To Access Video On Demand</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p><strong>{{$pricing = \App\Pricing::find(1)->match}}</strong> Credits will be deducted</p>
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
<!-- Modal for buyying stream -->
<div id="buyStream" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Purchase Subscription or Credits</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p><strong>In order to gain access to on-demand videos and live streams</strong></p>
                <p>You now have the option to pay a monthly subscription for a single venue(R30.00/mo) or full Access(R60.00/mo) to all selected venues.</p>
                <p>Buying credits and using tokens to access videos is still an available option at <strong>5</strong> credits per video.</p>
            </div>
            <div class="modal-footer">
                <a href="/subscription/checkout" class="btn btn-info">Purchase Subscription&nbsp;&nbsp;<span class="fas fa-credit-card"></span></a>
                <a href="/buy" class="btn btn-warning">Purchase Credits&nbsp;&nbsp;<span class="fas fa-coins"></span></a>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- End of Modal -->
@endsection
@section('scripts')

<script src="{{asset('node_modules/video.js/dist/video.js')}}"></script>
<script src="{{asset('node_modules/videojs-contrib-hls/dist/videojs-contrib-hls.min.js')}}"></script>
<script src="{{asset('node_modules/videojs-dynamic-overlay/dist/videojs-newoverlay.min.js')}}"></script>
<script src="{{asset('node_modules/videojs-watermark/dist/videojs-watermark.min.js')}}"></script>
<script src="https://cdn.jsdelivr.net/npm/videojs-flash@2/dist/videojs-flash.min.js"></script>

<?php 
        function shorten_name($name)
        {
            $split_name = explode(" ", $name);
            $short_name = "";
            if(count($split_name) > 1)
            {
                foreach($split_name as $first_letter)
                {
                    $short_name .= $first_letter[0];
                }
            } else {
                $short_name = substr($name, 0, 3);
            }
            return $short_name;
        }
        $team_a_short = shorten_name($fixture->team_a);
        $team_b_short = shorten_name($fixture->team_b);
    ?>

@if($fixture->score_tracking)

<script>

$( document ).ready(function() {
    $('#areYouSure').on('shown.bs.modal', function () {
        $('#purchaseButton').trigger('focus');
    });

    $('#buyStream').on('shown.bs.modal', function () {
        $('#buyButton').trigger('show');
    });

    var video_name = "<?php echo $vod->name ?>";
    var storage_location = "<?php echo $vod->storage_location ?>";
    var player = videojs('video-id', {"controls": true, "autoplay": true, "fluid": true, "preload": "auto"});

    player.src({
    src: encodeURI('http://192.168.1.69:1935/'+storage_location+'/mp4:'+video_name+'.mp4/playlist.m3u8'),
    type: 'application/x-mpegURL',
    withCredentials: false
    });

    player.newoverlay({
    contentOfOverlay:"<a href='https://supabets.co.za/Sport/Default.aspx' target='_blank'><img class='img-fluid' height='auto' width='50px' src='https://www.paperclipsa.co.za/storage/adverts/images/S6DqtnInVNdbKhm9ppSmSPyvQ7cIw7IOauQ3DqBU.jpeg'></a>"
    + "<div id='score-overlay'>"
    + "<div class='row'>"
    + "<div class='col-4 team-container-overlay'><span>{{$team_a_short}}</span></div>"
    + "<div class='col-4 score-container-overlay'><span id='teamAscoreOverlayID'>{{$fixture->team_a_goals}}</span> - <span id='teamBscoreOverlayID'>{{$fixture->team_b_goals}}<span></div>"
    + "<div class='col-4 team-container-overlay'><span>{{$team_b_short}}</span></div>"
    + "</div>"
    +"</div>",
    changeDuration:10000
    });

     var waitForEl = function (selector, callback, maxTimes = false) {
      if (jQuery(selector).length) {
        callback();
      } else {
        if (maxTimes === false || maxTimes > 0) {
          (maxTimes != false) && maxTimes-- ;
          setTimeout(function () {
            waitForEl(selector, callback, maxTimes);
          }, 100);
        }
      }
    };

    waitForEl('#teamAscoreOverlayID', function start_call() {
        function get_duration(){
        $.ajax({
            type: "POST",
            url: "/api/get-indoor-soccer-score?fixture_id=<?php echo $fixture->id ?>&current_duration=" + player.currentTime() + "&video_duration=" + player.duration(), 
            async: false,
            success: function(response) {
                console.log(response["data"]);
                //document.getElementById("table-data").innerHTML = x;
                document.getElementById("teamAscoreID").innerHTML = response["data"]["1"]["team_a_score"];
                document.getElementById("teamBscoreID").innerHTML = response["data"]["1"]["team_b_score"];
                document.getElementById("teamAscoreOverlayID").innerHTML = response["data"]["1"]["team_a_score"];
                document.getElementById("teamBscoreOverlayID").innerHTML = response["data"]["1"]["team_b_score"];
                setTimeout(function(){get_duration();}, 1500);

            },
            error: function(response) {
                console.log(response);
            }

        });  
    }
    get_duration();

    }, maxTimes = false)


});

</script>

@else

<script type="text/javascript">
$( document ).ready(function() {
    $('#areYouSure').on('shown.bs.modal', function () {
        $('#purchaseButton').trigger('focus');
    });

    $('#buyStream').on('shown.bs.modal', function () {
        $('#buyButton').trigger('show');
    });

    var video_name = "<?php echo $vod->name ?>";
    var storage_location = "<?php echo $vod->storage_location ?>";
    var player = videojs('video-id', {"controls": true, "autoplay": true, "fluid": true, "preload": "auto"});

    player.src({
    src: encodeURI('http://192.168.1.69:1935/'+storage_location+'/mp4:'+video_name+'.mp4/playlist.m3u8'),
    type: 'application/x-mpegURL',
    withCredentials: false
    });

    player.newoverlay({
    contentOfOverlay:"<a href='https://supabets.co.za/Sport/Default.aspx' target='_blank'><img class='img-fluid' height='auto' width='50px' src='https://www.paperclipsa.co.za/storage/adverts/images/S6DqtnInVNdbKhm9ppSmSPyvQ7cIw7IOauQ3DqBU.jpeg'></a>"
    + "<div id='score-overlay'>"
    + "<div class='row'>"
    + "<div class='col-4 team-container-overlay'><span>{{$team_a_short}}</span></div>"
    + "<div class='col-4 score-container-overlay'><span id='teamAscoreOverlayID'>{{$fixture->team_a_goals}}</span> - <span id='teamBscoreOverlayID'>{{$fixture->team_b_goals}}<span></div>"
    + "<div class='col-4 team-container-overlay'><span>{{$team_b_short}}</span></div>"
    + "</div>"
    +"</div>",
    changeDuration:10000
    });

});
</script>

@endif

@endsection