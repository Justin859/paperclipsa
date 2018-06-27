@extends('layouts.app')

@section('styles')
<link rel="stylesheet" type="text/css" href="{{asset('node_modules/timepicker/jquery.timepicker.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('node_modules/js-datepicker/datepicker.css')}}">
@endsection

@section('content')

<br />
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header"><strong>{{ __('Edit Fixture') }}</strong> {{$stream->name}}</div>
                <div class="card-body">
                    <form method="POST" action="/edit/fixture/{{$fixture->id}}/save">
                        @csrf
                        <div class="form-group row">
                            <label for="team_a" class="col-sm-2 col-form-label text-md-right">{{ __('Team A') }}</label>
                            <div class="col-md-10">
                                <select class="form-control{{ $errors->has('team_a') ? ' is-invalid' : '' }}" autocomplete="off" name="team_a" id="target_a" required autofocus>
                                <!-- <option disabled selected="selected"> -- Select First Team -- </option> -->
                                @foreach($teams as $team)
                                    @if($team->name == $fixture->team_a)
                                    <option value="{{$team->name}}" selected="selected">{{$team->name}}</option>
                                    @else
                                    <option value="{{$team->name}}">{{$team->name}}</option>
                                    @endif
                                @endforeach
                                </select>
                                @if ($errors->has('team_a'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('team_a') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="team_b" class="col-sm-2 col-form-label text-md-right">{{ __('Team B') }}</label>
                            <div class="col-md-10">
                                <select class="form-control{{ $errors->has('team_b') ? ' is-invalid' : '' }}" autocomplete="off" name="team_b" id="target_b" required>
                                <!-- <option disabled selected="selected"> -- Select First Team -- </option> -->
                                @foreach($teams as $team)
                                    @if($team->name == $fixture->team_b)
                                    <option value="{{$team->name}}" selected="selected">{{$team->name}}</option>
                                    @else
                                    <option value="{{$team->name}}">{{$team->name}}</option>
                                    @endif
                                @endforeach
                                </select>
                                @if ($errors->has('team_b'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('team_b') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="type" class="col-sm-2 col-form-label text-md-right">{{ __('Type') }}</label>

                            <div class="col-md-10">
                                <select class="form-control{{ $errors->has('fixture_type') ? ' is-invalid' : '' }}" autocomplete="off" value="{{ old('fixture_type') }}" name="fixture_type" id="fixture_type" required>
                                    @if($fixture->type == 'match')
                                    <option value="match" selected="selected">Match</option>
                                    @else
                                    <option value="match">Match</option>
                                    @endif
                                    @if($fixture->type == 'tournament')
                                    <option value="tournament" selected="selected">Tournament</option>
                                    @else
                                    <option value="tournament">Tournament</option>
                                    @endif
                                    @if($fixture->type == 'training')
                                    <option value="training" selected="selected">Training</option>
                                    @else
                                    <option value="training">Training</option>
                                    @endif
                                </select>
                                @if ($errors->has('fixture_type'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('fixture_type') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <input type="hidden" name="name" value="{{$stream->name}}" id="streamName" required>
                        <input type="hidden" name="startTime" id="startTime" value="{{str_replace(':', '-', str_replace(' ', '_', str_replace('-', '_', $fixture->date_time)))}}" required>
                        <input type="hidden" name="date_time" id="date_time" value="{{$fixture->date_time}}" required>
                        <input type="hidden" name="venue_id" id="fixture_venue_id" value="{{$venue->id}}" required>
                        <div class="form-group row">
                            <label for="field" class="col-sm-2 col-form-label text-md-right">Field</label>
                            <div class="col-md-10">
                            <select class="form-control{{ $errors->has('camera_port') ? ' is-invalid' : '' }}" name="camera_port">
                            @foreach($field_names as $key=>$field_name)
                                @if($ports[$key] == $stream->field_port)
                                <option value="{{$ports[$key]}}" selected="selected">{{$field_name}}</option>
                                @else
                                <option value="{{$ports[$key]}}">{{$field_name}}</option>
                                @endif
                            @endforeach
                            </select>
                            @if ($errors->has('camera_port'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('camera_port') }}</strong>
                                </span>
                            @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="field" class="col-sm-2 col-form-label text-md-right">Time</label>
                            <div class="col-md-10">
                            <input type="text" class="form-control{{ $errors->has('time') ? ' is-invalid' : '' }}" name="time" id="time" style="display:none;" required>
                            @if ($errors->has('time'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('time') }}</strong>
                                </span>
                            @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="field" class="col-sm-2 col-form-label text-md-right">Date</label>
                            <div class="col-md-10">
                            @if($user_is_admin)
                            <input type="text" class="form-control{{ $errors->has('date') ? ' is-invalid' : '' }}" name="date" id="date" readonly required>
                            @else
                            <input type="text" class="form-control{{ $errors->has('date') ? ' is-invalid' : '' }}" name="date" id="date" readonly required disabled>
                            @endif
                            @if ($errors->has('date'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('date') }}</strong>
                                </span>
                            @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12  text-md-right" align="right">
                            <div class="btn-group" role="group" aria-label="Basic example">
                                @if($user_is_admin)
                                <a href="/admin/dashboard" class="btn btn-danger"><i class="fas fa-ban"></i> {{ __('Cancel') }}</a>
                                @else
                                <a href="/referee/dashboard" class="btn btn-danger"><i class="fas fa-ban"></i> {{ __('Cancel') }}</a>
                                @endif
                                <button type="submit" class="btn btn-warning"><i class="far fa-save"></i> {{ __('Save Changes') }}</button>
                            </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')

<script type="text/javascript" src="{{asset('node_modules/moment/min/moment.min.js')}}"></script>
<script type="text/javascript" src="{{asset('node_modules/timepicker/jquery.timepicker.min.js')}}"></script>
<script type="text/javascript" src="{{asset('node_modules/js-datepicker/datepicker.min.js')}}"></script>
<script type="text/javascript" src="{{asset('node_modules/moment/min/moment.min.js')}}"></script>
<script>

$( document ).ready(function() {

    //$("#date_time").val(moment().format('Y-MM-D HH:mm:ss'));
    //$("#startTime").val(moment().format('Y_MM_D_HH-mm-ss'));

    var changeStreamName = function() {

        teamA = $("#target_a").val();
        teamB = $("#target_b").val();
        startTime = $("#date").val() + "_" + $("#time").val().replace(":", "-");
        streamName = teamA.replace(/\ /g,"_") + "_VS_" + teamB.replace(/\ /g,"_") + "_" + startTime;

        $("#streamName").val(streamName);
        $("#startTime").val(startTime)
        $("#date_time").val($("#date").val().replace(/_/g, "-") + " " + $("#time").val());
    }

    var checkTeams = function() {
        var myOpt = [];
        $("select").each(function () {
            myOpt.push($(this).val());
        });
        $("select").each(function () {
            $(this).find("option").prop('hidden', false);
            var sel = $(this);
            $.each(myOpt, function(key, value) {
                if((value != "") && (value != sel.val())) {
                    sel.find("option").filter('[value="' + value +'"]').prop('hidden', true);
                }
            });
        });
    }
    checkTeams();

    $('#time').timepicker({
        'scrollDefault': 'now',
        'timeFormat': 'H:i',
        'step': 15,
        'useSelect': true
    });

    $('#time').timepicker('setTime', new Date("<?php echo $fixture->date_time ?>"));

    $('.ui-timepicker-select').addClass('form-control');    

      const picker = datepicker('#date', {
        minDate: new Date("<?php echo date('Y-m-d') ?>"),
        dateSelected: new Date("<?php echo $fixture->date_time ?>"),
        formatter: function(el, date) {
        // This will display the date as `1/1/2017`.
        el.value = moment(date).format("YYYY_MM_DD").toString().replace(/\//gi, '_');
        },
        onHide: function(instance) {
            changeStreamName();
        },
    });

    $('select').change(function() {
        var myOpt = [];
        $("select").each(function () {
            myOpt.push($(this).val());
        });
        $("select").each(function () {
            $(this).find("option").prop('hidden', false);
            var sel = $(this);
            $.each(myOpt, function(key, value) {
                if((value != "") && (value != sel.val())) {
                    sel.find("option").filter('[value="' + value +'"]').prop('hidden', true);
                }
            });
        });
    });    

    $('#configreset').click(function(){
        $('#configform')[0].reset();
        $("select").each(function () {
            $(this).find("option").prop('hidden', false);
            changeStreamName();
        });
    });        

    $('.ui-timepicker-select').change(function() {
        changeStreamName();
    });

    $("#target_a").change(function() {
        changeStreamName();
    });

    $("#target_b").change(function() {
        changeStreamName();
    });   
    
    $('#configform').submit(function() {
        changeStreamName();
    });             
});

</script>

@endsection