@extends('layouts.app')

@section('styles')

@include('includes.user_profile_styles')

@endsection

@section('content')

<div class="container" style="height:100%; margin:auto;">
    <div class="row">

    @include('includes.user_side_panel')<!-- side panel -->

    <div class="col-12 col-md-9 order-md-2">
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
                    <a href="/on-demand/{{$vod->id}}/{{$vod->name}}/" class="js-item">
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

@section('scripts')

<script>

var on_verify_submit = function() {
    document.getElementById('verify-button').innerHTML = 'Verify Your Email Address <i class="fas fa-spinner fa-spin"></i>';
};

</script>

@endsection