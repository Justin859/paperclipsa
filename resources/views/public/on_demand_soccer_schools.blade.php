@extends('layouts.app')

@section('header')
<style>
    .col-md-3, .row, .container, .col-sm-12
    {
        padding:1px !important;
        margin: 0px !important;
        width: 100%;
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

    select 
    {
        border-color: #d50000;
        background-color: #18181818;
        color: #ffffff;
    }

    option
    {
        color: #ffffff;
        background-color: #383838;
    }
    
    </style>

<script type="text/javascript" src="//player.wowza.com/player/latest/wowzaplayer.min.js"></script>
<script type="text/javascript" src="{{asset('js/vendor/shuffle.min.js')}}"></script>
@endsection

@section('content')
<br />
<h1 class="main-heading" align="left">Soccer Schools On Demand Videos</h1>
<hr />

<div class="mx-auto px-1">
    <div class="row d-flex justify-content-center">
        @foreach($vods as $vod)
            @if($vod->stream_type == "vod")
            <div class="col-xs-12 col-md-3 vod-item">
                <a href="/on-demand/soccer-schools/{{$vod->id}}/{{$vod->name}}/" class="js-item">
                    <img src="{{ asset('images/vod1.png')}}" height="auto" width="100%" />
                    <i class="far fa-play-circle play-icon" style="display:none;"></i>
                </a>
                <p style="color: #ffffff; margin: 5px;">{{str_replace("_", " ", $vod->name)}}</p>
                <p style="color: #ffffff; margin: 5px;">@<a href="#">{{\App\Venue::find($vod->venue_id)->name}}</a></p>
            </div>
            @endif
        @endforeach
    </div>
</div>
<br />
<?php $current_page = $vods->currentPage(); 
      $page_numbers = [$current_page, $current_page + 1, $current_page + 2];
?>
<div class="d-flex justify-content-center">
    <nav aria-label="Page navigation example" width="100%">
    <ul class="pagination">
        @if($current_page > 2)
        <li class="page-item">
            <a class="page-link pagination-skip" href="?page={{$current_page - 3}}" aria-label="Next">
                <span class="fas fa-fast-backward" aria-hidden="true"></span>
                <span class="sr-only">back</span>
            </a>
        </li>
        @else
        <li class="page-item disabled">
            <a class="page-link pagination-skip" href="?page={{$current_page - 3}}" aria-label="Next">
                <span class="fas fa-fast-backward" style="color: #ffffff !important;" aria-hidden="true"></span>
                <span class="sr-only">back</span>
            </a>
        </li>
        @endif
        @if($current_page > 1)
        <li class="page-item">
            <a class="page-link pagination-skip" href="?page={{$current_page - 1}}" aria-label="Next">
                <span class="fas fa-step-backward" aria-hidden="true"></span>
                <span class="sr-only">back 3</span>
            </a>
        </li>
        @else
        <li class="page-item disabled">
            <a class="page-link pagination-skip" href="?page={{$current_page - 1}}" style="color: #ffffff !important;" aria-label="Next">
                <span class="fas fa-step-backward" style="color: #ffffff !important;" aria-hidden="true"></span>
                <span class="sr-only">back 3</span>
            </a>
        </li>
        @endif
        @foreach($page_numbers as $page_number)
            @if($page_number < $vods->lastPage())
                @if($page_number == $vods->currentPage())
                <li class="page-item disabled"><a class="page-link pagination-number" href="?page={{$page_number}}" style="background-color: #181818 !important;">{{$page_number}}</a></li>
                @else
                <li class="page-item"><a class="page-link pagination-number" href="?page={{$page_number}}">{{$page_number}}</a></li>
                @endif
            @endif
        @endforeach
        @if(($vods->lastPage() - $current_page) > 1)
        <li class="page-item">
            <a class="page-link pagination-skip" href="?page={{$current_page + 1}}" aria-label="Next">
                <span class="fas fa-step-forward" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </a>
        </li>
        @else
        <li class="page-item disabled">
            <a class="page-link pagination-skip"  href="?page={{$current_page + 1}}" aria-label="Next">
                <span class="fas fa-step-forward" style="color: #ffffff !important;" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </a>
        </li>
        @endif
        @if(($vods->lastPage() - $current_page) > 3)
        <li class="page-item">
            <a class="page-link pagination-skip" href="?page={{$current_page + 3}}" aria-label="Next">
                <span class="fas fa-fast-forward" aria-hidden="true"></span>
                <span class="sr-only">Next 3</span>
            </a>
        </li>
        @else
        <li class="page-item disabled">
            <a class="page-link pagination-skip" href="?page={{$current_page + 3}}" aria-label="Next">
                <span class="fas fa-fast-forward" style="color: #ffffff !important;" aria-hidden="true"></span>
                <span class="sr-only">Next 3</span>
            </a>
        </li>
        @endif
    </ul>
    </nav>
</div>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
        <a href="">
            <img class="img-fluid" src="http://www.paperclipsa.co.za/storage/adverts/images/si1zO40pT7mqxrGczugn1x4M3TxsNBG3b7hJ7ynD.jpeg">
        </a>
        </div>
    </div>
</div>
@endsection

@section('scripts')

@endsection