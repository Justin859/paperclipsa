@extends('layouts.app')

@section('styles')
<style>
    .col-md-8
    {
        background-color: #202020;
        border-width: 2px;
        border-color: #181818;
        box-shadow: rgba(0, 0, 0, 9);
        border-style: solid;
    }

    h1, p, label
    {
        color: #ffffff;
    }

    h2
    {
        color: #FFCC00;
    }
</style>
@endsection

@section('content')
<br />
<div class="container">
    <div class="row">
        <div class="col-12 col-md-8 offset-md-2">
            <h1>Email Verification</h1>
        </div>      
    </div>
    <div class="row">
        <div class="col-12 col-md-8 offset-md-2">
            <h2>Thank You! Your email has been verified successfully.</h4>
            <ul class="list-unstyled">
                <li><a href="/user-profile/">back to profile</a></li>

            </ul>
        </div>
    </div>  
</div>

@endsection