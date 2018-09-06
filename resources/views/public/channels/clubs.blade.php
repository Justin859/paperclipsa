@extends('layouts.app')

@section('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.min.css">

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
    .list-item-club:hover
    {
        background-color: #202020 !important;
    }
    .list-item-club
    {
        background-color: #181818;
        color: #ffffff;
    }
    a.club-link
    {
        text-decoration: none;
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

    <a href="http://new.supabets.co.za/Sport/Default.aspx?promocode=actionreplay">
        <img src="{{asset('/storage/adverts/images/239SqTl5NMJBWQ3RdeHXmcNnOMTZf8oaVQ26Q1bJ.jpeg')}}" class="img-fluid mb-4" />
    </a>

    @endif
    <br />
    <div class="row d-flex px-1" id="main">
        <div class="btn-group" role="group" aria-label="Basic example">
            <a href="/channel/{{$venue->id}}/{{$venue->name}}" class="btn btn-outline-danger">Channel</a>
            <a href="/channel/{{$venue->id}}/{{$venue->name}}/on-demand" class="btn btn-outline-danger">On-Demand</a>
            <a href="/channel/{{$venue->id}}/{{$venue->name}}/clubs" class="btn btn-danger">Clubs</a>
        </div>
    </div>
    <div class="container mt-5">
        <div class="row">
            <div class="col-12 col-md-8 offset-md-2">
                <form action="/on-demand/indoor-soccer/" method="get">
                    <div class="form-group row">
                        <label for="select_channel" class="col-sm-2 mb-2 ml-0 p-0" style="color: white;"><strong>Find A Club&nbsp;&nbsp;</strong><i class="fas fa-search" style="font-size: 14px;"></i></label>
                        <select class="selectpicker js-example-basic-single form-control" onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);" data-live-search="true" name="team">
                            <option value="">Select...</option>
                            @foreach($all_clubs as $club)
                                <option value="/channel/{{$venue->id}}/{{$venue->name}}/clubs/{{$club->id}}/{{$club->name}}">{{$club->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <hr />
    <div class="row">
        <div class="col-12 col-md-8 offset-md-2">
        <h2>Venue Clubs <small class="text-muted">{{$clubs_total}}</small></h2><hr />
        <ul class="list-group list-group-flush">
        @if($clubs->count())
            @foreach($clubs as $key=>$club)
            <?php 
                $team_profile = \App\TeamProfile::where(['team_id' => $club->team_id])->first();
                $team_players = \App\TeamPlayer::where('team_id', $club->id)->count();

                $games = \App\Fixture::where(['team_a' => $club->name])->orWhere(['team_b' => $club->name])->pluck('stream_id')->toArray();
                $games_recorded = \App\Stream::whereIn('id', $games)->where(['stream_type' => 'vod'])->count();
            ?>
            <a href="/channel/{{$venue->id}}/{{$venue->name}}/clubs/{{$club->id}}/{{$club->name}}" class="club-link">
            <li class="list-group-item list-item-club">
                <div class="media">
                    @if($team_profile and $team_profile->logo)
                    <img class="mr-3 img-fluid" src="{{asset('/storage/clubs/logos/' . $has_team_profile->logo)}}" height="128" width="128" alt="Generic placeholder image">
                    @else
                    <img class="mr-3 img-fluid" src="{{asset('/images/football-1406106_960_720.jpg')}}" height="128" width="128" alt="Generic placeholder image">
                    @endif
                    <div class="media-body">
                        <h5 class="mt-0">{{$club->name}}</h5>
                        @if($team_profile and $team_profile->description)
                        <p class="d-none d-sm-block">Cras sit amet nibh libero, in gravida nulla. Nulla vel metus scelerisque ante sollicitudin. Cras purus odio, vestibulum in vulputate at, tempus viverra turpis. Fusce condimentum nunc ac nisi vulputate fringilla. Donec lacinia congue felis in faucibus.</p>
                        @else
                        <p class="d-none d-sm-block">A club from {{$venue->name}}</p>
                        @endif
                        <ul class="list-inline float-right mt-2">
                            <li class="list-inline-item badge badge-warning">Players: {{$team_players}}</li>
                            <li class="list-inline-item badge badge-warning">Matches: {{$games_recorded}}</li>
                        </ul>
                    </div>
                </div>
                </li>
            </a>
                
            @endforeach
        @else
            <p>There are no clubs registerd with the venue at the moment.</p>    
        @endif
        </ul>
        </div>
    </div>
    <?php $current_page = $clubs->currentPage(); 
      $page_numbers = [$current_page, $current_page + 1, $current_page + 2];
    ?>
    <div class="d-flex justify-content-center mt-5">
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
                @if($page_number < $clubs->lastPage())
                    @if($page_number == $clubs->currentPage())
                    <li class="page-item disabled"><a class="page-link pagination-number" href="?page={{$page_number}}#main" style="background-color: #181818 !important;">{{$page_number}}</a></li>
                    @else
                    <li class="page-item"><a class="page-link pagination-number" href="?page={{$page_number}}#main">{{$page_number}}</a></li>
                    @endif
                @endif
            @endforeach
            @if(($clubs->lastPage() - $current_page) > 1)
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
            @if(($clubs->lastPage() - $current_page) > 3)
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

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>

<script>
$(document).ready(function() {
    $.fn.selectpicker.Constructor.BootstrapVersion = '4';
        $('.selectpicker').selectpicker({
            style: 'btn-secondary',
            size: 4
        });
        $('.selectpickerClub').selectpicker({
            style: 'btn-primary',
            size: 4
        });
});
</script>

@endsection