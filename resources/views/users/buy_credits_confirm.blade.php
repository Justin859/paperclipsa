@extends('layouts.app')

@section('header')
<link rel="stylesheet" href="{{asset('/add_ons/breadcrumbs-and-multistep-indicator/css/style.css')}}">
<link rel="stylesheet" href="{{asset('/add_ons/breadcrumbs-and-multistep-indicator/css/reset.css')}}">

@endsection

@section('styles')

<style>
    h2
    {
        color:#ffffff;
    }
</style>

@include('includes.user_profile_styles')

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
                    <li class="current"><em style="font-size: 20px;">Confirm</em></li>
                    <li><em style="font-size: 20px;">Done</em></li>
                </ol>
            </nav>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <table class="table table-striped table-dark">
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Credits</th>
                            <th scope="col">Amount (ZAR)</th>
                            <th scope="col">Total (INCL VAT)</th>
                        </tr>
                    <thead>
                    <tbody>
                        <tr>
                            <th scope="row">1</th>
                            <td>{{$credit_cart->credits}}</td>
                            <td>R{{$credit_cart->credits}}.00</td>
                            <td>R{{$credit_cart->credits}}.00</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <form action="/user-profile/purchase-credit" method="post">
                {{ csrf_field() }}
                    <input type="hidden" value="{{$credit_cart->id}}">
                    <div class="btn-group float-right" role="group" aria-label="Basic example">
                        <a href="/user-profile/buy-credit" type="button" class="btn btn-outline-warning float-right">back to checkout</a>
                        <button type="submit" class="btn btn-outline-warning">Pay Now</button>
                    </div>
                <form>
            </div>
        </div>
        </div>
    </div>
</div>

@endsection