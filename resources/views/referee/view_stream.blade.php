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
    .fixture-date-time, .fixture-venue
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
<h1 class="game-header">Live Fixture</h1>
<p class="fixture-venue">{{$venue->name}}</p>
<form id="game" action="/referee/update-scores" method="post" name="update-scores" onsubmit="return confirm('Are you sure?');">
@csrf
<input name="fixture_id" type="text" value="{{$fixture->id}}" hidden readonly />
<input id="highlight" name="highlight_time" type="text" hidden />
<input id="highlightName" name="highlight_name" type="text" hidden />
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
        <hr />
            <table class="table table-sm table-dark text-center">
                <thead>
                    <tr>
                    <th scope="col">Team</th>
                    <th scope="col">Goals</th>
                    <th scope="col">Add</th>
                    <th scope="col">Sub</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                    <td>{{$fixture->team_a}}</td>
                    @if ($fixture->team_a_goals)
                        <input type="text" name="team_a_scored" id="teamaScored" value="{{$fixture->team_a_goals}}" hidden />
                        <td id="team_a_scored_goals">{{$fixture->team_a_goals}}</td>
                    @else
                        <input type="text" name="team_a_scored" id="teamaScored" value="0" hidden />  
                        <td id="team_a_scored_goals">0</td>                 
                    @endif
                    <td><button type="button" class="btn btn-warning" id="team_a_scored"><i class="fas fa-plus"></i></button></td>
                    <td><button type="button" class="btn btn-warning" id="team_a_subtract"><i class="fas fa-minus"></i></button></td>                    
                    </tr>
                    <tr>
                    <td>{{$fixture->team_b}}</td>
                    @if ($fixture->team_b_goals)
                        <input type="text" name="team_b_scored" id="teambScored" value="{{$fixture->team_b_goals}}" hidden />
                        <td id="team_b_scored_goals">{{$fixture->team_b_goals}}</td>
                    @else
                        <input type="text" name="team_b_scored" id="teambScored" value="0" hidden />  
                        <td id="team_b_scored_goals">0</td>                 
                    @endif
                    <td><button type="button" class="btn btn-warning" id="team_b_scored"><i class="fas fa-plus"></i></button></td>
                    <td><button type="button" class="btn btn-warning" id="team_b_subtract"><i class="fas fa-minus"></i></button></td>                    
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <br />
    <div class="row">
        <div class="col-12">
            <button type="submit" class="btn btn-outline-warning btn-lg btn-block" name="update-scores-button">Update Scores</button>
            <div id="stream_status"></div>
        </div>    
    </div>
    <hr />
</div>
</form>
<div class="container-fluid">
<!-- <div class="row">
    <div class="col-12">
        <form name="reconnect-stream" action="/referee/dashboard" method="post">
        {{ csrf_field() }}
            <input type="text" name="name" value="{{str_replace(' ', '_', $fixture->team_a.' '.$fixture->team_b.' '.str_replace(':', '-', $fixture->date_time))}}" hidden readonly/>
            <input type="text" name="stream_id" value="{{$fixture->stream_id}}" hidden readonly />
            <input type="text" name="venue_id" value="{{$fixture->venue_id}}" hidden readonly />
            <input type="text" name="fixture_id" value="{{$fixture->id}}" hidden readonly />
            <input type="text" name="storage" value="VOD_STORAGE_2" hidden readonly />
            <input type="submit" class="btn btn-outline-info btn-lg btn-block" name="reconnectStreamBtn" value="Reconnect Stream" />
        </form>
    </div>    
</div> -->
<div class="row">
    <div class="col-12">
        <form id="stop_game" method="post" action="/referee/stop-stream" name="stopStream">
        {{ csrf_field() }}
        <input type="hidden" name="stream_name" value="{{$stream->name}}" readonly required />
        <input type="hidden" name="stream_id" value="{{$stream->id}}" readonly required />
        <input type="hidden" name="app_name" value="{{$venue->wow_app_name}}" readonly required/>
        <input type="submit" class="btn btn-outline-danger btn-lg btn-block" name="stopStreamBtn" value="Stop Stream" />
        </form>
    </div>    
</div>
<hr />
<div class="row">
    <div class="col-12">
        <a href="/referee/dashboard/" class="btn btn-outline-info btn-lg btn-block">Back to Dashboard</a>
    </div>    
</div>
</div>
<div class="badge badge-secondary" id="time-elapsed" style="color: #ffffff"></div>

@endsection

@section('scripts')
<script type="text/javascript">

    $( document ).ready(function() {
        
        $('#team_a_scored').click(function() {
            $("#teamaScored").val(parseInt($('#teamaScored').val(), 10)+1);
            $("#team_a_scored_goals").html(parseInt($('#team_a_scored_goals').html(), 10)+1);
        });
        $('#team_b_scored').click(function() {
            $("#teambScored").val(parseInt($('#teambScored').val(), 10)+1);
            $("#team_b_scored_goals").html(parseInt($('#team_b_scored_goals').html(), 10)+1);
        });
        $('#team_a_subtract').click(function() {
            if($("#teamaScored").val() != 0) {
                $("#teamaScored").val(parseInt($('#teamaScored').val(), 10)-1);
                $("#team_a_scored_goals").html(parseInt($('#team_a_scored_goals').html(), 10)-1);
            }
        });
        $('#team_b_subtract').click(function() {
            if($("#teambScored").val() != 0) {
                $("#teambScored").val(parseInt($('#teambScored').val(), 10)-1);
                $("#team_b_scored_goals").html(parseInt($('#team_b_scored_goals').html(), 10)-1);
            }
        });

        var startDateTime = new Date("<?php echo $fixture->date_time ?>"); // YYYY (M-1) D H m s ms (start time and date from DB)
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

        // function get_stream_statistics(){
        //     $.ajax({
        //         type: "GET",
        //         url: "/api/stream-streamfiles",
        //         async: false,
        //         success: function(response) {
        //             console.log(response)
        //             if(response.data.uptime == 0) {
        //                 $("#stream_status").html("disconnected");
        //             } else {
        //                 $("#stream_status").html("connected");
        //             }
        //             setTimeout(function(){get_stream_statistics();}, 10000);
        //         }
        //     });  
        // }    
        // get_stream_statistics();
    });

</script>
@endsection