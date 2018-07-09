@extends('layouts.app')

@section('styles')
<script type="text/javascript" src="//player.wowza.com/player/latest/wowzaplayer.min.js"></script>
<link href="{{asset('node_modules/video.js/dist/video-js.css')}}" rel="stylesheet">
<link href="{{asset('node_modules/videojs-watermark/dist/videojs-watermark.css')}}">
<link href="{{asset('node_modules/videojs-overlay/dist/videojs-overlay.css')}}"> 
@endsection

@section('content')

<div class="container" align="center">
    <div class="row" style="background-color: #000000;">
        <div class="col-md-8 offset-md-2" style="padding: 0px;">
            <div id="playerElement" style="width:100%; height:0; padding:0 0 56.25% 0"></div>
            <img src="{{asset('images/vid_logo_1.jpg')}}" class="img-fluid" height="auto" width="50" style="position: absolute; top:10px; right: 10px;" />
        </div>
    </div>
    <hr />
    <div class="row">
        <div class="col-md-8 offset-md-2" style="padding: 0px;">
            <table class="table table-dark text-center">
                <thead>
                    <tr>
                        <th scope="col">Round</th>
                        <th scope="col">{{$fixture->player_1}}</th>
                        <th scope="col">{{$fixture->player_2}}</th>
                        <th scope="col"></th>
                    </tr>
                </thead>
            <tbody id="table-data">
                @foreach($rounds as $key=>$round)
                    <tr>
                        <td>{{$key}}</td>
                        <td>{{$round_points[$key]["player_1"]}}</td>
                        <td>{{$round_points[$key]["player_2"]}}</td>
                        <td></td>
                    </tr>
                @endforeach
            </tbody>
            </table>
        </div>
    </div>
    <div class="col-12">
        <p id="test"></p>
    </div>
</div>

@endsection

@section('modal')
<!-- Modal for purchase request -->
<div id="areYouSure" class="modal" tabindex="-1" role="dialog">
    <form method="post" action="/live-now/squash/purchase">
    {{ csrf_field() }}
    <input type="number" name="vod_id" hidden="true" value="{{$live->id}}" readonly/>
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Use Tokens To Access Video On Demand</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
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

<script src="{{asset('node_modules/video.js/dist/video.js')}}"></script>
<script src="{{asset('node_modules/videojs-contrib-hls/dist/videojs-contrib-hls.min.js')}}"></script>
<script src="{{asset('node_modules/videojs-dynamic-overlay/dist/videojs-newoverlay.min.js')}}"></script>
<script src="{{asset('node_modules/videojs-watermark/dist/videojs-watermark.min.js')}}"></script>

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
    "sourceURL":"http%3A%2F%2F192.168.0.69%3A1935%2F{{$app_name}}%2Fstream%3A"+encodeURI("<?php echo $live->name ?>")+".stream_source%2Fplaylist.m3u8",
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
    function get_scores(){
        $.ajax({
            type: "GET",
            url: "/api/get-squash-score?fixture_id=<?php echo $fixture->id ?>",
            async: false,
            success: function(response) {
                console.log(response);
                var x = "";
                for (i in response["data"]) {
                    x += "<tr>";
                    x += "<td>" + i + "</td>";
                    x += "<td>" + response["data"][i]["player_1"] + "</td>";
                    x += "<td>" + response["data"][i]["player_2"] + "</td>";
                    x += "</tr>";
                }
                document.getElementById("table-data").innerHTML = x;

                setTimeout(function(){get_scores();}, 10000);
            },
            error: function(response) {
                console.log(response);
            }
        });  
    }    
    get_scores();
    });
</script>

@endsection
