@extends('layouts.app')

@section('header')
<script type="text/javascript" src="//player.wowza.com/player/latest/wowzaplayer.min.js"></script>
@endsection

@section('content')
<br />
<div class="container" align="center">
   @if($stream_available)
   @foreach($camera as $camera)
    <div class="row">
        <div class="col-12 col-md-9" style="padding:0px; margin-bottom: 15px;">
        <video src="http://192.168.0.69:1935/VOD_STORAGE_2/mp4:{{$camera}}_source.mp4/playlist.m3u8" data-viblast-key="3234ee02-940e-4ee4-8a26-866bc45b4363" controls width="100%" height="auto"></video>
            <img src="{{asset('images/logo_2 PNG.png')}}" class="img-fluid" height="auto" width="50" style="position: absolute; top:10px; right: 10px;" />
        </div>
    </div>
    @endforeach
    <div class="row" align="left">
        <div class="col-12 col-md-9">
            <h1 style="color: #FFFFFF;">{{$event->eventName}}</h1>   
            <p style="color:#FFCC00;">Live Stream starts @ 12am CAT</p> 
        </div>
    </div>
    @else
    <div class="row">
        <div class="col-12 col-md-9" style="padding:0px;">
            <div style="width:100%; height:454.35px; padding:0 0 56.25% 0 0; background-color:black; color:white;">
            
            <div class="btn-group" style="postion:absolute; top:50%;" role="group" aria-label="User Actions">
            @if($account_balance)
                <button id="purchaseButton" class="btn btn-outline-warning" data-toggle="modal" data-target="#areYouSure">Use Credits&nbsp;&nbsp;<span class="fas fa-money-bill"></span></button>
            @else
                <button id="purchaseButton" class="btn btn-outline-warning disabled" aria-disabled="true">Use Credits&nbsp;&nbsp;<span class="fas fa-money-bill"></span></button>           
            @endif    
                <button id="purchaseButton" class="btn btn-outline-warning">Buy Credits&nbsp;&nbsp;<span class="fas fa-credit-card"></span></button>
            </div>

            </div>        
        </div>
    </div>
    <div class="row" align="left">
        <div class="col-12 col-md-9">
            <h1 style="color: #FFFFFF;">{{$event->eventName}}&nbsp;</h1>   
            <h2><span class="badge badge-secondary float-right" align="right" style="margin-top: 10px;">Cost: 25 Credits</span></h2>
            <p style="color:#FFCC00;">Live Stream starts 2018-06-02 @ 12am CAT</p> 
        </div>
    </div>
    @endif
</div>


@endsection

@section('modal')
<!-- Modal for purchase request -->
<div id="areYouSure" class="modal" tabindex="-1" role="dialog">
    <form method="post" action="/live-event/{{$event->id}}/{{$event->eventName}}">
    {{ csrf_field() }}
    <input type="number" name="event_id" hidden="true" value="{{$event->id}}" readonly/>
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Use Tokens To Access Video On Demand</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p><strong>25 Credits will be deducted to purchase access to this stream.</strong></p>
                    <p><strong>This stream will be available 2018-06-02 @ 12am CAT</strong></p>
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
@endsection

@section('scripts')
@if($stream_available)
<script type="text/javascript">

    var myPlayer = WowzaPlayer.create('playerElement',
        {
        "license":"PLAY1-h6N7A-zkt3G-We8UE-Dyxcn-G4Pnb",
        "title":"",
        "description":"",
        "sourceURL":"http%3A%2F%2F192.168.0.6%3A1935%2FFast_Sport_Fusion_Old_Parks%2Fcam_1_source%2Fplaylist.m3u8",
        "autoPlay":false,
        "volume":"75",
        "mute":false,
        "loop":false,
        "audioOnly":false,
        "uiShowQuickRewind":true,
        "uiQuickRewindSeconds":"30"
        }
    ).play();

        var changeStream = function(camera) {
            var myPlayer = WowzaPlayer.get('playerElement'); 
            
            if (myPlayer != null) {

            myPlayer.destroy();

                myPlayer = WowzaPlayer.create('playerElement',
                {
                "license":"PLAY1-h6N7A-zkt3G-We8UE-Dyxcn-G4Pnb",
                "title":"",
                "description":"",
                "sourceURL":"http%3A%2F%2F192.168.0.6%3A1935%2FFast_Sport_Fusion_Old_Parks%2F"+camera+"_source%2Fplaylist.m3u8",
                "autoPlay":false,
                "volume":"75",
                "mute":false,
                "loop":false,
                "audioOnly":false,
                "uiShowQuickRewind":true,
                "uiQuickRewindSeconds":"30"
                }
            ).play();

            }

       } 
</script>

@else

<script type="text/javascript">
    $('#areYouSure').on('shown.bs.modal', function () {
        $('#purchaseButton').trigger('focus');
    });
</script>

@endif
@endsection