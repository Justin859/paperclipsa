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
    /* .video-js 
    {
        width: 100% !important;
        height: auto !important;
        z-index: 9999;
    } */
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
    
    <!-- Scoreboard Goes Here -->
    <div class="row">
        <div class="col-md-8 offset-md-2" style="padding: 0px;">
            <table class="table table-dark  text-center">
                <thead>
                    <tr>
                        <th scope="col">Round</th>
                        <th scope="col">{{$fixture->player_1}}</th>
                        <th scope="col">{{$fixture->player_2}}</th>
                        <th scope="col"></th>
                    </tr>
                </thead>
            <tbody id="table-data">
                @foreach($rounds as $key=>$round)
                    <tr>
                        <td>{{$key}}</td>
                        <td>{{$round_points[$key]["player_1"]}}</td>
                        <td>{{$round_points[$key]["player_2"]}}</td>
                        <td></td>
                    </tr>
                @endforeach
            </tbody>
            </table>
        </div>
    </div>

    @else
    <div class="row" style="background-color: #000000;">
        <div class="col-md-8 offset-md-2" style="padding: 0px;">
            <div style="width:100%; height:454.35px; padding:0 0 56.25% 0 0; background-color:black; color:white;">
                <div class="btn-group" style="postion:absolute; top:45%;" role="group" aria-label="User Actions">
                @if($account_balance->balance_value >= 10)
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
    <div class="row d-flex justify-content-center">
            @foreach($vods_from_venue as $vod_item)
            @if($vod_item->stream_type == "vod")
            <div class="col-xs-2 col-md-3 vod-item vod-items">
                <a href="/on-demand/indoor-soccer/{{$vod_item->id}}/{{$vod_item->name}}/" class="js-item">
                    <img src="{{ asset('images/vod_1.png')}}" height="auto" width="100%" />
                    <i class="far fa-play-circle play-icon" style="display:none;"></i>
                    {{str_replace("_", " ", $vod_item->name)}}
                </a>
            </div>
            @endif
        @endforeach
    </div>
    <hr />
</div>

@endsection
@section('modal')
<!-- Modal for purchase request -->
<div id="areYouSure" class="modal" tabindex="-1" role="dialog">
    <form method="post" action="/on-demand/squash/purchase">
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
                    <p><strong>10</strong> Credits will be deducted</p>
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
                <p>Buying credits and using tokens to access videos is still an available option at <strong>10</strong> credits per video.</p>
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



<?php $ip_address = \App\GlobalSetting::find(1)->wowza_server_ip ?>

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
    contentOfOverlay:"<a href='https://supabets.co.za/Sport/Default.aspx' target='_blank'><img class='img-fluid' height='auto' width='50px' src='https://www.paperclipsa.co.za/storage/adverts/images/S6DqtnInVNdbKhm9ppSmSPyvQ7cIw7IOauQ3DqBU.jpeg'></a>",
    changeDuration:10000
    });

player.on('loadedmetadata', function() {
    var duration = player.duration();
    console.log(duration);
});

    function get_duration() {
        $.ajax({
            type: "POST",
            url: "/api/get-squash-score-odv?fixture_id=<?php echo $fixture->id ?>&current_duration=" + player.currentTime() + "&video_duration=" + player.duration(), 
            async: false,
            success: function(response) {
                console.log(response["data"]);
                var x = "";
                for (i in response["data"]) {
                    x += "<tr>";
                    x += "<td>" + i + "</td>";
                    x += "<td>" + response["data"][i]["player_1_score"] + "</td>";
                    x += "<td>" + response["data"][i]["player_2_score"] + "</td>";
                    x += "</tr>";
                }
                document.getElementById("table-data").innerHTML = x;
                setTimeout(function(){get_duration();}, 1500);

            },
            error: function(response) {
                console.log(response);
            }

        });  
    }

    get_duration();
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