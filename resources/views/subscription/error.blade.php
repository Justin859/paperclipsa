@extends('layouts.app')

@section('content')

<br />
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Purchase Subscription') }}</div>

                <div class="card-body">
                    <h2>Your Subscription has been <span style="color:#D50000">unsuccessfull</span>!</h2>
                    <p>Please contact <a href="mailto:info@paperclipsa.co.za?Subject=Account%20Subscription%20Error" target="_top">info@paperclipsa.co.za</a> to assist you with resolving the issue.</p>
                    <p><strong>Proved us with your account email address and the subscription you would like purchase</strong></p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection