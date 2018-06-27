@extends('layouts.app')

@section('styles')

<link rel="stylesheet" type="text/css" href="{{asset('node_modules/timepicker/jquery.timepicker.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('node_modules/js-datepicker/datepicker.css')}}">
<style>
    .fa-plug, .fa-trash, .fa-edit {
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

</style>
@endsection

@section('content')

<div class="container" style="color:#ffffff;">

    <div class="row">
        <div class="col-12">
        <h1>Admin Dashboard</h1>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <h3>You are logged in as an admin for {{$venue->name}}.</h3>
        </div>
    </div>
    <div class="row d-flex d-sm-flex justify-content-end p-2">
        <button class="btn btn-large btn-danger" id="newStream" data-toggle="modal" data-target="#areYouSure"><i class="far fa-plus-square"></i>&nbsp;&nbsp;ADD FIXTURE</button>
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
                    <th scope="col">Delete</th>
                    <th scope="col">Status</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($fixtures as $key=>$fixture)
                    <tr>
                    <th scope="row">{{$key + 1}}</th>
                        <td>{{$fixture->team_a}} VS {{$fixture->team_b}}</td>
                        <td>{{$fixture->date_time}}</td>
                        <td>
                            @if(\App\Stream::find($fixture->stream_id)->stream_type !== "live")
                            <a class="btn" href="/edit/fixture/{{$fixture->id}}"><i class="far fa-edit"></i></a>
                            @else
                            <a class="btn disabled" href="/edit/fixture/{{$fixture->id}}"><i class="far fa-edit"></i></a>
                            @endif
                        </td>
                        <td>
                        <form name="form_{{$key + 1}}" action="/admin/dashboard/delete" method="post" onsubmit="return confirm('Are you sure you want to delete {{$fixture->team_a}} VS {{$fixture->team_b}}?');">
                            @csrf
                            <input type="text" name="stream_id" value="{{$fixture->stream_id}}" hidden readonly />
                            <input type="text" name="fixture_id" value="{{$fixture->id}}" hidden readonly />
                            @if(\App\Stream::find($fixture->stream_id)->stream_type !== 'live')
                            <button type="submit" name="submit_{{$key + 1}}" class="btn" style="background-color: #212529"><i class="fas fa-trash"></i></button>
                            @else
                            <button type="submit" name="submit_{{$key + 1}}" class="btn disabled" style="background-color: #212529"><i class="fas fa-trash"></i></button>
                            @endif
                        </form>  
                        </td>
                        <td>
                        @if(\App\Stream::find($fixture->stream_id))
                            @if(\App\Stream::find($fixture->stream_id)->stream_type == 'live')
                            <i class="fas fa-plug" style="color:green;"></i>
                            @else
                            <i class="fas fa-plug" style="color:grey;"></i>
                            @endif
                        @else
                        <i class="fas fa-plug" style="color:grey;"></i>
                        @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <br />
    <hr />
    <div class="row">
        <div class="col-12">
            <h3>Upcoming Fixtures</h3>
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
                    <th scope="col">Delete</th>
                    <th scope="col">Status</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($upcoming_fixtures as $key=>$fixture)
                    <tr>
                    <th scope="row">{{$key + 1}}</th>
                        <td>{{$fixture->team_a}} VS {{$fixture->team_b}}</td>
                        <td>{{$fixture->date_time}}</td>
                        <td>
                            @if(\App\Stream::find($fixture->stream_id)->stream_type !== "live")
                            <a class="btn" href="/edit/fixture/{{$fixture->id}}"><i class="far fa-edit"></i></a>
                            @else
                            <a class="btn disabled" href="/edit/fixture/{{$fixture->id}}"><i class="far fa-edit"></i></a>
                            @endif
                        </td>
                        <td>
                        <form name="form_upcomming{{$key + 1}}" action="/admin/dashboard/delete" method="post" onsubmit="return confirm('Are you sure you want to delete {{$fixture->team_a}} VS {{$fixture->team_b}}?');">
                            @csrf
                            <input type="text" name="stream_id" value="{{$fixture->stream_id}}" hidden readonly />
                            <input type="text" name="fixture_id" value="{{$fixture->id}}" hidden readonly />
                            @if(\App\Stream::find($fixture->stream_id)->stream_type !== 'live')
                            <button type="submit" name="submit_upcomming{{$key + 1}}" class="btn" style="background-color: #212529"><i class="fas fa-trash"></i></button>
                            @else
                            <button type="submit" name="submit_upcomming{{$key + 1}}" class="btn disabled" style="background-color: #212529"><i class="fas fa-trash"></i></button>
                            @endif
                        </form>  
                        </td>
                        <td>
                        @if(\App\Stream::find($fixture->stream_id))
                            @if(\App\Stream::find($fixture->stream_id)->stream_type == 'live')
                            <i class="fas fa-plug" style="color:green;"></i>
                            @else
                            <i class="fas fa-plug" style="color:grey;"></i>
                            @endif
                        @else
                        <i class="fas fa-plug" style="color:grey;"></i>
                        @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>            
        </div>
    </div>
</div>

@endsection

@section('modal')

<!-- Modal for adding fixture -->
<div id="areYouSure" class="modal" tabindex="-1" role="dialog">
    <form method="post" action="/admin/dashboard" id="configform" name="create_fixture">
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
                        <label for="team_a" class="col-2 col-form-label">Team A</label>
                        <div class="col-10">
                            <select class="form-control{{ $errors->has('team_a') ? ' is-invalid' : '' }}" autocomplete="off" name="team_a" id="target_a">
                            @if(old('team_a'))
                            <option disabled value=""> -- Select Second Team -- </option>
                            @else
                            <option disabled value="" selected="selected"> -- Select Second Team -- </option>
                            @endif   
                            @foreach($teams as $team)
                            @if(old('team_a') == $team->name)
                            <option selected="selected" value="{{$team->name}}">{{$team->name}}</option>
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
                        <label for="team_b" class="col-2 col-form-label">Team B</label>
                        <div class="col-10">
                        <select class="form-control{{ $errors->has('team_b') ? ' is-invalid' : '' }}" autocomplete="off" name="team_b" id="target_b">
                        @if(old('team_b'))
                        <option disabled value=""> -- Select Second Team -- </option>
                        @else
                        <option disabled value="" selected="selected"> -- Select Second Team -- </option>
                        @endif
                        @foreach($teams as $team)
                            @if(old('team_b') == $team->name)
                            <option selected="selected" value="{{$team->name}}">{{$team->name}}</option>
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
                        <label for="fixture_type" class="col-2 col-form-label">Type</label>
                        <div class="col-10">
                            <select class="form-control{{ $errors->has('fixture_type') ? ' is-invalid' : '' }}" autocomplete="off" name="fixture_type" id="fixture_type" required>
                                @if(old('fixture_type') == 'match')
                                <option value="match" selected="selected">Match</option>
                                @else
                                <option value="match">Match</option>
                                @endif
                                @if(old('fixture_type') == 'tournament')
                                <option value="tournament" selected="selected">Tournament</option>
                                @else
                                <option value="tournament">Tournament</option>
                                @endif
                                @if(old('fixture_type') == 'training')
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
                    <input type="hidden" name="name" value="" id="streamName" required>
                    <input type="hidden" name="startTime" id="startTime" value="{{date('Y_m_d_H-i-s')}}" required>
                    <input type="hidden" name="date_time" id="date_time" value="{{date('Y-m-d H:i:s')}}" required>
                    <input type="hidden" name="venue_id" id="fixture_venue_id" value="{{$venue->id}}" required>
                    <div class="form-group row">
                        <label for="field" class="col-2 col-form-label">Field</label>
                        <div class="col-10">
                        <select class="form-control{{ $errors->has('camera_port') ? ' is-invalid' : '' }}" name="camera_port">
                        @foreach($field_names as $key=>$field_name)
                        @if(old('camera_port') == $ports[$key])
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
                        <label for="field" class="col-2 col-form-label">Time</label>
                        <div class="col-10">
                        <input type="text" class="form-control{{ $errors->has('time') ? ' is-invalid' : '' }}" name="time" id="time" style="display:none;" required>
                        @if ($errors->has('time'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('time') }}</strong>
                            </span>
                        @endif
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="field" class="col-2 col-form-label">Date</label>
                        <div class="col-10">
                        <input type="text" class="form-control{{ $errors->has('date') ? ' is-invalid' : '' }}" name="date" id="date" readonly required>
                        @if ($errors->has('date'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('date') }}</strong>
                            </span>
                        @endif
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="submit" class="btn btn-warning" name="new_stream" value="Create Fixture" />
                    <button type="button" class="btn btn-secondary" name="start_new" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </form>
</div>
<!-- End of Modal -->

@endsection

@section('scripts')
<script type="text/javascript" src="{{asset('node_modules/timepicker/jquery.timepicker.min.js')}}"></script>
<script type="text/javascript" src="{{asset('node_modules/js-datepicker/datepicker.min.js')}}"></script>
<script type="text/javascript" src="{{asset('node_modules/moment/min/moment.min.js')}}"></script>
@if ($errors->any())
	<script type="text/javascript">
    $(document).ready(function() {
        $('#areYouSure').modal('show');
        $('#areYouSure').on('shown.bs.modal', function () {
        $('#newStream').trigger('focus');
        });

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
    </script>
@endif
<script type="text/javascript">

$(document).ready(function() {
    $('#areYouSure').on('shown.bs.modal', function () {
    $('#newStream').trigger('focus');
    });
    
    $('#time').timepicker({
        'scrollDefault': 'now',
        'timeFormat': 'H:i',
        'step': 15,
        'useSelect': true
    });
    $('#time').timepicker('setTime', new Date(
        "<?php 
         if(old('date_time'))
         {
             echo old('date_time');
         } else {
             echo date('Y-m-d H:i:s');
         }   
         ?>"
    ));

    $('.ui-timepicker-select').addClass('form-control');
    $("#date_time").val(moment().format('Y-MM-D HH:mm:ss'));
    $("#startTime").val(moment().format('Y_MM_D_HH-mm-ss'));

    var changeStreamName = function() {

        var teamA = "";
        var teamB = "";
        var startTime = "";
        var streamName = "";

        if($("#target_a").val()) {
            teamA = $("#target_a").val();
        }

        if($("#target_b").val()) {
            teamB = $("#target_b").val();
        }

        if($("#date").val() && $("#time").val()) {
            startTime = $("#date").val() + "_" + $("#time").val().replace(":", "-");
            streamName = teamA.replace(/\ /g,"_") + "_VS_" + teamB.replace(/\ /g,"_") + "_" + startTime;
            $("#streamName").val(streamName);
            $("#startTime").val(startTime)
            $("#date_time").val($("#date").val().replace(/_/g, "-") + " " + $("#time").val());
        }

    }

   const picker = datepicker('#date', {
        minDate: new Date("<?php echo date('Y-m-d')?>"),
        dateSelected: new Date(
            "<?php 
             if(old('date_time'))
             {
                 echo old('date_time');
             } else {
                 echo date('Y-m-d H:i:s');
             }  
             ?>"
        ),
        formatter: function(el, date) {
        // This will display the date as `1/1/2017`.
        el.value = moment(date).format("YYYY_MM_DD").toString().replace(/\//gi, '_');
        },
        onHide: function(instance) {
            changeStreamName();
        },
    });

    $('.ui-timepicker-select').change(function() {
        changeStreamName();
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
