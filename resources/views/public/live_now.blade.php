@extends('layouts.app')

@section('styles')
<style>
    .play-live-icon 
    { 
        font-size: 54px;
        color: rgb(0, 0, 0, 0.7);
        position: absolute;
        top: 38%;
        right: 45%;
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
</style>
@endsection

@section('content')

<br />
<h1 class="main-heading" align="left">Live Videos</h1>
<hr />

<br />
<div class="container">
    <div class="mx-auto px-1">
        <div class="row d-flex justify-content-center">
        @foreach($live_streams as $live_stream)
            @if($live_stream->stream_type == "live")
            <div class="col-12 col-md-6 vod-item">
                <div class="video-frame">
                <a href="/live-now/{{$live_stream->id}}/{{$live_stream->name}}/" class="js-item">
                    <img class="img-fluid" src="{{ asset('images/livestream_1.png')}}" height="218" width="auto" />
                    <i class="fas fa-play-circle play-live-icon" style="display:none;"></i>
                    <i class="fas fa-video live-video">&nbsp;&nbsp;Live</i>
                </a>
                </div>
                <p style="color: #ffffff; margin: 5px;">{{str_replace("_", " ", $live_stream->name)}}</p>
            </div>
            @endif
        @endforeach
        </div>

    </div>
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