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

    .list-group img
    {
        border: 1px solid #ffffff;
    }

</style>

@include('includes.user_profile_styles')

@endsection

@section('content')

<div class="container">
    <div class="row">
        
        <div class="col-12 col-md-9 order-md-2">
        <br />
        <h2>My Soccer Clubs</h2><hr />
        <ul class="list-group list-group-flush mb-5">
        @if($clubs->count())
            @foreach($clubs as $key=>$club)
                <?php 
                    $team_profile = \App\TeamProfile::where('team_id', $club->id)->first();
                    $team_players = \App\TeamPlayer::where('team_id', $club->id)->count();
                ?>
                <li class="list-group-item list-item-notification">
                <div class="media">
                @if($team_profile)
                
                    @if($team_profile->logo)
                    <img class="mr-3" height="64" width="64" src="{{asset('/storage/clubs/logos/' . $team_profile->logo)}}" alt="Team logo placeholder image">
                    @else
                    <img class="mr-3" height="64" width="64" src="{{asset('images/football-1406106_960_720.jpg')}}" alt="Generic placeholder image">
                    @endif
                    <div class="media-body text-truncate">
                        <h4 class="mt-0"><a href="/user-profile/my-soccer-clubs/{{$club->id}}/{{$club->name}}">{{$club->name}}</a></h4>
                        @if($team_profile->description)
                        {{$team_profile->description}}
                        @else
                        {{$club->name}} is a team from {{\App\Venue::find($club->venue_id)->name}}
                        @endif
                        <ul class="list-inline float-right mt-2">
                            <li class="list-inline-item badge badge-warning">Players: {{$team_players}}</li>
                            <li class="list-inline-item badge badge-warning">Venue: {{\App\Venue::find($club->venue_id)->name}}</li>
                        </ul>                      
                    </div>
                </div>
                @else
                <img class="mr-3" height="64" width="64" src="{{asset('images/football-1406106_960_720.jpg')}}" alt="Generic placeholder image">
                    <div class="media-body text-truncate">
                        <h4 class="mt-0"><a href="/user-profile/my-soccer-clubs/{{$club->id}}/{{$club->name}}">{{$club->name}}</a></h4>
                        {{$club->name}} is a team from {{\App\Venue::find($club->venue_id)->name}}
                        <ul class="list-inline float-right mt-2">
                            <li class="list-inline-item badge badge-warning">Players: {{$team_players}}</li>
                            <li class="list-inline-item badge badge-warning">Venue: {{\App\Venue::find($club->venue_id)->name}}</li>
                        </ul>
                    </div>
                </div>
                @endif

                </li>
                
            @endforeach
        @else
            <p>You are not registered with any clubs. Go to your profile main page <a href="/user-profile">here</a> and submit a request from the <strong>Join Your Club</strong> dropdown</p>    
        @endif
        </ul>
        </div> <!-- view panel -->    
        @include('includes.user_side_panel')<!-- side panel -->

    </div>
</div>

@endsection

@section('scripts')
<script>

// Methods

</script>
@endsection