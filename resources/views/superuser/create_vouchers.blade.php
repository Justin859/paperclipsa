@extends('layouts.app')

@section('content')
<div class="container-fluid">

    <div class="row">
        <div class="col-12">
        <h1>Superuser Dashboard</h1>

            <ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="nav-link" href="/superuser/dashboard">Home</a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" href="/superuser/dashboard/create-vouchers">Create Voucher</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/superuser/dashboard/download-vouchers">Download Vouchers</a>
            </li>
            <!--
            <li class="nav-item">
                <a class="nav-link disabled" href="#">Disabled</a>
            </li> 
            -->
            </ul> 
        </div>
    </div>    

</div>
<div class="container">
    <br />
    <div class="row">
        <div class="col-12">
        
        <div class="row justify-content-center">
        <div class="col-md-8">

            <div class="card">
                <div class="card-header"><h3>{{ __('Create Vouchers') }}</h3></div>

                <div class="card-body">

                    <form action="/superuser/dashboard/submit-vouchers" method="post">
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
                            <label for="points_value" class="col-md-4 col-form-label text-md-right">{{ __('Points Value') }}</label>

                            <div class="col-md-6">
                                <input id="points_value" type="number" class="form-control{{ $errors->has('points_value') ? ' is-invalid' : '' }}" value="20" name="points_value" required>
                                @if ($errors->has('points_value'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('points_value') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="form-group row">
                            <label for="number_of_vouchers" class="col-md-4 col-form-label text-md-right">{{ __('Number of Vouchers') }}</label>

                            <div class="col-md-6">
                                <input id="number_of_vouchers" type="number" class="form-control{{ $errors->has('number_of_vouchers') ? ' is-invalid' : '' }}" value="20" name="number_of_vouchers" required>
                                @if ($errors->has('number_of_vouchers'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('number_of_vouchers') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-outline-warning">
                                    Create Vouchers
                                </button>
                            </div>
                        </div>                                                

                    </form>

            </div>
                
            </div>
        </div>
    </div>

        </div>    
    </div>

</div>

@endsection