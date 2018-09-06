@extends('layouts.app')

@section('styles')

<style>
    h2, p
    {
        color: #ffffff;
    }
    form
    {
        margin: 0px;
        padding: 0px;
    }

    .list-item-notification:hover
    {
        background-color: #202020 !important;
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
    .fa-pen-square, .fa-user-edit
    {
        color: #ffffff;
    } 
    .fa-pen-square:hover, .fa-user-edit:hover
    {
        color: #D50000;
    }
</style>

@include('includes.user_profile_styles')

@endsection

@section('content')

<div class="container">
    <div class="row">
        
        <div class="col-12 col-md-9 order-md-2">
        <br />
        <h2>{{$club->name}} <small class="text-muted">{{$venue->name}}</small>&nbsp;&nbsp;
            @if($is_admin)
            <a href="/user-profile/my-soccer-clubs/{{$club->id}}/{{$club->name}}/edit" class="float-right"><i class="fas fa-pen-square m-1"></i></a>
            @endif
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
            <a href="#" class="float-right" id="readMore" style="color: #D50000;" onclick="read_more();">[read more]</a>
        </blockquote>
        @endif
        <h2>Players&nbsp;&nbsp;
            @if($is_admin)
            <a href="/user-profile/my-soccer-clubs/{{$club->id}}/{{$club->name}}/players/edit" class="float-right"><i class="fas fa-user-edit"></i></a>
            @endif
        </h2><hr />
        <table class="table table-dark table-responsive-sm mt-3">
            <thead>
                <tr>
                    <th scope="col"># ID</th>
                    <th scope="col">Name</th>
                    <th scope="col">email</th>
                    <th scope="col">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($team_players as $team_player)
                <tr>
                    <th scope="row">{{$team_player->id}}</th>
                    <?php 
                        $user = \App\User::find($team_player->user_id);
                        $user_profile = \App\UserProfile::where('user_id', $user->id)->first();
                    ?>
                    <td>
                    <div class="media">
                    @if($user_profile and $user_profile->profile_image)
                    <img class="mr-3" src="{{asset('storage/userprofile_imgs/'. $user_profile->profile_image)}}" height="64" width="64" alt="Generic placeholder image">
                    @else
                    <img class="mr-3" src="{{asset('storage/userprofile_imgs/profile_img.jpg')}}" height="64" width="64" alt="Generic placeholder image">
                    @endif
                        <div class="media-body">
                            <h5 class="mt-0">{{$user->firstname}} {{$user->surname}}</h5>
                        </div>
                    </div>
                        
                    </td>
                    <td>{{$user->email}}</td>
                    @if($team_player->active_status == 'active')
                    <td style="color:#006600; font-weight: bold;">active</td>
                    @else
                    <td style="color:#D50000; font-weight: bold;">inactive</td>
                    @endif
                </tr>
                @endforeach
            </tbody>
        </table>
        <br />
        <h2>Latest Fixtures</h2><hr />
        <div class="mx-auto px-1">
                <div class="row d-flex justify-content-left m-md-1">
                @if($club_vods)
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
                    <p>You have not watched any streams yet. Find your games on the <a href="/on-demand">on-demand</a> page or browse our <a href="/channels">channels</a>.</p>
                @endif
                </div>
            </div>
        </div> <!-- view panel -->    
        @include('includes.user_side_panel')<!-- side panel -->

    </div>
</div>

@endsection

@section('scripts')
<script>

// Methods
var read_more = function() {
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