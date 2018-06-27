@extends('layouts.app')

@section('styles')
<style>
    .fa-redo-alt {
        font-size: 24px;
        color: red;
    }    
</style>
@endsection

@section('content')
<br />
<div class="container-fluid">
    <div class="row">
        <div class="co-12">
            <h1>{{$event->eventName}}</h1>
        </div>
    </div>
    <div class="row">
        <div class="col-12 col-md-8 offset-md-2">
        
        <table class="table table-dark text-center">
                <thead>
                    <tr>
                    <th scope="col">#</th>
                    <th scope="col">Camera</th>
                    <th scope="col">Status</th>
                    </tr>
                </thead>
                <tbody>
                @if(array_key_exists('streamrecorder', $response_recording))
                @foreach($response_recording->streamrecorder as $key=>$recording)
                    <tr>
                        <td>{{$key + 1}}</td>
                        <td>{{json_encode($recording->recorderName)}}</td>
                        <td>{{json_encode($recording->recorderState)}}</td>
                    </tr>
                @endforeach
                @endif
                <tr>
                    <td colspan="4" align="right">
                    <form action="/superuser/dashboard/restart-recording" method="post">
                        @csrf
                        <input type="number" name="event_id" value="{{$event->id}}" hidden readonly/>
                        <button type="submit" class="btn btn-outline-danger">Restart Recordings</button>
                    </form>
                    </td>
                </tr>
                </tbody>
            </table>

        </div>
    </div>

</div>

@endsection

@section('scripts')

@endsection