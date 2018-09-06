@extends('layouts.app')

@section('styles')
<style>
   .btn-group .btn
    {
        border-radius: 0px !important;
    }

    h1, h3, h2
    {
        color: #ffffff;
    }

    .venue-description
    {
        background-color: rgba(0, 0, 0, 0.5);
    }

    p
    {
        color: #ffffff;
    }

    .fas, .fab
    {
        font-size: 24px;
    }

    .fa-twitter
    {
        color: #00aced;
    }

    .fa-facebook
    {
        color: #3B5998;
    }

    .fa-globe
    {
        color: #ffffff;
    }

    .club-logo-container
    {
        width: 100%;
        text-align: center;
        position:relative;
    }

    .club-logo-container img
    {
        box-shadow: 3px 3px 1px #ccc;
        -webkit-box-shadow: 3px 3px 1px rgba(255, 255, 255, 0.1);
        -moz-box-shadow: 3px 3px 1px rgba(255, 255, 255, 0.1);
    }

</style>
@endsection

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12 col-md-10 offset-md-1">
            <br />
            <h2>{{$club->name}} <small class="text-muted">{{$venue->name}}</small>&nbsp;&nbsp;
            </h2><hr />
            <div class="club-logo-container" style="position: relative;">
            @if($has_team_profile and $has_team_profile->logo)
                <div class="club-logo-background" style="background-image: url({{asset('/storage/clubs/logos/' . $has_team_profile->logo)}}); background-size: cover; -webkit-filter: blur(2px);position:absolute;top:0;left:0;right:0;bottom:0;z-index:1;">
                </div>
            @else
                <div class="club-logo-background" style="background-image: url({{asset('/images/football-1406106_960_720.jpg')}}); background-size: cover; -webkit-filter: blur(2px);position:absolute;top:0;left:0;right:0;bottom:0;z-index:1;">
                </div>
            @endif
                <div class="club-logo">
                @if($has_team_profile and $has_team_profile->logo)
                <img src="{{asset('/storage/clubs/logos/' . $has_team_profile->logo)}}" class="img-fluid" style="max-width: 170px; position:relative; z-index: 100;" />
                @else
                <img src="{{asset('/images/football-1406106_960_720.jpg')}}" class="img-fluid" style="max-width: 170px; position:relative; z-index: 100;" />
                @endif
                </div>
            </div>
            <br />
            @if($has_team_profile and $has_team_profile->description)
            <blockquote class="blockquote mb-5">
                <p class="mb-0 text-truncate" id="teamDescription">{{$has_team_profile->description}}</p>
                <a href="#" class="float-right" id="readMore" style="color: #D50000;" onclick="read_more(event);">[read more]</a>
            </blockquote>
            @endif
        </div>
    </div>

    <div class="row">
        <div class="col-12 col-md-10 offset-md-1">
        <h2>Players&nbsp;&nbsp;
        </h2><hr />
        <div class="mx-auto px-1">
            <div class="row d-flex justify-content-left m-md-1">
            @if($team_players->count())
            @foreach($team_players as $team_player)
            <?php 
                $user = \App\User::find($team_player->user_id);
                $user_profile = \App\UserProfile::where('user_id', $user->id)->first();
            ?>
                <div class="col-xs-12 col-md-3 mb-3">
                <div class="media">
                @if($user_profile and $user_profile->profile_image)
                <img class="mr-3" src="{{asset('storage/userprofile_imgs/'. $user_profile->profile_image)}}" height="64" width="64" alt="Generic placeholder image">
                @else
                <img class="mr-3" src="{{asset('storage/userprofile_imgs/profile_img.jpg')}}" height="64" width="64" alt="Generic placeholder image">
                @endif
                <div class="media-body">
                    <h5 class="mt-0" style="color: #ffffff;">{{$user->firstname}} {{$user->surname}}</h5>
                </div>
                </div> 
                </div>
            @endforeach
            @else
                <p>There are no registered players yet.</p>
            @endif
            </div>
        </div>
        <br />
        <h2>Latest Fixtures</h2><hr />
        <div class="mx-auto px-1">
            <div class="row d-flex justify-content-left m-md-1">
            @if($club_vods->count())
            @foreach($club_vods as $vod)
                @if($vod->stream_type == "vod")
                <div class="col-xs-12 col-md-3 vod-item">
                    <div class="vod-wrapper">
                    <a href="/on-demand/indoor-soccer/{{$vod->id}}/{{$vod->name}}/" class="js-item">
                        <img src="{{ asset('images/vod_1.png')}}" height="auto" width="100%" />
                        <i class="far fa-play-circle play-icon" style="display:none;"></i>
                    </a>
                    @if($vod->duration)
                    <span class="video-duration">
                        @if($vod->duration < 5400)
                        {{gmdate("H:i:s" ,$vod->duration)}}
                        @endif
                    </span>
                    @endif
                    </div>
                    <p style="color: #ffffff; margin: 0px;">{{ucwords(\App\Fixture::where('stream_id', $vod->id)->first()->team_a)}} VS {{ucwords(\App\Fixture::where('stream_id', $vod->id)->first()->team_b)}}</p>
                    <p style="color: #D3D3D3; margin: 0px;">{{\App\Fixture::where('stream_id', $vod->id)->first()->date_time}}</p>
                    <p style="color: #FFCC00; margin: 0px; margin-bottom: 5px;">@<a href="/channel/{{$vod->venue_id}}/{{\App\Venue::find($vod->venue_id)->name}}" style="color: #FFCC00;">{{\App\Venue::find($vod->venue_id)->name}}</a></p>
                </div>
                @endif
            @endforeach
            @else
                <p>There are no recorded games for {{$club->name}} available yet.</p>
            @endif
            </div>
        </div>

        </div>
    </div>
    
    
</div>
@endsection

@section('scripts')
<script>

// Methods
var read_more = function(e) {
    e.preventDefault();
    if($('#teamDescription').hasClass('text-truncate')) {

        $('#teamDescription').removeClass('text-truncate')
        $('#readMore').html('[show less]');
    } else {
        $('#teamDescription').addClass('text-truncate')
        $('#readMore').html('[read more]');
    }
}
</script>

@endsection