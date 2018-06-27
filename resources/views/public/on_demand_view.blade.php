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
        <div class="col-md-4 score-container">{{$fixture->team_a_goals}} - {{$fixture->team_b_goals}}</div>
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
    <div class="row">
        <div class="col-md-9" style="padding: 0px;">
            <div style="width:100%; height:454.35px; padding:0 0 56.25% 0 0; background-color:black; color:white;">
            
                <div class="btn-group" style="postion:absolute; top:50%;" role="group" aria-label="User Actions">
                @if($account_balance)
                    <button id="purchaseButton" class="btn btn-outline-warning" data-toggle="modal" data-target="#areYouSure">Use Credits&nbsp;&nbsp;<span class="fas fa-money-bill"></span></button>
                @else
                    <button id="purchaseButton" class="btn btn-outline-warning disabled" aria-disabled="true">Use Credits&nbsp;&nbsp;<span class="fas fa-money-bill"></span></button>           
                @endif    
                    <button id="purchaseButton" class="btn btn-outline-warning">Buy Credits&nbsp;&nbsp;<span class="fas fa-credit-card"></span></button>
                </div>   
            
            </div>     

        </div>
    </div>
    <br />
    <div class="row">
        <div class="col-sm-9" style="padding: 5px;">
            <h2 class="main-heading" align="left">{{str_replace("_", " ",$vod->name)}}<span class="badge badge-secondary float-right" align="right" style="margin-top: 10px;">Cost: 0 Credits</span></h2>
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
                <a href="/on-demand/{{$vod_item->id}}/{{$vod_item->name}}/" class="js-item">
                    <img src="{{ asset('images/vod1.png')}}" height="auto" width="100%" />
                    <i class="far fa-play-circle play-icon" style="display:none;"></i>
                    {{str_replace("_", " ", $vod_item->name)}}
                </a>
            </div>
            @endif
        @endforeach
    </div>

</div>

@endsection
@section('modal')
<!-- Modal for purchase request -->
<div id="areYouSure" class="modal" tabindex="-1" role="dialog">
    <form method="post" action="/on-demand/{{$vod->id}}/{{$vod->name}}">
    {{ csrf_field() }}
    <input type="number" name="vod_id" hidden="true" value="{{$vod->id}}" readonly/>
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Use Tokens To Access Video On Demand</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p><strong>0</strong> credits will be deducted</p>
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


<?php $ip_address = \App\GlobalSetting::find(1)->wowza_server_ip ?>

<script type="text/javascript">
$( document ).ready(function() {
    $('#areYouSure').on('shown.bs.modal', function () {
    $('#purchaseButton').trigger('focus');
    });

    var video_name = "<?php echo $vod->name ?>";
    var player = videojs('video-id', {"controls": true, "autoplay": true, "fluid": true, "preload": "auto"});

    player.src({
    src: 'http://192.168.0.24:1935/VOD_STORAGE_1/mp4:'+video_name+'.mp4/playlist.m3u8',
    type: 'application/x-mpegURL',
    withCredentials: false
    });

    player.newoverlay({
    contentOfOverlay:"<a href='#'><img class='img-fluid' height='auto' width='50px' src='http://paperclipsa.local/images/logo_2 PNG.png'></a>",
    changeDuration:10000
    });

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