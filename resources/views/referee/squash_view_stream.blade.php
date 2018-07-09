@extends('layouts.app')

@section('styles')
<style>
.btn
{
    border-radius: 0px;
    margin-bottom: 10px;
}
.points
{
    background-color: #686868;
    border-right: solid 1px #ffffff;
    border-left: solid 1px #ffffff;

}
.point
{
    color: #ffffff;
    font-size: 48px;
}
.player
{
    color: #FFCC00;
    font-size: 24px;
    border-right: solid 1px #ffffff;
    border-left: solid 1px #ffffff;
}
.point-btn
{
    border-right: solid 1px #ffffff;
    border-left: solid 1px #ffffff;
}

.point-button
{
    margin-top: 15px;
}

#spinner-text
{
    position: fixed;
    z-index: 999;
    color: #ffffff;
    font-size: 24px;
    height: 4em;
    width: 4em;
    overflow: show;
    margin: auto;
    top: 0;
    left: 0;
    bottom: 0;
    right: 0;
}

#spinner
{
    position: fixed;
    z-index: 999;
    height: 2em;
    width: 2em;
    overflow: show;
    margin: auto;
    top: 15px;
    left: 25px;
    bottom: 0;
    right: 0;
}

#spinner:before {
    content: '';
    display: block;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0,0,0,0.8);
}

#spinner-text:before
{
    content: '';
    display: block;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
}

</style>
@endsection

@section('content')

<div class="container-fluid">
    <div class="row"><!--Rounds Won -->
        <div class="col-6 points text-center">
            <span class="point">{{$player_1_rounds_won}}</span>
        </div> <!-- Player 1 -->
        <div class="col-6 points text-center">
            <span class="point">{{$player_2_rounds_won}}</span>
        </div> <!-- Player 2 -->
    </div>
    <div class="row"> <!-- Player Names -->
        <div class="col-6 text-center player">{{$fixture->player_1}}</div> <!-- Player 1 -->
        <div class="col-6 text-center player">{{$fixture->player_2}}</div> <!-- Player 2 -->
    </div>
    <div class="row"> <!-- Points in Round -->
        <div class="col-6 points text-center">
            <span id="player_1_points" class="point">{{$player_1_round_points}}</span>
        </div> <!-- Player 1 -->
        <div class="col-6 points text-center">
            <span id="player_2_points" class="point">{{$player_2_round_points}}</span>
        </div> <!-- Player 2 -->
    </div>
    <div class="row point-btns"> <!-- Add Point -->
        <div class="col-6 point-btn">
        <button type="button" class="btn btn-warning btn-lg btn-block point-button" id="player_1_scored"><i class="fas fa-plus"></i></button>
        </div> <!-- Player 1 -->
        <div class="col-6 point-btn">
        <button type="button" class="btn btn-warning btn-lg btn-block point-button" id="player_2_scored"><i class="fas fa-plus"></i></button>
        </div> <!-- Player 2 -->
    </div>
    <div class="row"> <!-- Subtract Point -->
        <div class="col-6 point-btn">
        <button type="button" class="btn btn-warning btn-lg btn-block point-button" id="player_1_subtract"><i class="fas fa-minus"></i></button>
        </div> <!-- Player 1 -->
        <div class="col-6 point-btn">
        <button type="button" class="btn btn-warning btn-lg btn-block point-button" id="player_2_subtract"><i class="fas fa-minus"></i></button>
        </div> <!-- Player 2 -->
    </div>
    <br />
    <div class="row"> <!-- End Game -->
        <div class="col-6">
        <form id="start_rally" action="/referee/squash/start-recording" method="post" name="start-rally"><!-- work in progress start and stop recording -->
        @csrf
            <input type="number" name="fixture_id" value="{{$fixture->id}}" hidden />
            <button id="StartRally" type="submit" class="btn btn-outline-warning btn-lg btn-block" name="updateScoresBtn">Start Rally</button>
         </form>
        </div>
        <div class="col-6">
        <form id="stop_rally" action="/referee/squash/stop-recording" method="post" name="stop-rally">
        @csrf
            <input type="number" name="fixture_id" value="{{$fixture->id}}" hidden />
            <button id="EndRally" type="submit" class="btn btn-outline-warning btn-lg btn-block" name="updateScoresBtn">End Rally</button>
         </form>

        </div>
    </div>

    <div class="row"> <!-- End Game -->
        <div class="col-6">
            <form id="start_next_round" method="post" action="/referee/squash/start-next-round" name="stopStream"><!-- work in progress start next round -->
            {{ csrf_field() }}
                <input type="number" name="fixture_id" value="{{$fixture->id}}" hidden />
                <input type="submit" class="btn btn-outline-success btn-lg btn-block" name="stopStreamBtn" value="End Game" />
            </form>
        </div>
        <div class="col-6">
            <form id="stop_game" method="post" action="/referee/squash/stop-stream" name="stopStream">
            {{ csrf_field() }}
                <input type="hidden" name="stream_name" value="{{$stream->name}}" readonly required />
                <input type="hidden" name="squash_stream_id" value="{{$stream->id}}" readonly required />
                <input type="hidden" name="app_name" value="{{$venue->wow_app_name}}" readonly required/>
                <input type="submit" class="btn btn-outline-danger btn-lg btn-block" name="stopStreamBtn" value="End Match" />
            </form>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <a href="/referee/squash/dashboard/" class="btn btn-outline-info btn-lg btn-block">Back to Dashboard</a>
        </div>    
    </div>
    <hr />
    <div class="row">
        <div class="col-12">
            <table class="table table-dark  text-center">
                <thead>
                    <tr>
                        <th scope="col">Game</th>
                        <th scope="col">{{$fixture->player_1}}</th>
                        <th scope="col">{{$fixture->player_2}}</th>
                        <th scope="col"></th>
                    </tr>
                </thead>
            <tbody>
                @foreach($rounds as $key=>$round)
                    <tr>
                        <td>{{$key}}</td>
                        <td>{{$round_points[$key]["player_1"]}}</td>
                        <td>{{$round_points[$key]["player_2"]}}</td>
                        <td></td>
                    </tr>
                @endforeach
            </tbody>
            </table>
        </div>
    </div>
</div>

<!-- spinner -->
<div class="spinner-wrapper" style="display:none">
    <div id='spinner'></div>
    <div id="spinner-text">Starting Stream..</div>
</div>
<!-- end spinner -->

@endsection


@section('scripts')

<script type="text/javascript" src="{{asset('node_modules/moment/min/moment.min.js')}}"></script>
<script type="text/javascript" src="{{asset('node_modules/spin/dist/spin.min.js')}}"></script>

<script type="text/javascript">

    var opts = {
        lines: 12, // The number of lines to draw
        length: 40, // The length of each line
        width: 20, // The line thickness
        radius: 50, // The radius of the inner circle
        scale: 1.08, // Scales overall size of the spinner
        corners: 1, // Corner roundness (0..1)
        color: '#D50000', // CSS color or array of colors
        fadeColor: 'transparent', // CSS color or array of colors
        speed: 1, // Rounds per second
        rotate: 0, // The rotation offset
        animation: 'spinner-line-fade-more', // The CSS animation name for the lines
        direction: 1, // 1: clockwise, -1: counterclockwise
        zIndex: 2e9, // The z-index (defaults to 2000000000)
        className: 'spinner', // The CSS class to assign to the spinner
        top: '50%', // Top position relative to parent
        left: '60%', // Left position relative to parent
        shadow: '0 0 1px transparent', // Box-shadow for the lines
        position: 'absolute' // Element positioning
    };

    var target = document.getElementById('spinner');
    var spinner = new Spinner(opts).spin(target);

    var showSpinner = function() {
        $('.spinner-wrapper').css('display','block');
        $('#areYouSure').modal('hide');
        setTimeout(
        function() {
            $('#spinner-text').html('Checking Camera...');
        }, 3000);
    }

    var update_score = function(player, calc) {
        $.ajax({
            type: "POST",
            url: "/api/update-squash-score?fixture_id=" + "<?php echo $fixture->id ?>" + "&player=" +player+ "&point=" + calc,
            async: false,
            success: function(response) {
                console.log(response);
            },
            error: function(response) {
                console.log(response);
            }

        });
    }

    $('#player_1_scored').click(function() {
        $("#player1Scored").val(parseInt($('#player1Scored').val(), 10)+1);
        $("#player_1_points").html(parseInt($('#player_1_points').html(), 10)+1);
        update_score("player_1", "add");
    });
    $('#player_2_scored').click(function() {
        $("#player2Scored").val(parseInt($('#player2Scored').val(), 10)+1);
        $("#player_2_points").html(parseInt($('#player_2_points').html(), 10)+1);
        update_score("player_2", "add");
    });
    $('#player_1_subtract').click(function() {
        if($("#player1Scored").val() != 0) {
            $("#player1Scored").val(parseInt($('#player1Scored').val(), 10)-1);
            $("#player_1_points").html(parseInt($('#player_1_points').html(), 10)-1);
            update_score("player_1", "subtract");
        }
    });
    $('#player_2_subtract').click(function() {
        if($("#player2Scored").val() != 0) {
            $("#player2Scored").val(parseInt($('#player2Scored').val(), 10)-1);
            $("#player_2_points").html(parseInt($('#player_2_points').html(), 10)-1);
            update_score("player_2", "subtract");
        }
    });

    $('#player_1_subtract').prop('disabled', true);
    $('#player_2_subtract').prop('disabled', true);
    
    $('#player_1_scored').click(function() {
        $(this).prop('disabled', true);
        $('#player_1_subtract').prop("disabled", false);
    });

    $('#player_1_subtract').click(function() {
        $(this).prop('disabled', true);
        $('#player_1_scored').prop('disabled', false);
    });

    $('#player_2_scored').click(function() {
        $(this).prop('disabled', true);
        $('#player_2_subtract').prop("disabled", false);
    });

    $('#player_2_subtract').click(function() {
        $(this).prop('disabled', true);
        $('#player_2_scored').prop('disabled', false);
    });    

    $('#start_rally').submit(function() {
        showSpinner();
    });

</script>

@endsection
