@extends('layouts.app')

@section('content')
<br />
@if($single_access_user and $subscribed_user->status == 'active')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Purchase Subscription') }}</div>

                <div class="card-body">
                    <h2>Your Subscription has been successfully completed</h2>
                    <p>Any videos from {{$venue->name}}'s Live and On-Demand can now be viewed.</p>
                    <p>Browse now from the <a href="/new/on-demand?channel={{$venue->id}}">On-Demand Page</a> or from the <a href="/channel/{{$venue->id}}">channel page</a></p>
                </div>
            </div>
        </div>
    </div>
</div>
@elseif ($full_access_user and $subscribed_user->status == 'active')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Purchase Subscription') }}</div>

                <div class="card-body">
                    <h2>Your Subscription has been successfully completed</h2>
                    <p>Any videos from indoor soccer venues Live and On-Demand can now be viewed.</p>
                    <p>Browse now from the <a href="/new/on-demand?channel=all">On-Demand Page</a></p>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@endsection