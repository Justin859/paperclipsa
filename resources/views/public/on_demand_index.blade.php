@extends('layouts.app')

@section('styles')

<style>

    .img-wrapper
    {
        position: relative;
        max-width: 500px;
        max-height: 500px;
        padding: 0px;
        margin: 25px;
    }

    .img-wrapper img:hover
    {
        filter: blur(2px);
    }

    .centered 
    {
        font-size: 32px;
        font-weight: bold;
        background-color: rgba(0, 0, 0, 0.9);
        width: 100%;
        height: 50px;
        text-align: center;
        color: #FFFFFF;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }

    .img-wrapper img
    {
        border: 1px solid #F2F2F2F2;
        border-radius: 1px;
    }

    .categories
    {
        align-content: middle;
    }

</style>

@endsection

@section('content')
<br />
<div class="container">
    <div class="row">
        <div class="col-12">
            <h1>On Demand <small class="text-muted">categories</small></h1>
            <hr />
        </div>
    </div>
    <div class="row categories">
        <div class="col-md-6">
            <a href="/on-demand/indoor-soccer">
                <div class="img-wrapper ">
                    <img src="{{asset('images/category/Indoor_Soccer_2.jpg')}}" class="img-fluid" />
                    <div class="centered">Indoor Soccer</div>
                </div>
            </a>
        </div>
        <div class="col-md-6">
            <a href="/on-demand/soccer-schools">
                <div class="img-wrapper ">
                    <img src="{{asset('images/category/School_Soccer_1.jpg')}}" class="img-fluid" />
                    <div class="centered ">Soccer Schools</div>
                </div>
            </a>
        </div>
        <div class="col-md-6">
            <a href="/on-demand/squash">
                <div class="img-wrapper ">
                    <img src="{{asset('images/category/Squash_2.jpg')}}" class="img-fluid" />
                    <div class="centered">Squash</div>
                </div>
            </a>
        </div>
    </div>
</div>

@endsection