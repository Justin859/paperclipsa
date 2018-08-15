@extends('layouts.app')

@section('header')
<script type="text/javascript" src="//player.wowza.com/player/latest/wowzaplayer.min.js"></script>
@endsection

@section('styles')
<style>
    .score-container
    {
        padding: 20px;
        text-align: center;
        background-color: #FFCC00;
        font-size: 25px;
        width: 100%;
    }
    .team-container
    {
        color: #ffffff;
        padding: 20px;
        width: 100%;
        background-color: #505050;
    }
    .fixture-date-time, .fixture-venue
    {
        color:#FFCC00;
    }
    hr
    {
        background-color: #505050;
    }
    .venue-link
    {
        color:#FFCC00;
    }
    .venue-link:hover {
        color:#FFFFFF;
    }
</style>
@endsection

@section('content')
<div class="container" align="center">
    @if($stream_available)
    <div class="row" style="background-color: #000000;">
        <div class="col-md-8 offset-md-2" style="padding: 0px;">
            <div id="playerElement" style="width:100%; height:0; padding:0 0 56.25% 0"></div>
            <img src="{{asset('images/vid_logo_1.jpg')}}" class="img-fluid" height="auto" width="50" style="position: absolute; top:10px; right: 10px;" />        
        </div>
    </div>
    <div class="row">
        <div class="col-md-4 team-container"><h4>{{$fixture->team_a}}</h4></div>
        <div class="col-md-4 score-container"><span id="scoreA">{{$fixture->team_a_goals}}</span> - <span id="scoreB">{{$fixture->team_b_goals}}</span></div>
        <div class="col-md-4 team-container"><h4>{{$fixture->team_b}}</h4></div>
    </div>
    <div class="row">
        <div class="col-sm-12" style="padding: 0px;">
            <h2 class="main-heading" align="left">{{str_replace("_", " ",$live->name)}}</h2>
            <p  class="fixture-date-time" align="left">{{$fixture->date_time}}</p>
            <p class="fixture-venue" align="left"><a class="venue-link" href="#">{{"@" . $current_venue->name}}</a></p>
        </div>
    </div>
    @else
    <div class="row" style="background-color: #000000;">
        <div class="col-md-8 offset-md-2" style="padding: 0px;">
            <div style="width:100%; height:454.35px; padding:0 0 56.25% 0 0; background-color:black; color:white;">
                <div class="btn-group" style="postion:absolute; top:45%;" role="group" aria-label="User Actions">
                @if($account_balance)
                    @if($account_balance->balance_value >= \App\Pricing::find(1)->match)
                    <button id="purchaseButton" class="btn btn-outline-warning" data-toggle="modal" data-target="#areYouSure">Use Credits&nbsp;&nbsp;<span class="fas fa-money-bill"></span></button>
                    @else
                    <button id="purchaseButton" class="btn btn-outline-warning disabled" aria-disabled="true">Use Credits&nbsp;&nbsp;<span class="fas fa-money-bill"></span></button>           
                    @endif
                @else
                    <button id="purchaseButton" class="btn btn-outline-warning disabled" aria-disabled="true">Use Credits&nbsp;&nbsp;<span class="fas fa-money-bill"></span></button>           
                @endif    
                    <button id="buyButton" class="btn btn-outline-warning" data-toggle="modal" data-target="#buyStream">Buy Credits&nbsp;&nbsp;<span class="fas fa-credit-card"></span></button>
                </div>
            </div>        
        </div>
    </div>
    <br />
    <div class="row">
        <div class="col-sm-12" style="padding: 0px;">
            <h2 class="main-heading" align="left">{{str_replace("_", " ",$live->name)}}</h2>
            <p  class="fixture-date-time" align="left">{{$fixture->date_time}}</p>
            <p class="fixture-venue" align="left"><a class="venue-link" href="#">{{"@" . $current_venue->name}}</a></p>
        </div>
    </div>
    @endif
    </br>
    <div class="row">
        <div class="col-md-12">
            <h2 class="main-heading" align="left">More Live Streams</h2>
            <hr />
        </div>
    </div>
    <div class="row d-flex justify-content-center">
            @foreach($more_live_streams as $live_item)
            @if($live_item->stream_type == "live")
            <div class="col-xs-2 col-md-3 vod-item vod-items">
                <a href="/live-now/{{$live_item->id}}/{{$live_item->name}}/" class="js-item">
                    <img src="{{ asset('images/livestream_1.png')}}" height="auto" width="100%" />
                    <i class="far fa-play-circle play-icon" style="display:none;"></i>
                </a>
                <p align="left" style="color: #ffffff; margin: 5px;">{{str_replace("_", " ", $live_item->name)}}</p>
                <p align="left" style="color: #FFCC00; margin: 5px;">@<a href="/channel/{{$live_item->venue_id}}" style="color: #FFCC00;">{{\App\Venue::find($live_item->venue_id)->name}}</a></p>
            </div>
            @endif
        @endforeach
    </div>

</div>

@endsection
@section('modal')
<!-- Modal for purchase request -->
<div id="areYouSure" class="modal" tabindex="-1" role="dialog">
    <form method="post" action="/new/live-now/purchase">
    {{ csrf_field() }}
    <input type="number" name="vod_id" hidden="true" value="{{$live->id}}" readonly/>
    <input type="text" name="vod_name" hidden="true" value="{{$live->name}}" readonly/>

        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Use Tokens To Access Video On Demand</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p><strong>{{$pricing = \App\Pricing::find(1)->match}}</strong> Credits will be deducted</p>
                    <p>Are You Sure ?</p>
                </div>
                <div class="modal-footer">
                    <input type="submit" class="btn btn-warning" value="Yes" />
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </form>
</div>
<!-- End of Modal -->
<!-- Modal for buyying stream -->
<div id="buyStream" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Purchase Subscription or Credits</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p><strong>In order to gain access to on-demand videos and live streams</strong></p>
                <p>You now have the option to pay a monthly subscription for a single venue(R30.00/mo) or full Access(R60.00/mo) to all selected venues.</p>
                <p>Buying credits and using tokens to access videos is still an available option at <strong>5</strong> credits per video.</p>
            </div>
            <div class="modal-footer">
                <a href="/subscription/checkout" class="btn btn-info">Purchase Subscription&nbsp;&nbsp;<span class="fas fa-credit-card"></span></a>
                <a href="/buy" class="btn btn-warning">Purchase Credits&nbsp;&nbsp;<span class="fas fa-coins"></span></a>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- End of Modal -->
@endsection
@section('scripts')
<?php $ip_address = \App\GlobalSetting::find(1)->wowza_server_ip; 
      $app_name = $current_venue->wow_app_name;
?>

<script type="text/javascript">
$( document ).ready(function() {
    WowzaPlayer.create('playerElement',
    {
    "license":"PLAY1-h6N7A-zkt3G-We8UE-Dyxcn-G4Pnb",
    "title":"",
    "description":"",
    "sourceURL":"http%3A%2F%2F192.168.1.69%3A1935%2F{{$app_name}}%2Fstream%3A"+encodeURI("<?php echo $live->name ?>")+".stream_source%2Fplaylist.m3u8",
    "autoPlay":false,
    "useFlash": true,
    "uiShowDurationVsTimeRemaining": true,
    "volume":"75",
    "mute":false,
    "loop":false,
    "audioOnly":false,
    "uiShowQuickRewind":true,
    "uiQuickRewindSeconds":"30"
    }
    
    );

    $('#areYouSure').on('shown.bs.modal', function () {
    $('#purchaseButton').trigger('focus');
    });

    function get_scores(){
        $.ajax({
            type: "GET",
            url: "/api/fixture?fixture_id=<?php echo $fixture->id ?>",
            async: false,
            success: function(response) {
                console.log(response)
                $("#scoreA").html(response.data.team_a_goals);
                $("#scoreB").html(response.data.team_b_goals);
                setTimeout(function(){get_scores();}, 10000);
            }
        });  
    }    
    get_scores();
});

</script>
@endsection