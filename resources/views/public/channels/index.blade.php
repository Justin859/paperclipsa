@extends('layouts.app')

@section('styles')
<style>
    .description-text
    {
        color: #ffffff;
    }

    .venue-link
    {
        text-decoration: none !important;
    }

    .venue-link p:hover
    {
        color: #D50000;
    }

    .venue-link .col-8
    {
        padding-top: 15px;
    }

    .venue-link:hover
    {
        background-color: #303030;
        box-shadow: 4px 0 2px -2px rgba(0,0,0,0.7);
    }

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
        background-color: rgba(0, 0, 0, 0.7);
    }
</style>
@endsection

@section('content')
<br />
<h1 style="padding-left: 15px;">Channels</h1>
<hr />
<div class="container">
    @foreach($venues as $venue)
        <a class="venue-link row" href="/channel/{{$venue->id}}/{{$venue->name}}" style="background-image: url('{{asset('/storage/venues/banners/'.$venue->banner_img)}}'); background-size: cover;">
            <div class="col-12 col-md-4 venue-description" style="padding-left: 0px;">
            <img src="{{asset('/storage/venues/logos/'. $venue->logo_img)}}" height="200" width="auto" class="float-left" alt="...">
            </div>
            <div class="col-12 col-md-8 venue-description d-flex align-items-center text-center" style="padding-top: 15px;">
            <?php 
                $description = strlen($venue->description) > 250 ? substr($venue->description,0,250)."..." : $venue->description;
            ?>
                <h2 class="text-center ">{{$venue->name}}</h2>
                <!-- <p class="description-text">{{$description}} <a href="/channel/{{$venue->id}}/{{$venue->name}}"><b>View Page</b></a></p> -->
            </div>
        </a>
        <hr />
        <div class="row d-flex px-1">
            <div class="btn-group" role="group" aria-label="Basic example">
                <a href="/channel/{{$venue->id}}/{{$venue->name}}" class="btn btn-outline-danger">Channel</a>
                <a href="/channel/{{$venue->id}}/{{$venue->name}}/on-demand" class="btn btn-outline-danger">On-Demand</a>
            </div>
        </div>
        <br />
        <div class="mx-auto px-1 d-none d-sm-block">
            <div class="row d-flex justify-content-center">
            <?php 
                $vods = \App\Stream::orderBy('id', 'desc')->where(['stream_type' => 'vod', 'venue_id' => $venue->id])->take(4)->get();

                if($venue->venue_type == 'squash')
                {
                  $vods = \App\SquashStream::orderBy('id', 'desc')->where(['stream_type' => 'vod', 'venue_id' => $venue->id])->take(4)->get();
                } 
            ?>
            @foreach($vods as $vod)
                @if($vod->stream_type == "vod")
                <div class="col-xs-12 col-md-3 vod-item">
                    @if($venue->venue_type != 'squash')
                    <a href="/on-demand/{{$vod->id}}/{{$vod->name}}/" class="js-item">
                        <img src="{{ asset('images/vod1.png')}}" height="auto" width="100%" />
                        <i class="far fa-play-circle play-icon" style="display:none;"></i>
                    </a>
                    @else
                    <a href="/on-demand/squash/{{$vod->id}}/{{$vod->name}}/" class="js-item">
                        <img src="{{ asset('images/vod_squash.png')}}" height="auto" width="100%" />
                        <i class="far fa-play-circle play-icon" style="display:none;"></i>
                    </a>
                    @endif
                    <p style="color: #ffffff; margin: 5px;">{{str_replace("_", " ", $vod->name)}}</p>
                    <p style="color: #ffffff; margin: 5px;">@<a href="/channel/{{$venue->id}}/{{$venue->name}}">{{\App\Venue::find($vod->venue_id)->name}}</a></p>
                </div>
                @endif
            @endforeach
            </div>
        </div>
    <hr />
    @endforeach
</div>

@endsection