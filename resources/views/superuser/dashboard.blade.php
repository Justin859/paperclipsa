@extends('layouts.app')

@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <h1>
                Superuser Dashboard
            </h1>       
            <ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="nav-link active" href="#">Home</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/superuser/dashboard/create-vouchers">Create Voucher</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/superuser/dashboard/download-vouchers">Download Vouchers</a>
            </li>            
            <!-- 
            <li class="nav-item">
                <a class="nav-link disabled" href="#">Disabled</a>
            </li> -->
            </ul> 
        </div>
    </div>
    <hr />
    <div class="row">
        <div class="col-12">
            <h2>Current Events</h2>
        </div>
    </div>
    <br />
    <div class="col-12">
    <table class="table table-dark text-center">
                <thead>
                    <tr>
                    <th scope="col">#</th>
                    <th scope="col">Event Name</th>
                    <th scope="col">Go To</th>
                    </tr>
                </thead>
                <tbody>

                    @if($events)
                    @foreach($events as $key=>$event)
                        <tr>
                            <td>{{$key + 1}}</td>
                            <td>{{$event->eventName}}</td>
                            <td><a href="/superuser/dashboard/event/{{$event->id}}"><i class="fas fa-external-link-alt"></i></a></td>
                        </tr>
                    @endforeach
                    @endif
                
                </tbody>
            </table>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-8">

            <div class="card">
                <div class="card-header"><h3>{{ __('Create Event') }}</h3></div>

                <div class="card-body">

                    <form action="/superuser/dashboard/startstream" method="post">
                        @csrf
                        <div class="form-group row">
                            <label for="eventName" class="col-md-4 col-form-label text-md-right">{{ __('Channel') }}</label>

                            <div class="col-md-6">
                                <select id="venue_id" type="text" class="form-control{{ $errors->has('venue_id') ? ' is-invalid' : '' }}" name="venue_id"  autofocus>
                                    @foreach($channels as $channel)
                                        <option value="{{$channel->id}}">{{$channel->name}}</option>
                                    @endforeach
                                </select>

                                @if ($errors->has('venue_id'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('venue_id') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="eventName" class="col-md-4 col-form-label text-md-right">{{ __('Event Name') }}</label>

                            <div class="col-md-6">
                                <input id="name" type="text" placeholder="Name of the event" class="form-control{{ $errors->has('eventName') ? ' is-invalid' : '' }}" name="eventName" value="{{ old('eventName') }}" required>

                                @if ($errors->has('eventName'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('eventName') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>


                        <div class="form-group row">
                            <label for="applicationName" class="col-md-4 col-form-label text-md-right">{{ __('Application Name') }}</label>

                            <div class="col-md-6">
                                <input id="applicationName" type="text" placeholder="Name of wowza application eg. 'live'" class="form-control{{ $errors->has('applicationName') ? ' is-invalid' : '' }}" name="applicationName" value="{{ old('applicationName') }}" required>

                                @if ($errors->has('applicationName'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('applicationName') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>                        

                        <div class="form-group row">
                            <label for="cameraOne" class="col-md-4 col-form-label text-md-right">{{ __('First Camera') }}</label>

                            <div class="col-md-6">
                                <input id="cameraOne" type="text" placeholder="Stream File name of camera" class="form-control{{ $errors->has('cameraOne') ? ' is-invalid' : '' }}" name="cameraOne" value="{{ old('cameraOne') }}" required autofocus>

                                @if ($errors->has('cameraOne'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('cameraOne') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>   

                        <div class="form-group row">
                            <label for="cameraTwo" class="col-md-4 col-form-label text-md-right">{{ __('Second Camera') }}</label>

                            <div class="col-md-6">
                                <input id="cameraTwo" type="text" placeholder="Stream File name of camera" class="form-control{{ $errors->has('cameraTwo') ? ' is-invalid' : '' }}" name="cameraTwo" value="{{ old('cameraTwo') }}">

                                @if ($errors->has('cameraTwo'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('cameraTwo') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div> 

                        <div class="form-group row">
                            <label for="cameraThree" class="col-md-4 col-form-label text-md-right">{{ __('Third Camera') }}</label>

                            <div class="col-md-6">
                                <input id="cameraThree" type="text" placeholder="Stream File name of camera" class="form-control{{ $errors->has('cameraThree') ? ' is-invalid' : '' }}" name="cameraThree" value="{{ old('cameraThree') }}" >

                                @if ($errors->has('cameraThree'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('cameraThree') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="cameraFour" class="col-md-4 col-form-label text-md-right">{{ __('Fourth Camera') }}</label>

                            <div class="col-md-6">
                                <input id="cameraFour" type="text" placeholder="Stream File name of camera" class="form-control{{ $errors->has('cameraFour') ? ' is-invalid' : '' }}" name="cameraFour" value="{{ old('cameraFour') }}">

                                @if ($errors->has('cameraFour'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('cameraFour') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="cameraFive" class="col-md-4 col-form-label text-md-right">{{ __('Fith Camera') }}</label>

                            <div class="col-md-6">
                                <input id="cameraFive" type="text" placeholder="Stream File name of camera" class="form-control{{ $errors->has('cameraFive') ? ' is-invalid' : '' }}" name="cameraFive" value="{{ old('cameraFive') }}">

                                @if ($errors->has('cameraFive'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('cameraFive') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="cameraSix" class="col-md-4 col-form-label text-md-right">{{ __('Sixth Camera') }}</label>

                            <div class="col-md-6">
                                <input id="cameraSix" type="text" placeholder="Stream File name of camera" class="form-control{{ $errors->has('cameraSix') ? ' is-invalid' : '' }}" name="cameraSix" value="{{ old('cameraSix') }}">

                                @if ($errors->has('cameraSix'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('cameraSix') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>                        
                            
                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-outline-warning">
                                    Start Stream
                                </button>
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

<script type="text/javascript">

$( document ).ready(function() {
    
});

</script>

@endsection