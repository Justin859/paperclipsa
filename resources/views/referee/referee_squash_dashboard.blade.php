@extends('layouts.app')

@section('styles')
<style>
.fa-plug, .fa-dot-circle, .fa-eye, .fa-edit {
        font-size: 24px;
    }
    table th {
        text-align: center; 
    }

    .table {
        margin: auto;
    }

    td {
        vertical-align: middle !important;
    }
    hr {
        border: 0;
        clear:both;
        display:block;
        background-color:#808080;
        height: 1px;
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
<?php date_default_timezone_set ( 'Africa/Johannesburg' ) ?>
<div class="container" style="color:#ffffff;">
    <div class="row">
        <div class="col-12">
        <h1>Squash Referee Dashboard</h1>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <h2>Stream</h2>
            <h3>You are logged in as a referee for {{$venue->name}}.</h3>
        </div>
    </div>
    <div class="row d-flex d-sm-flex justify-content-end p-2">
        <button class="btn btn-large btn-danger" id="newStream" data-toggle="modal" data-target="#areYouSure"><i class="far fa-plus-square"></i>&nbsp;&nbsp;NEW GAME</button>
    </div>
    <hr />
    <div class="row">
        <div class="col-12">
            <h3>Todays Fixtures</h3>
        </div>
    </div>
    <hr /> 
    <div class="row">
        <div class="col-12 col-md-8 offset-md-2">
            <table class="table table-sm table-dark table-responsive-sm text-center">
                <thead>
                    <tr>
                    <th scope="col">#</th>
                    <th scope="col">Fixture</th>
                    <th scope="col">Time</th>
                    <th scope="col">Edit</th>
                    <th scope="col">Action</th>
                    <th scope="col">Status</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($fixtures as $key=>$fixture)
                    @if(\App\SquashStream::find($fixture->squash_stream_id)->stream_type != 'vod')
                    <tr>
                    <th scope="row">{{$key + 1}}</th>
                        <td>{{$fixture->player_1}} VS {{$fixture->player_2}}</td>
                        <td>{{$fixture->date_time}}</td>
                        <td>
                        @if(\App\SquashStream::find($fixture->squash_stream_id)->stream_type !== "live")
                            <a class="btn" href="/edit/fixture/{{$fixture->id}}"><i class="far fa-edit"></i></a>
                            @else
                            <a class="btn disabled" href="/edit/fixture/{{$fixture->id}}"><i class="far fa-edit"></i></a>
                            @endif
                        </td>
                        <td>
                        @if(\App\SquashStream::find($fixture->squash_stream_id))
                            <a href="/referee/squash/dashboard/fixture/{{\App\SquashFixture::find($fixture->id)->id}}/{{\App\SquashStream::find($fixture->squash_stream_id)->name}}"><i class="fas fa-eye" style="color:green;"></i></a>                      
                        @endif
                        </td>
                        <td>
                        @if(\App\SquashStream::find($fixture->squash_stream_id))
                            @if(\App\SquashStream::find($fixture->squash_stream_id)->stream_type == 'live')
                            <i class="fas fa-plug" style="color:green;"></i>
                            @else
                            <i class="fas fa-plug" style="color:grey;"></i>
                            @endif
                        @else
                        <i class="fas fa-plug" style="color:grey;"></i>
                        @endif
                        </td>
                    </tr>
                    @endif
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

@section('modal')

<!-- Modal for purchase request -->
<div id="areYouSure" class="modal" tabindex="-1" role="dialog">
    <form method="post" action="/referee/squash/dashboard" id="configform" name="start_new_stream">
    {{ csrf_field() }}
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Start Streaming Match</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group row">
                        <label for="player_1" class="col-3 col-form-label">Player 1</label>
                        <div class="col-9">
                            <input type="text" class="form-control{{ $errors->has('player_1') ? ' is-invalid' : '' }}" placeholder="Name of Player 1" name="player_1" id="target_a">
                            @if ($errors->has('player_1'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('player_1') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="player_2" class="col-3 col-form-label">Player 2</label>
                        <div class="col-9">
                        <input type="text" class="form-control{{ $errors->has('player_2') ? ' is-invalid' : '' }}" placeholder="Name of Player 2" name="player_2" id="target_b">
                        @if ($errors->has('player_2'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('player_2') }}</strong>
                            </span>
                        @endif
                        </div>
                    </div>
                    <input type="hidden" name="name" value="" id="streamName" required>
                    <input type="hidden" name="startTime" id="startTime" value="{{date('Y_m_d_H-i-s')}}" required>
                    <input type="hidden" name="date_time" id="date_time" value="{{date('Y-m-d H:i:s')}}" required>
                    <div class="form-group row">
                        <label for="field" class="col-3 col-form-label">Field</label>
                        <div class="col-9">
                        <select class="form-control" name="camera_port" required>
                        @foreach($field_names as $key=>$field_name)
                            @if(old('camera_port') == $ports[$key])
                            <option value="{{$ports[$key]}}" selected="selected">Court {{$key + 1}}</option>
                            @else
                            <option value="{{$ports[$key]}}">Court {{$key + 1}}</option>
                            @endif
                        @endforeach
                        </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="submit" class="btn btn-warning" name="new_stream" value="Start Game" />
                    <button type="button" class="btn btn-secondary" name="start_new" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </form>
</div>
<!-- End of Modal -->

@endsection

@section('scripts')

@if ($errors->any())
	<script type="text/javascript">
    $(document).ready(function() {
        $('#areYouSure').modal('show');
        $('#areYouSure').on('shown.bs.modal', function () {
        $('#newStream').trigger('focus');
        });
    });
    </script>
@endif

<script type="text/javascript" src="{{asset('node_modules/moment/min/moment.min.js')}}"></script>
<script type="text/javascript" src="{{asset('node_modules/spin/dist/spin.min.js')}}"></script>

<script type="text/javascript">

$( document ).ready(function() {
        $('#areYouSure').on('shown.bs.modal', function () {
        $('#newStream').trigger('focus');
        });

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

        var changeStreamName = function() {

            var player_1 = "";
            var player_2 = "";
            var streamName = "";

            $("#date_time").val(moment().format('Y-MM-D HH:mm:ss'));
            $("#startTime").val(moment().format('Y_MM_D_HH-mm-ss'));

            if($("#target_a").val()) {
                player_1 = $("#target_a").val();
            }
            if($("#target_a").val()) {
                player_2 = $("#target_b").val();
            }

            if($("#target_a").val() && $("#target_b").val()) {
            startTime = $("#startTime").val();
            streamName = player_1.replace(/\ /g,"_") + "_VS_" + player_2.replace(/\ /g,"_") + "_" + startTime;
            }

            $("#streamName").val(streamName);
        }

        var showSpinner = function() {
            $('.spinner-wrapper').css('display','block');
            $('#areYouSure').modal('hide');
            setTimeout(
            function() {
                $('#spinner-text').html('Checking Camera...');
            }, 3000);
        }

        $("#target_a").keyup(function() {
            changeStreamName();
        });

        $("#target_b").keyup(function() {
            changeStreamName();
        });
        $('#configform').submit(function() {
            showSpinner();
            changeStreamName();
        });
});

</script>
@endsection
