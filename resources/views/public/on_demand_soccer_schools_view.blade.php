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
    .session-date-time, .session-venue
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
        <video id="video-id" class="video-js vjs-default-skin vjs-big-play-centered" width="640" height="268">

        </video>
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
        <div class="col-sm-9" style="padding: 0px;">
            <h2 class="main-heading" align="left">{{$session->age_group_id}}</h2>
            <p  class="session-date-time" align="left">{{$session->date_time}}</p>
            <p class="session-venue" align="left"><a class="venue-link" href="/channel/{{$current_venue->id}}/{{$current_venue->name}}">{{"@" . $current_venue->name}}</a></p>
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
                <a href="/on-demand/soccer-schools/{{$vod_item->id}}/{{$vod_item->name}}/" class="js-item">
                    <img src="{{ asset('images/vod_1.png')}}" height="auto" width="100%" />
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

<!-- Modal -->

<!-- End of Modal -->
@endsection
@section('scripts')

<script src="{{asset('node_modules/video.js/dist/video.js')}}"></script>
<script src="{{asset('node_modules/videojs-contrib-hls/dist/videojs-contrib-hls.min.js')}}"></script>
<script src="{{asset('node_modules/videojs-dynamic-overlay/dist/videojs-newoverlay.min.js')}}"></script>
<script src="{{asset('node_modules/videojs-watermark/dist/videojs-watermark.min.js')}}"></script>
<script src="https://cdn.jsdelivr.net/npm/videojs-flash@2/dist/videojs-flash.min.js"></script>

<script type="text/javascript">
$( document ).ready(function() {

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

});
</script>

@endsection