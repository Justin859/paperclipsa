@extends('layouts.app')

@section('styles')

<style>
    h2, p
    {
        color: #ffffff;
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
</style>

@include('includes.user_profile_styles')

@endsection

@section('content')

<div class="container">
    <div class="row">
        
        <div class="col-12 col-md-9 order-md-2">
        <br />
        <h2>Notifications</h2><hr />
        <ul class="list-group list-group-flush">
        @if($notifications->count())
            @foreach($notifications as $key=>$notification)
            <?php $user = \App\User::find($notification->user_id);
                $club = \App\Team::find($notification->team_id);
            ?>
                <li class="list-group-item list-item-notification">
                    <span class="badge badge-warning">{{$key +1}}</span>
                    &nbsp;&nbsp;{{$user->firstname}} {{$user->surname}} requested to be a player for <strong>{{$club->name}}</strong>
                    @if($notification->message)
                    <p style="color:#FFCC00">{{$notification->message}}</p>
                    @endif
                    <ul class="list-inline float-right m-0 p-0">
                        <li class="list-inline-item">
                            <form action="/user-profile/team-admin/notifications/request" method="post" class="m-0 p-0" name="accept_{{$key}}">
                            @csrf
                                <input type="hidden" name="response" value="accept">
                                <input type="hidden" name="notification_id" value="{{$notification->id}}">
                                <button type="submit" class="btn btn-sm btn-success">accept</button>
                            </form>
                        </li>
                        <li class="list-inline-item">
                            <form action="/user-profile/team-admin/notifications/request" method="post" class="m-0 p-0" name="decline_{{$key}}">
                            @csrf
                                <input type="hidden" name="response" value="decline">
                                <input type="hidden" name="notification_id" value="{{$notification->id}}">
                                <button type="submit" class="btn btn-sm btn-danger">decline</button>
                            </form>
                        </li>
                    </ul>
                </li>
                
            @endforeach
        @else
            <p>You have no new notifications at the moment.</p>    
        @endif
        </ul>
        </div> <!-- view panel -->    
        @include('includes.user_side_panel')<!-- side panel -->

    </div>
</div>

@endsection

@section('scripts')
<script>

// Methods

</script>
@endsection