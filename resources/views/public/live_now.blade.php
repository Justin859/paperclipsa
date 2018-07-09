@extends('layouts.app')

@section('styles')
<link href="https://fonts.googleapis.com/css?family=Lato:900" rel="stylesheet"> 
<style>
    .play-live-icon 
    { 
        font-size: 54px;
        color: rgb(0, 0, 0, 0.7);
        position: absolute;
        top: calc(50% - 50px);
        right: 0px;
        left: 0px;
        bottom: 0px;
        margin: auto;
        vertical-align: middle;
        text-align: center;
    }
    .live-video
    {
        font-size: 18px;
        background-color: #FFCC00;
        color: black;
        position: absolute;
        top: 0px;
        right: 0px;
        padding: 8px;
    }
    .live-text
    {
        font-family: 'Lato';
    }
</style>
@endsection

@section('content')

<br />
<h1 class="main-heading" align="left">Live Videos</h1>
<hr />

<br />
<div class="container">
    @if($live_streams->count())
    <h2 class="main-heading" align="left">Live Indoor Soccer Streams</h2>
    <hr />
    <div class="mx-auto px-1">
        <div class="row d-flex justify-content-center">
        @foreach($live_streams as $live_stream)
            <div class="col-12 col-md-6 vod-item">
                <div class="video-frame">
                <a href="/live-now/{{$live_stream->id}}/{{$live_stream->name}}/" class="js-item">
                    <img class="img-fluid" src="{{ asset('images/livestream_1.png')}}" height="218" width="auto" />
                    <i class="fas fa-play-circle play-live-icon" style="display:none;"></i>
                    <i class="fas fa-video live-video">&nbsp;&nbsp;<span class="live-text">Live</span></i>
                </a>
                </div>
                <p style="color: #ffffff; margin: 5px;">{{str_replace("_", " ", $live_stream->name)}}</p>
            </div>
        @endforeach
        </div>
    </div>
    @endif
    @if($squash_live_streams->count())
    <br />
    <h2 class="main-heading" align="left">Live Squash Streams</h1>
    <hr />
    <div class="mx-auto px-1">
        <div class="row d-flex justify-content-center">
        @foreach($squash_live_streams as $squash_live_stream)
            <div class="col-12 col-md-6 vod-item">
                <div class="video-frame">
                <a href="/live-now/squash/{{$squash_live_stream->id}}/{{$squash_live_stream->name}}/" class="js-item">
                    <img class="img-fluid" src="{{ asset('images/livestream_1.png')}}" height="218" width="auto" />
                    <i class="fas fa-play-circle play-live-icon" style="display:none;"></i>
                    <i class="fas fa-video live-video">&nbsp;&nbsp;<span class="live-text">Live</span></i>
                </a>
                </div>
                <p style="color: #ffffff; margin: 5px;">{{str_replace("_", " ", $squash_live_stream->name)}}</p>
            </div>
        @endforeach
        </div>
    </div>
    @endif
</div>

@endsection

@section('scripts')
<script>
    $( document ).ready(function() {
        $( ".js-item" )
        .hover(function() {
            $(this).find('.play-live-icon').css("display","block");
        }, function() {
            $(this).find('.play-live-icon').css("display","none");
        });
    });
</script>
@endsection