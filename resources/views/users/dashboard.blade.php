@extends('layouts.app')

@section('styles')
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
@include('includes.user_profile_styles')
<style>
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
                <h2 style="color:#ffffff;">Get Notifications</h2>
                <hr />
            </div>
        </div>
            <form action="/get-notifications/team" method="post" id="notificationFormId" name="notificationForm">
            @csrf
            <label><strong>Find A Team</strong></label>
            <div class="form-group">
                <select class="js-example-basic-single form-control" name="team">
                    <?php $venue_groups = \App\Venue::where('active_status', 'active')->get(); ?>
                    @foreach($venue_groups as $venue_group)
                            @foreach($teams as $team)
                                @if($team->venue_id == $venue_group->id)
                                    <option value="{{$team->id}}">{{$team->name}} -- {{$venue_group->name}}</option>
                                @endif
                            @endforeach
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
                                <th scope="row">{{$key}}</th>
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
           @endif
        <div class="row">
            <div class="col-12">
                <h2 style="color:#ffffff;">Watch again</h2>
                <hr />
            </div>
        </div>
        <div class="mx-auto px-1">
            <div class="row d-flex justify-content-left m-md-1">
            @if($watch_again)
            @foreach($watch_again as $vod)
                @if($vod->stream_type == "vod")
                <div class="col-xs-12 col-md-3 vod-item">
                    <a href="/on-demand/indoor-soccer/{{$vod->id}}/{{$vod->name}}/" class="js-item">
                        <img src="{{ asset('images/vod1.png')}}" height="auto" width="100%" />
                        <i class="far fa-play-circle play-icon" style="display:none;"></i>
                    </a>
                    <p style="color: #ffffff; margin: 0px;">{{ucwords(\App\Fixture::where('stream_id', $vod->id)->first()->team_a)}} VS {{ucwords(\App\Fixture::where('stream_id', $vod->id)->first()->team_b)}}</p>
                    <p style="color: #D3D3D3; margin: 0px;">{{\App\Fixture::where('stream_id', $vod->id)->first()->date_time}}</p>
                    <p style="color: #FFCC00; margin: 0px;">@<a <a href="/channel/{{$vod->venue_id}}/{{\App\Venue::find($vod->venue_id)->name}}" style="color: #FFCC00;">{{\App\Venue::find($vod->venue_id)->name}}</a></p>
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
    

</div>

@endsection

@section('modal')

<!-- Modal -->


@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
<script>

var on_verify_submit = function() {
    document.getElementById('verify-button').innerHTML = 'Verify Your Email Address <i class="fas fa-spinner fa-spin"></i>';
};

$(document).ready(function() {
    $('.js-example-basic-single').select2({
        theme: "classic"
    });
});

</script>

@endsection