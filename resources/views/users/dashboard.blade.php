@extends('layouts.app')

@section('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.min.css">
@include('includes.user_profile_styles')
<style>

    .fa-arrow-circle-down, .fa-arrow-circle-up {
        font-size: 24px;
        color: #D50000;
    }

    .collapse-btn
    {
        background-color: transparent;
        border: none;
    }

    .collapse-btn:hover
    {
        background-color: transparent;
    }

    .collapse-btn:active
    {
        border: none !important;
    }
    .collapse-btn:focus
    {
        border: none !important;
    }
    h3
    {
        color: #ffffff;
    }
    /* table th {
        text-align: center; 
    } */

    .table {
        margin: auto;
    }

    td, th{
        vertical-align: middle !important;
    }
    h2
    {
        color: #ffffff;
        
    }

    .venue-staff
    {
        position: relative;
        float: left;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }

    h2 i
    {
        color: #D50000;
    }
</style>
@endsection

@section('content')

<div class="container" style="height:100%; margin:auto;">
    <div class="row">

    @include('includes.user_side_panel')<!-- side panel -->

    <div class="col-12 col-md-9 order-md-2">
        @if(!$is_referee and !$is_admin and !$is_coach)
            @if(!$email_verified)
            <div class="row">
                <div class="col-12">
                    <form action="/verify-email" method="post" onsubmit="on_verify_submit();">
                    @csrf
                        <button type="submit" id="verify-button" class="btn float-right">Verify Your Email Address</button>
                    </form>
                </div>
            </div>
            @endif
        @endif
        
        @if(!$is_referee and !$is_coach and !$is_admin)
        <div class="row">
            <div class="col-12">
                <h2 style="color:#ffffff;">Get Notifications
                    <a class="float-right collapse-btn" data-toggle="collapse" href="#notificationCollpase" role="button" aria-expanded="false" aria-controls="notificationCollpase">
                        <i class="fas fa-arrow-circle-down" id="collapseBtn"></i>
                    </a>
                </h2>
                <p style="color: #FFCC00">Select your team and get email notifications when new videos are available on demand</p>
                <hr />
            </div>
        </div>
        <div class="collapse" id="notificationCollpase">
        <form action="/get-notifications/team" method="post" id="notificationFormId" name="notificationForm">
            @csrf
            <label><strong>Find A Team</strong></label>
            <div class="form-group">
                <select class="selectpicker js-example-basic-single form-control" data-live-search="true" name="team">
                    <?php $venue_groups = \App\Venue::where('active_status', 'active')->get(); ?>
                    @foreach($venue_groups as $venue_group)
                    <optgroup label="{{$venue_group->name}}" data-max-options="2">
                        @foreach($teams as $team)
                            @if($team->venue_id == $venue_group->id)
                                <option value="{{$team->id}}">{{$team->name}}</option>
                            @endif
                        @endforeach
                    </optgroup>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <button type="submit" name="notificationSubmit" id="notificationButton" class="btn btn-block btn-outline-warning">Get Email Notifications&nbsp;&nbsp;&nbsp;&nbsp;<i class="far fa-bell" style="font-size: 24px;"></i></button>
            </div>
        </form>
           @if($notification_teams)
           <div class="row">
                <div class="col-12">
                    <h3>Teams from your email notifications</h3>
                    <table class="table table-sm table-striped table-dark">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Teams</th>
                                <th>Venue</th>
                                <th>cancel</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($notification_teams as $key=>$notf_team)
                            <tr>
                                <th scope="row">{{$key +1}}</th>
                                <td>{{$notf_team->name}}</td>
                                <td>{{\App\Venue::find($notf_team->venue_id)->name}}</td>
                                <td>
                                    <form action="/get-notifications/team/remove" name="cancel_notification_{{$key}}" method="post">
                                    @csrf
                                    <input type="hidden" name="team_notf_id" value="{{$notf_team->id}}">
                                    <button type="submit" name="submit_cancelation_{{$key}}" class="btn btn-sm btn-outline-danger">Cancel&nbsp;&nbsp;<i class="fas fa-ban"></i></button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
           @endif
        </div>

        <div class="row">
            <div class="col-12">
                <h2 style="color:#ffffff;">Join Your Club
                    <a class="float-right collapse-btn" data-toggle="collapse" href="#joinClubCollapse" role="button" aria-expanded="false" aria-controls="joinClubCollapse">
                        <i class="fas fa-arrow-circle-down" id="collapseBtnClub"></i>
                    </a>
                </h2>
                <p style="color: #FFCC00">Request to verify your profile as an <b>Admin</b> or <b>Player</b> of your club</p>
                <hr />
            </div>
        </div>
        <div class="collapse" id="joinClubCollapse">
        <form action="/join-club/request" method="post" id="joinClubFormId" name="joinClubForm">
            @csrf
            <label><strong>Are you a Player or an Admin ?</strong></label>
            <div class="form-group">
                <select name="profile_type" id="profileType" class="form-control">
                    <option value="player">Player</option>
                    <option value="admin">Admin</option>
                </select>
            </div>
            <label><strong>Find A Club</strong></label>
            <div class="form-group">
                <select class="selectpickerClub js-example-basic-single form-control" data-live-search="true" name="club">
                    <?php $venue_groups = \App\Venue::where('active_status', 'active')->get(); ?>
                    @foreach($venue_groups as $venue_group)
                    <optgroup label="{{$venue_group->name}}" data-max-options="2">
                        @foreach($teams as $team)
                            @if($team->venue_id == $venue_group->id)
                                <option value="{{$team->id}}">{{$team->name}}</option>
                            @endif
                        @endforeach
                    </optgroup>
                    @endforeach
                </select>
            </div>
            <label><strong>Message (Optional)</strong></label>
            <div class="form-group">
                <textarea class="form-control" name="message" placeholder="Your message" rows="4" maxlength="255"></textarea>
            </div>
            <div class="form-group">
                <button type="submit" name="joinClubSubmit" id="joinClubButton" class="btn btn-block btn-outline-warning">Submit Request&nbsp;&nbsp;&nbsp;&nbsp;<i class="far fa-envelope" style="font-size: 24px;"></i></button>
            </div>
        </form>
           
        </div>        

        <div class="row">
            <div class="col-12">
                <h2 style="color:#ffffff;">Watch again
                    <a class="float-right collapse-btn" data-toggle="collapse" href="#watchagainCollapse" role="button" aria-expanded="true" aria-controls="watchagainCollapse">
                        <i class="fas fa-arrow-circle-down" id="collapseBtnWatch"></i>
                    </a>
                </h2>
                <p style="color: #FFCC00">Videos you have seen recently</p>
                <hr />
            </div>
        </div>
        <div class="collapse" id="watchagainCollapse">
            <div class="mx-auto px-1">
                <div class="row d-flex justify-content-left m-md-1">
                @if($watch_again)
                @foreach($watch_again as $vod)
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
        </div>

        </div>

        @else
            @if($is_referee)
                <h2 class="venue-staff" >You are logged in as a referee <i class="far fa-address-card"></i></h2>
            @elseif($is_admin)
                <h2 class="venue-staff">you are logged in as an admin  <i class="far fa-address-card"></i></h2>
            @elseif($is_coach)
                <h2 class="venue-staff">You are logged in as a coach <i class="far fa-address-card"></i></h2>
            @endif
        @endif

        </div>
    
    </div>

</div>

@endsection

@section('modal')

<!-- Modal -->


@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>
<script>

var on_verify_submit = function() {
    document.getElementById('verify-button').innerHTML = 'Verify Your Email Address <i class="fas fa-spinner fa-spin"></i>';
};

$(document).ready(function() {
    $.fn.selectpicker.Constructor.BootstrapVersion = '4';
        $('.selectpicker').selectpicker({
            style: 'btn-secondary',
            size: 4
        });
        $('.selectpickerClub').selectpicker({
            style: 'btn-primary',
            size: 4
        });
    
    $('#notificationCollpase').on('shown.bs.collapse', function () {
        $('#collapseBtn').removeClass("fas fa-arrow-circle-down").addClass('fas fa-arrow-circle-up');
    });

    $('#notificationCollpase').on('hidden.bs.collapse', function () {
        $('#collapseBtn').removeClass("fas fa-arrow-circle-up").addClass('fas fa-arrow-circle-down');
    });

    $('#watchagainCollapse').on('shown.bs.collapse', function () {
        $('#collapseBtnWatch').removeClass("fas fa-arrow-circle-down").addClass('fas fa-arrow-circle-up');
    });

    $('#watchagainCollapse').on('hidden.bs.collapse', function () {
        $('#collapseBtnWatch').removeClass("fas fa-arrow-circle-up").addClass('fas fa-arrow-circle-down');
    });

    $('#joinClubCollapse').on('shown.bs.collapse', function () {
        $('#collapseBtnClub').removeClass("fas fa-arrow-circle-down").addClass('fas fa-arrow-circle-up');
    });

    $('#joinClubCollapse').on('hidden.bs.collapse', function () {
        $('#collapseBtnClub').removeClass("fas fa-arrow-circle-up").addClass('fas fa-arrow-circle-down');
    });    
});

</script>

@endsection