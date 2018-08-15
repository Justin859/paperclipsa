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
</style>
@endsection

@section('content')

<div class="container">
    <div class="row">
        @include('includes.user_side_panel')<!-- side panel -->

        <div class="col-12 col-md-9 order-md-2">
            <div class="row">
                <div class="col-12">
                    <h2 style="color:#ffffff;">Your purchase has been canceled</h2>
                    <hr >
                </div>
                <div class="row">
                    <div class="col-12 m-4">
                        <p>If this was a mistake go back to confirm and proceed with payment <a href="/user-profile/buy-credit/confirm/{{$user_id}}/{{$cart_id}}">here</a></p>
                        <p>Or if you would like to change the amount go back to the checkout page <a href="/user-profile/buy-credit">here</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection