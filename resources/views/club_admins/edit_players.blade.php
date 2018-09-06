@extends('layouts.app')

@section('styles')

<style>
    h2, p
    {
        color: #ffffff;
    }
    .fa-toggle-off, .fa-toggle-on, .fa-times {
        font-size: 24px;
    }
    table th {
        text-align: center; 
    }

    .table {
        margin: auto;
    }

    td {
        vertical-align: middle !important;
    }
    form
    {
        margin: 0px;
        padding: 0px;
    }

    .list-item-notification:hover
    {
        background-color: #202020 !important;
    }

    .club-logo-container
    {
        width: 100%;
        text-align: center;
        position:relative;
    }

    .club-logo-container img
    {
        box-shadow: 3px 3px 1px #ccc;
        -webkit-box-shadow: 3px 3px 1px rgba(255, 255, 255, 0.1);
        -moz-box-shadow: 3px 3px 1px rgba(255, 255, 255, 0.1);
    }
    .fa-pen-square, .fa-user-edit, .fa-user-plus
    {
        color: #ffffff;
    } 
    .fa-pen-square:hover, .fa-user-edit:hover, .fa-user-plus:hover
    {
        color: #D50000;
    }

    .remove-player
    {
        background-color: transparent;
        border: 0px;
        color: #D50000;
    }
    .set-player-status
    {
        background-color: transparent;
        border: 0px;
    }
    .remove-player:hover, .set-player-status:hover
    {
        background-color: transparent;
    }
</style>

@include('includes.user_profile_styles')

@endsection

@section('content')

<div class="container">
    <div class="row">
        
        <div class="col-12 col-md-9 order-md-2">
        <br />
        <h2>{{$club->name}} <small class="text-muted">{{$venue->name}}</small>&nbsp;&nbsp;
            @if($is_admin)
            <a href="/user-profile/my-soccer-clubs/{{$club->id}}/{{$club->name}}/edit" class="float-right"><i class="fas fa-pen-square m-1"></i></a>
            @endif
        </h2><hr />
        <div class="club-logo-container" style="position: relative;">
        @if($has_team_profile and $has_team_profile->logo)
            <div class="club-logo-background" style="background-image: url({{asset('/storage/clubs/logos/' . $has_team_profile->logo)}}); background-size: cover; -webkit-filter: blur(2px);position:absolute;top:0;left:0;right:0;bottom:0;z-index:1;">
            </div>
        @else
            <div class="club-logo-background" style="background-image: url({{asset('/images/football-1406106_960_720.jpg')}}); background-size: cover; -webkit-filter: blur(2px);position:absolute;top:0;left:0;right:0;bottom:0;z-index:1;">
            </div>
        @endif
            <div class="club-logo">
            @if($has_team_profile and $has_team_profile->logo)
            <img src="{{asset('/storage/clubs/logos/' . $has_team_profile->logo)}}" class="img-fluid" style="max-width: 170px; position:relative; z-index: 100;" />
            @else
            <img src="{{asset('/images/football-1406106_960_720.jpg')}}" class="img-fluid" style="max-width: 170px; position:relative; z-index: 100;" />
            @endif
            </div>
        </div>
        <br />
        <h2>Players <small class="text-muted">Edit</small>
            <a href="#" data-toggle="modal" id="addPlayerButton" data-target="#AddPlayerModal"><i class="float-right fas fa-user-plus"></i></a>
        </h2><hr />
        <table class="table table-dark table-responsive-sm mt-3 mb-3 text-center">
            <thead>
                <tr>
                    <th scope="col" class="text-left">#</th>
                    <th scope="col" class="text-left">Name</th>
                    <th scope="col" class="text-left">email</th>
                    <th scope="col" class="text-left">Active</th>
                    <th scope="col" class="text-left">Remove</th>
                </tr>
            </thead>
            <tbody>
                @foreach($team_players as $key=>$team_player)
                <tr>
                    <th scope="row">{{$team_player->id}}</th>
                    <?php 
                        $user = \App\User::find($team_player->user_id);
                        $user_profile = \App\UserProfile::where('user_id', $user->id)->first();
                    ?>
                    <td>
                    <div class="media" align="left">
                    @if($user_profile and $user_profile->profile_image)
                        <img class="mr-3" src="{{asset('storage/userprofile_imgs/'. $user_profile->profile_image)}}" height="64" width="64" alt="Generic placeholder image">
                    @else
                        <img class="mr-3" src="{{asset('storage/userprofile_imgs/profile_img.jpg')}}" height="64" width="64" alt="Generic placeholder image">
                    @endif
                        <div class="media-body">
                            <h5 class="mt-0">{{$user->firstname}} {{$user->surname}}</h5>
                        </div>
                    </div>
                        
                    </td>
                    <td class="text-left">{{$user->email}}</td>
                    
                    <td style="color:#006600; font-weight: bold;">
                        <form action="/user-profile/team-admin/my-soccer-club/player/active-status" method="post" name="setActiveStatus_{{$key + 1}}">
                        @csrf
                            <input type="hidden" name="player_id" value="{{$team_player->id}}">
                            <input type="hidden" name="team_id" value="{{$team_player->team_id}}">
                            <button type="submit" class="btn btn-secondary set-player-status" name="PlayerStatusButton_{{$key + 1}}">
                            @if($team_player->active_status == 'active')
                                <i class="fas fa-toggle-on" style="color: green;"></i>
                            @else
                                <i class="fas fa-toggle-off" style="color: grey;"></i>
                            @endif
                            </button>
                        </form>
                    </td>
                    <td>
                        @if(\Auth::user()->id == $team_player->user_id)
                        <form action="/user-profile/team-admin/my-soccer-club/player/delete" method="post" name="removePalyerForm_{{$key + 1}}" onsubmit="return confirm('Are you sure you want to remove yourself from the club?');">
                        @csrf
                            <input type="hidden" name="player_id" value="{{$team_player->id}}">
                            <input type="hidden" name="team_id" value="{{$team_player->team_id}}">
                            <button type="submit" class="btn btn-danger remove-player" name="removePlayerButton_{{$key + 1}}">
                                <i class="fas fa-times"></i>
                            </button>
                        </form>
                        @else
                        <form action="/user-profile/team-admin/my-soccer-club/player/delete" method="post" name="removePalyerForm_{{$key + 1}}" onsubmit="return confirm('Are you sure you want to delete {{$user->firstname}} {{$user->surname}}?');">
                        @csrf
                            <input type="hidden" name="player_id" value="{{$team_player->id}}">
                            <input type="hidden" name="team_id" value="{{$team_player->team_id}}">
                            <button type="submit" class="btn btn-danger remove-player" name="removePlayerButton_{{$key + 1}}">
                                <i class="fas fa-times"></i>
                            </button>
                        </form>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        
        </div> <!-- view panel -->    
        @include('includes.user_side_panel')<!-- side panel -->

    </div>
</div>

@endsection

@section('modal')
<!-- Modal -->
<div class="modal fade" id="AddPlayerModal" tabindex="-1" role="dialog" aria-labelledby="AddPlayerModalTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Add Player to Club</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="/user-profile/team-admin/my-soccer-club/player/add" method="post" name="add_new_player" id="addPlayerForm" class="m-0 p-0" novalidate>
      @csrf
      <input type="hidden" name="club_id" value="{{$club->id}}">
      <div class="modal-body">
        <div class="form-group">
        <label for="playerEmail" style="color: #000000; font-weight: bold;">Player User Email Address</label>
            <input type="email" name="email" id="playerEmail" placeholder="exampleuser@example.com" value="{{ old('email') }}" class="form-control {{ $errors->has('email') ? ' is-invalid' : '' }}">
            @if ($errors->has('email'))
                <span class="invalid-feedback">
                    <strong>{{ $errors->first('email') }}</strong>
                </span>
            @endif
            <small id="playerEmailHelp" class="form-text text-muted">
                Enter the users email. The email must be the email address that they used to register with Paperclip SA. 
            </small>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Add Player to Team</button>
      </div>
      </form>
    </div>
  </div>
</div>
@endsection

@section('scripts')
@if ($errors->has('email'))
	<script type="text/javascript">
    $(document).ready(function() {
        $('#AddPlayerModal').modal('show');
        $('#AddPlayerModal').on('shown.bs.modal', function () {
        $('#addPlayerButton').trigger('focus');
        });

    });
    </script>
@endif
@endsection