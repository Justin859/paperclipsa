@extends('layouts.app')

@section('styles')
<style>
   .btn-group .btn
    {
        border-radius: 0px !important;
    }

    h1, h3, h2
    {
        color: #ffffff;
    }

    .venue-description
    {
        background-color: rgba(0, 0, 0, 0.5);
    }

    p
    {
        color: #ffffff;
    }

    .fas, .fab
    {
        font-size: 24px;
    }

    .fa-twitter
    {
        color: #00aced;
    }

    .fa-facebook
    {
        color: #3B5998;
    }

    .fa-globe
    {
        color: #ffffff;
    }

</style>
@endsection

@section('content')
<div class="container">
    <div class="venue-link row"  style="background-image: url('{{asset('/storage/venues/banners/'.$venue->banner_img)}}'); background-size: cover">
        <div class="col-12 col-md-4 venue-description" style="padding-left: 0px;">
        <img src="{{asset('/storage/venues/logos/'. $venue->logo_img)}}" height="200" width="auto" class="float-left" alt="...">
        </div>
        <div class="col-12 col-md-8 venue-description d-flex align-items-center text-center" style="padding-top: 15px;">

            <h2 class="text-center ">{{$venue->name}}</h2>
        </div>
    </div>
    <hr />
    @if($venue->venue_type == 'indoor_soccer')

    <a href="http://new.supabets.co.za/Sport/Default.aspx?promocode=actionreplay">
        <img src="{{asset('/storage/adverts/images/239SqTl5NMJBWQ3RdeHXmcNnOMTZf8oaVQ26Q1bJ.jpeg')}}" class="img-fluid mb-4" />
    </a>

    @endif
    <br />
    <div class="row d-flex px-1">
        <div class="btn-group" role="group" aria-label="Basic example">
            <a href="/channel/{{$venue->id}}/{{$venue->name}}" class="btn btn-danger">Channel</a>
            <a href="/channel/{{$venue->id}}/{{$venue->name}}/on-demand" class="btn btn-outline-danger">On-Demand</a>
            <a href="/channel/{{$venue->id}}/{{$venue->name}}/clubs" class="btn btn-outline-danger">Clubs</a>
        </div>
    </div>
    <hr />
    <div class="row">
        <div class="col-12 col-md-4">
        <?php 
        ?>
            <p class="description-text">{{$venue->description}}</p>
            <ul class="list-inline">
                @if($venue->twitter_url)
                <li class="list-inline-item"><a href="{{$venue->twitter_url}}"><i class="fab fa-twitter"></i></a></li>
                @endif
                @if($venue->fb_url)
                <li class="list-inline-item"><a href="{{$venue->fb_url}}"><i class="fab fa-facebook"></i></a></li>
                @endif
                @if($venue->web_url)
                <li class="list-inline-item"><a href="{{$venue->web_url}}"><i class="fas fa-globe"></i></a></li>
                @endif
            </ul>
        </div>
        <div class="col-12 col-md-8">
        <?php

        if($venue->intro_vid_url != '')
        {
            $url_ = explode('=', $venue->intro_vid_url);
            $url = substr($url_[1], 0, 11); 

            $url_ = ($url) ? 'https://www.youtube.com/embed/'.$url.'?rel=0' : 'https://www.youtube.com/embed/cPAbx5kgCJo?rel=0'; 
        }
            
        ?>
        <div class="embed-responsive embed-responsive-16by9">
            <iframe class="embed-responsive-item" src='{{ $url_ }}' frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
        </div>
        </div>
    </div>

</div>
@endsection