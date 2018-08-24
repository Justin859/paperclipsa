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

    .pagination
    {
        font-size: 24px;
    }
    .fa-step-forward, .fa-step-backward, .fa-fast-forward, .fa-fast-backward 
    {
        color: rgba(208, 0, 0);
    }

    .pagination-number
    {
        color: #FFFFFF !important;
        background-color: rgba(208, 0, 0) !important;
    }

    .pagination-skip, .pagination-number-active
    {
        background-color: #181818 !important;
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

    <div class="row" style="background-image: url(''); background-size: contain;">
        <img src="{{asset('/storage/adverts/images/239SqTl5NMJBWQ3RdeHXmcNnOMTZf8oaVQ26Q1bJ.jpeg')}}" />
    </div>

    @elseif($venue->venue_type == 'squash')

    <div class="row" style="background-image: url(''); background-size: contain;">
        <img src="{{asset('/storage/adverts/images/Artboard_2.png')}}" />
    </div>

    @endif
    <br />
    <div class="row d-flex px-1" id="main">
        <div class="btn-group" role="group" aria-label="Basic example">
            <a href="/channel/{{$venue->id}}/{{$venue->name}}" class="btn btn-outline-danger">Channel</a>
            <a href="/channel/{{$venue->id}}/{{$venue->name}}/on-demand" class="btn btn-danger">On-Demand</a>
        </div>
    </div>
    <hr />

<div class="mx-auto px-1">
    <div class="row d-flex justify-content-center">
        @foreach($venue_odvs as $vod)
            @if($vod->stream_type == "vod")
            <div class="col-xs-12 col-md-3 vod-item">
                @if($venue->venue_type != 'squash')
                <a href="/on-demand/indoor-soccer/{{$vod->id}}/{{$vod->name}}/" class="js-item">
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
                <p style="color: #ffffff; margin: 5px;">@<a href="#">{{\App\Venue::find($vod->venue_id)->name}}</a></p>
            </div>
            @endif
        @endforeach
    </div>
</div>
<br />
<?php $current_page = $venue_odvs->currentPage(); 
      $page_numbers = [$current_page, $current_page + 1, $current_page + 2];
?>
<div class="d-flex justify-content-center">
    <nav aria-label="Page navigation example" width="100%">
    <ul class="pagination">
        @if($current_page > 2)
        <li class="page-item">
            <a class="page-link pagination-skip" href="?page={{$current_page - 3}}#main" aria-label="Next">
                <span class="fas fa-fast-backward" aria-hidden="true"></span>
                <span class="sr-only">back</span>
            </a>
        </li>
        @else
        <li class="page-item disabled">
            <a class="page-link pagination-skip" href="?page={{$current_page - 3}}#main" aria-label="Next">
                <span class="fas fa-fast-backward" style="color: #ffffff !important;" aria-hidden="true"></span>
                <span class="sr-only">back</span>
            </a>
        </li>
        @endif
        @if($current_page > 1)
        <li class="page-item">
            <a class="page-link pagination-skip" href="?page={{$current_page - 1}}#main" aria-label="Next">
                <span class="fas fa-step-backward" aria-hidden="true"></span>
                <span class="sr-only">back 3</span>
            </a>
        </li>
        @else
        <li class="page-item disabled">
            <a class="page-link pagination-skip" href="?page={{$current_page - 1}}#main" style="color: #ffffff !important;" aria-label="Next">
                <span class="fas fa-step-backward" style="color: #ffffff !important;" aria-hidden="true"></span>
                <span class="sr-only">back 3</span>
            </a>
        </li>
        @endif
        @foreach($page_numbers as $page_number)
            @if($page_number < $venue_odvs->lastPage())
                @if($page_number == $venue_odvs->currentPage())
                <li class="page-item disabled"><a class="page-link pagination-number" href="?page={{$page_number}}#main" style="background-color: #181818 !important;">{{$page_number}}</a></li>
                @else
                <li class="page-item"><a class="page-link pagination-number" href="?page={{$page_number}}#main">{{$page_number}}</a></li>
                @endif
            @endif
        @endforeach
        @if(($venue_odvs->lastPage() - $current_page) > 1)
        <li class="page-item">
            <a class="page-link pagination-skip" href="?page={{$current_page + 1}}#main" aria-label="Next">
                <span class="fas fa-step-forward" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </a>
        </li>
        @else
        <li class="page-item disabled">
            <a class="page-link pagination-skip"  href="?page={{$current_page + 1}}#main" aria-label="Next">
                <span class="fas fa-step-forward" style="color: #ffffff !important;" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </a>
        </li>
        @endif
        @if(($venue_odvs->lastPage() - $current_page) > 3)
        <li class="page-item">
            <a class="page-link pagination-skip" href="?page={{$current_page + 3}}#main" aria-label="Next">
                <span class="fas fa-fast-forward" aria-hidden="true"></span>
                <span class="sr-only">Next 3</span>
            </a>
        </li>
        @else
        <li class="page-item disabled">
            <a class="page-link pagination-skip" href="?page={{$current_page + 3}}#main" aria-label="Next">
                <span class="fas fa-fast-forward" style="color: #ffffff !important;" aria-hidden="true"></span>
                <span class="sr-only">Next 3</span>
            </a>
        </li>
        @endif
    </ul>
    </nav>
</div>   

</div>

@endsection