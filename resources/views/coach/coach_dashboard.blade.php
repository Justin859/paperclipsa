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
        <h1>Referee Dashboard</h1>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <h2>Session</h2>
            <h3>You are logged in as a coach for {{$venue->name}}.</h3>
        </div>
    </div>
    <div class="row d-flex d-sm-flex justify-content-end p-2">
        <button class="btn btn-large btn-danger" id="newStream" data-toggle="modal" data-target="#areYouSure"><i class="far fa-plus-square"></i>&nbsp;&nbsp;NEW STREAM</button>
    </div>
    <hr />
    <div class="row">
        <div class="col-12">
            <h3>Todays Sessions</h3>
        </div>
    </div>
    <hr />
    <div class="row">
        <div class="col-12 col-md-8 offset-md-2">
            <table class="table table-sm table-dark table-responsive-sm text-center">
                <thead>
                    <tr>
                    <th scope="col">#</th>
                    <th scope="col">Session</th>
                    <th scope="col">Time</th>
                    <th scope="col">Edit</th>
                    <th scope="col">Action</th>
                    <th scope="col">Status</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($ss_sessions as $key=>$ss_session)
                    <tr>
                    <th scope="row">{{$key + 1}}</th>
                        <td>{{$ss_session->age_group_id}}</td>
                        <td>{{$ss_session->date_time}}</td>
                        <td>
                        @if(\App\SoccerSchoolsStream::find($ss_session->ss_stream_id)->stream_type !== "live")
                            <a class="btn" href="/edit/session/{{$ss_session->id}}" data-toggle="modal" data-target="#editSession_{{$key}}"><i class="far fa-edit"></i></a>
                            @else
                            <a class="btn disabled" href="/edit/session/{{$ss_session->id}}"><i class="far fa-edit"></i></a>
                            @endif
                        </td>
                        <td>
                        @if(\App\SoccerSchoolsStream::find($ss_session->ss_stream_id))
                            @if(\App\SoccerSchoolsStream::find($ss_session->ss_stream_id)->stream_type == 'live')
                            <a href="/coach/dashboard/session/{{\App\SoccerSchoolsSession::find($ss_session->id)->id}}/{{\App\SoccerSchoolsStream::find($ss_session->ss_stream_id)->name}}"><i class="fas fa-eye" style="color:green;"></i></a>
                            @else
                            <form class="start-stream-form" name="form_{{$key + 1}}" action="/coach/dashboard" method="post">
                                @csrf
                                <input type="text" name="name" value="{{\App\SoccerSchoolsStream::find($ss_session->ss_stream_id)->name}}" hidden readonly/>
                                <input type="text" name="ss_stream_id" value="{{$ss_session->ss_stream_id}}" hidden readonly />
                                <input type="text" name="venue_id" value="{{$ss_session->venue_id}}" hidden readonly />
                                <input type="text" name="ss_session_id" value="{{$ss_session->id}}" hidden readonly />
                                <input type="text" name="ss_age_group" value="{{$ss_session->age_group_id}}" hidden readonly />
                                <input type="text" name="coach_id" value="{{$ss_session->coach_id}}" hidden readonly />
                                <input type="text" name="storage" value="VOD_STORAGE_2" hidden readonly/>
                                <input type="text" name="camera_port" value="{{\App\SoccerSchoolsStream::find($ss_session->ss_stream_id)->camera_port}}" required hidden>
                                <button type="submit" name="submit_{{$key + 1}}" class="btn" style="background-color: #212529"><i class="far fa-dot-circle" style="color:#D50000"></i></button>
                            </form>  
                            @endif
                        @else
                            <form class="start-stream-form" name="form_{{$key + 1}}" action="/coach/dashboard" method="post">
                                @csrf
                                <input type="text" name="name" value="{{\App\SoccerSchoolsStream::find($ss_session->ss_stream_id)->name}}" hidden readonly/>
                                <input type="text" name="ss_stream_id" value="{{$ss_session->ss_stream_id}}" hidden readonly />
                                <input type="text" name="venue_id" value="{{$ss_session->venue_id}}" hidden readonly />
                                <input type="text" name="ss_age_group" value="{{$ss_session->age_group_id}}" hidden readonly />
                                <input type="text" name="coach_id" value="{{$ss_session->coach_id}}" hidden readonly />
                                <input type="text" name="ss_session_id" value="{{$ss_session->id}}" hidden readonly />
                                <input type="text" name="storage" value="VOD_STORAGE_2" hidden readonly />
                                <input type="text" name="camera_port" value="{{\App\SoccerSchoolsStream::find($ss_session->ss_stream_id)->camera_port}}" required hidden>
                                <button type="submit" name="submit_{{$key + 1}}" class="btn" style="background-color: #212529"><i class="fas fa-video"></i></button>
                            </form>                            
                        @endif
                        </td>
                        <td>
                        @if(\App\SoccerSchoolsStream::find($ss_session->ss_stream_id))
                            @if(\App\SoccerSchoolsStream::find($ss_session->ss_stream_id)->stream_type == 'live')
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
    @if($response_recording != "null")
    @if(array_key_exists('streamrecorder', $response_recording))
    <hr />
    <div class="row">
        <div class="col-12">
            <h3>Session Recording Status</h3>
        </div>
    </div>
    <hr />
    <div class="row">
        <div class="col-12 col-md-8 offset-md-2">
            <table class="table table-sm table-dark table-responsive-sm text-center">
            <thead>
                    <tr>
                    <th scope="col">#</th>
                    <th scope="col">Session</th>
                    <th scope="col">Status</th>
                    </tr>
            </thead>
            <tbody>
                @foreach($response_recording->streamrecorder as $key=>$recording)
                    <tr>
                        <td>{{$key + 1}}</td>
                        <td>{{json_encode($recording->recorderName)}}</td>
                        <td>{{json_encode($recording->recorderState)}}</td>
                    </tr>
                @endforeach
            </tbody>
            </table>
        </div>
    </div>
    @endif

    @endif
</div>

@endsection

@section('modal')

<!-- Modal for stream request -->
<div id="areYouSure" class="modal" tabindex="-1" role="dialog">
    <form method="post" action="/coach/dashboard" id="configform" name="start_new_stream">
    {{ csrf_field() }}
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Start Streaming Session</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group row">
                        <label for="ss_age_group" class="col-2 col-form-label">Age Group</label>
                        <div class="col-10">
                            <select class="form-control{{ $errors->has('ss_age_group') ? ' is-invalid' : '' }}" autocomplete="off" name="ss_age_group" id="target_a">
                            @if(old('ss_age_group'))
                            <option disabled value=""> -- Select Age Group -- </option>
                            @else
                            <option disabled value="" selected="selected"> -- Select Age Group -- </option>
                            @endif                            
                            @foreach($ss_age_groups as $ss_age_group)
                            @if(old('ss_age_group') == $ss_age_group->name)
                            <option selected="selected" value="{{$ss_age_group->name}}">{{$ss_age_group->name}}</option>
                            @else
                            <option value="{{$ss_age_group->name}}">{{$ss_age_group->name}}</option>
                            @endif
                            @endforeach
                            </select>
                            @if ($errors->has('ss_age_group'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('ss_age_group') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                    <input type="hidden" name="name" value="" id="streamName" required>
                    <input type="hidden" name="startTime" id="startTime" value="{{date('Y_m_d_H-i-s')}}" required>
                    <input type="hidden" name="date_time" id="date_time" value="{{date('Y-m-d H:i:s')}}" required>
                    <div class="form-group row">
                        <label for="field" class="col-2 col-form-label">Field</label>
                        <div class="col-10">
                        <select class="form-control" name="camera_port" required>
                        @foreach($field_names as $key=>$field_name)
                            @if(old('camera_port') == $ports[$key])
                            <option value="{{$ports[$key]}}" selected="selected">{{$field_name}}</option>
                            @else
                            <option value="{{$ports[$key]}}">{{$field_name}}</option>
                            @endif
                        @endforeach
                        </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="submit" class="btn btn-warning" name="new_stream" value="Start Session" />
                    <button type="button" class="btn btn-secondary" name="start_new" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </form>
</div>

@foreach($ss_sessions as $key=>$ss_session)
<!-- Modal -->
<div class="modal fade" id="editSession_{{$key}}" tabindex="-1" role="dialog" aria-labelledby="editSessionLabel_{{$key}}" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">{{$ss_session->age_group_id}} @ {{$ss_session->date_time}}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="/coach/dashboard/session/edit" method="post" id="formEditSession_{{$key}}">
      {{ csrf_field() }}
      <input type="hidden" name="ss_session_id" value="{{$ss_session->id}}" />
      <div class="modal-body">
      <div class="form-group row">
        <label for="field" class="col-2 col-form-label">Age Group</label>
        <div class="col-10">
            <select class="form-control" name="age_group_id">
                @foreach($ss_age_groups as $age_group)
                    @if($age_group->name == $ss_session->age_group_id)
                    <option value="{{$age_group->id}}" selected="selected">{{$age_group->name}}</option>
                    @else
                    <option value="{{$age_group->id}}">{{$age_group->name}}</option>
                    @endif
                @endforeach
            </select>
        </div>
        </div>
        <div class="form-group row">
        <label for="field" class="col-2 col-form-label">Field</label>
        <div class="col-10">
            <select class="form-control" name="camera_port" required>
                @foreach($field_names as $key=>$field_name)
                    @if(\App\SoccerSchoolsStream::find($ss_session->ss_stream_id)->camera_port == $ports[$key])
                    <option value="{{$ports[$key]}}" selected="selected">{{$field_name}}</option>
                    @else
                    <option value="{{$ports[$key]}}">{{$field_name}}</option>
                    @endif
                @endforeach
            </select>
        </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary" id="saveChangesSession_{{$key}}">Save changes</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
      </form>
    </div>
  </div>
</div>
@endforeach

<!-- End of Modal -->

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

            var ss_age_group = "";
            var coach = "";
            var streamName = "";

            $("#date_time").val(moment().format('Y-MM-D HH:mm:ss'));
            $("#startTime").val(moment().format('Y_MM_D_HH-mm-ss'));

            if($("#target_a").val()) {
                ss_age_group = $("#target_a").val();
            }
            
            if($("#target_a").val()) {
              startTime = $("#startTime").val();
              streamName = ss_age_group.replace(/\ /g,"_") + "_" + startTime;
            }
            
            $("#streamName").val(streamName);
        }

        $("#target_a").change(function() {
            changeStreamName();
        });
        
        var showSpinner = function() {
            $('.spinner-wrapper').css('display','block');
            $('#areYouSure').modal('hide');
            setTimeout(
            function() {
                $('#spinner-text').html('Checking Camera...');
            }, 3000);
        }
        
        $('#configform').submit(function() {
          
            showSpinner();
            changeStreamName();
        });   
        $('.start-stream-form').submit(function() {
            showSpinner();
        });                   
    });
</script>
@endsection