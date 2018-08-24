@extends('layouts.app')
@section('styles')
<style>
    .team-name 
    {
        color: #ffffff;
        background: #D00000;
        padding: 15px;
    }
    .game-header
    {
        color: #ffffff;
        padding: 8px;
    }
    .session-date-time, .session-venue
    {
        color:#FFCC00;
        padding: 8px;
    }

    .table {
        margin: auto;
    }

    td {
        vertical-align: middle !important;
    }
    hr
    {
        background-color: grey;
    }
</style>
@endsection
@section('content')
<h1 class="game-header">Live Session <small>{{$session->age_group_id}}</small></h1>
<p class="session-venue">{{$venue->name}}</p>
<form id="game" action="/referee/update-scores" method="post" name="update-scores" onsubmit="return confirm('Are you sure?');">
@csrf
<input name="session_id" type="text" value="{{$session->id}}" hidden readonly />
<input id="highlight" name="highlight_time" type="text" hidden />
<input id="highlightName" name="highlight_name" type="text" hidden />
<div class="container-fluid">
    <hr />
</div>
</form>
<div class="container-fluid">
<div class="row">
    <div class="col-12">
        <form id="stop_game" method="post" action="/coach/stop-stream" name="stopStream">
        {{ csrf_field() }}
        <input type="hidden" name="stream_name" value="{{$stream->name}}" readonly required />
        <input type="hidden" name="ss_stream_id" value="{{$stream->id}}" readonly required />
        <input type="hidden" name="app_name" value="{{$venue->wow_app_name}}" readonly required/>
        <input type="submit" class="btn btn-outline-danger btn-lg btn-block" name="stopStreamBtn" value="Stop Stream" />
        </form>
    </div>    
</div>
<hr />
<div class="row">
    <div class="col-12">
        <a href="/coach/dashboard/" class="btn btn-outline-info btn-lg btn-block">Back to Dashboard</a>
    </div>    
</div>
</div>
<div class="badge badge-secondary" id="time-elapsed" style="color: #ffffff"></div>

@endsection

@section('scripts')
<script type="text/javascript">

    $( document ).ready(function() {

        var startDateTime = new Date("<?php echo $session->date_time ?>"); // YYYY (M-1) D H m s ms (start time and date from DB)
        var startStamp = startDateTime.getTime();

        var newDate = new Date();
        var newStamp = newDate.getTime();

        var timer; // for storing the interval (to stop or pause later if needed)

        function pad(n) {
            return (n < 10) ? ("0" + n) : n;
        }

       function updateClock() {
            newDate = new Date();
            newStamp = newDate.getTime();
            var diff = Math.round((newStamp-startStamp)/1000);
            
            var h = Math.floor(diff/(60*60));
            diff = diff-(h*60*60);
            var m = Math.floor(diff/(60));
            diff = diff-(m*60);
            var s = diff;
            
            document.getElementById("highlight").value = pad(h)+":"+pad(m)+":"+pad(s);
            document.getElementById("highlightName").value = "<?php echo $stream->name ?>" + "_" + pad(h)+"-"+pad(m)+"-"+pad(s);
            document.getElementById("time-elapsed").innerHTML = pad(h)+":"+pad(m)+":"+pad(s);
        }

        timer = setInterval(updateClock, 1000);
    });

</script>
@endsection