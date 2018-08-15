@extends('layouts.app')

@section('header')
<link rel="stylesheet" href="{{asset('/add_ons/breadcrumbs-and-multistep-indicator/css/style.css')}}">
<link rel="stylesheet" href="{{asset('/add_ons/breadcrumbs-and-multistep-indicator/css/reset.css')}}">

@endsection

@section('styles')

@include('includes.user_profile_styles')
<style>
    .done-list 
    {
        font-size: 24px;
    }

    .done-text
    {
        color: #ffffff;
    }

    h2
    {
        color: #ffffff;
    }
</style>
@endsection

@section('content')

<div class="container">
    <div class="row">
        @include('includes.user_side_panel')<!-- side panel -->

        <div class="col-12 col-md-9 order-md-2">
            <div class="row">
                <div class="col-12">
                <h2>Buy Credits</h2><hr />
                <nav>
                    <ol class="cd-multi-steps text-center">
                        <li><em style="font-size: 20px;">Checkout</em></li>
                        <li><em style="font-size: 20px;">Confirm</em></li>
                        <li class="current"><em style="font-size: 20px;">Done</em></li>
                    </ol>
                </nav>
                </div>
                <div class="row done-text">
                    <div class="col-12 m-4">
                        <h2>Success!</h2>

                        <h4>Thank you for your purchase</h4>
                        <ol class="done-list">
                            @if($account_balance)
                            <li>Your credits are currently: <span class="badge badge-warning">{{$account_balance->balance_value}}</span></li>
                            @endif

                        </ol>
                        <hr  />
                        <p>If there has been any issues with your purchase please contact <a href="mailto:info@paperclipsa.co.za?Subject=Issue%20with%20credit%20purchase" target="_top">info@paperclipsa.co.za</a></p>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

@endsection