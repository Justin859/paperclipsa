@extends('layouts.app')

@section('styles')
<link href="https://fonts.googleapis.com/css?family=Lato:900" rel="stylesheet"> 

<style>
    .play-live-icon 
    { 
        font-size: 54px;
        color: rgb(0, 0, 0, 0.7);
        position: absolute;
        top: calc(50% - 60px);
        right:0px;
        left: 0px;
        bottom: 0px;
        margin:auto;
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
<h1 class="main-heading p-1" align="left">Live Now</h1>
<hr />

<br />
<div class="container">
    <h2 class="main-heading" align="left">Live Indoor Soccer Streams</h2>
    <hr />
    <div class="mx-auto px-1">
        @if($live_streams->count())
        <div class="row d-flex justify-content-center">
        @foreach($live_streams as $live_stream)
        <div class="col-xs-12 col-md-3 vod-item">
            <div class="video-frame">
            <a href="/new/live-now/{{$live_stream->id}}/{{$live_stream->name}}/" class="js-item">
                <img class="img-fluid" src="{{ asset('images/livestream_1.png')}}" height="218" width="auto" />
                <i class="fas fa-play-circle play-live-icon" style="display:none;"></i>
                <i class="fas fa-video live-video">&nbsp;&nbsp;<span class="live-text">Live</span></i>
            </a>
            </div>
            <p style="color: #ffffff; margin: 0px;">{{ucwords(\App\Fixture::where('stream_id', $live_stream->id)->first()->team_a)}} VS {{ucwords(\App\Fixture::where('stream_id', $live_stream->id)->first()->team_b)}}</p>
            <p style="color: #D3D3D3; margin: 0px;">{{\App\Fixture::where('stream_id', $live_stream->id)->first()->date_time}}</p>
            <p style="color: #FFCC00; margin: 0px; margin-bottom: 5px;">@<a href="/channel/{{$live_stream->venue_id}}/{{\App\Venue::find($live_stream->venue_id)->name}}" style="color: #FFCC00;">{{\App\Venue::find($live_stream->venue_id)->name}}</a></p>
        </div>
        @endforeach            
        @else
        <div align="left">
            <h2>There are currently no indoor soccer live streams available. Come back soon.</h2>
            <h3>Meanwhile, you can watch from our video-on-demand page <a href="/new/on-demand">here.</a></h3>
            <h3>Or view all our channels <a href="/channels">here.</a></h3>
        </div>
        @endif
        </div>
    </div>
</div>

<div class="container">
<br />
<h2 class="main-heading" align="left">Live Squash Streams</h1>
<hr />
<div class="mx-auto px-1">
        @if($squash_live_streams->count())
        <div class="row d-flex justify-content-center">
        @foreach($squash_live_streams as $squash_live_stream)
        <div class="col-xs-12 col-md-3 vod-item">
            <div class="video-frame">
            <a href="/new/live-now/squash/{{$squash_live_stream->id}}/{{$squash_live_stream->name}}/" class="js-item">
                <img class="img-fluid" src="{{ asset('images/livestream_1.png')}}" height="218" width="auto" />
                <i class="fas fa-play-circle play-live-icon" style="display:none;"></i>
                <i class="fas fa-video live-video">&nbsp;&nbsp;<span class="live-text">Live</span></i>
            </a>
            </div>
            <p style="color: #ffffff; margin: 0px;">{{ucwords(\App\SquashFixture::where('squash_stream_id', $squash_live_stream->id)->first()->player_1)}} VS {{ucwords(\App\SquashFixture::where('squash_stream_id', $squash_live_stream->id)->first()->player_2)}}</p>
            <p style="color: #D3D3D3; margin: 0px;">{{\App\SquashFixture::where('squash_stream_id', $squash_live_stream->id)->first()->date_time}}</p>
            <p style="color: #FFCC00; margin: 5px;">@<a href="/channel/{{$squash_live_stream->venue_id}}/{{\App\Venue::find($squash_live_stream->venue_id)->name}}" style="color: #FFCC00;">{{\App\Venue::find($squash_live_stream->venue_id)->name}}</a></p>
        </div>
        @endforeach            
        @else
        <div align="left">
            <h2>There are currently no squash live streams available. Come back soon.</h2>
            <h3>Meanwhile, you can watch from our video-on-demand page <a href="/new/on-demand/squash">here.</a></h3>
            <h3>Or view all our channels <a href="/channels">here.</a></h3>
        </div>
        @endif
        </div>
    </div>
</div>
 
<div class="container" style="margin-top: 60px; margin-bottom: 60px;">
    <div class="row">
        <div class="col-12">
            @if($ads != 'none')
                @foreach($ads as $ad)
                <a class="_item" target="_blank" href="{{ $ad->link }}" title="{{ ($ad->desc == '' ) ? $ad->title.' for '.ucwords($ad->company_name) :  $ad->title.'.   '.$ad->desc }}">
                    <span><img class="img-fluid" src="../storage/adverts/images/{{ $ad->image }}" /></span>
                </a>
                @endforeach
            @else
            <h3 style="color: #ffcc00">To advertise on this space, you can call us on +27 82 920 7355, +27 60 490 3732 or email us at info@paperclipsa.com</h3>
            @endif            
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