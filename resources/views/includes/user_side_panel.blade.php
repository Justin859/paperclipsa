<?php 
    $user = \Auth::user();
    $user_profile = \App\UserProfile::where('user_id', \Auth::user()->id)->first();
    $is_referee = \App\Referee::where('user_id', $user->id)->first();
    $is_admin = \App\Admin::where('user_id', $user->id)->first();
    $is_superuser = \App\SuperUser::where('user_id', $user->id)->first();
    $is_coach = \App\Coach::where('user_id', $user->id)->first();
    $is_team_admin = \App\TeamAdmin::where('user_id', $user->id)->first();
    $is_team_player = \App\TeamPlayer::where('user_id', $user->id)->first();
?>
<?php 
    $venue = null;
    if($is_admin)
    {
        $venue = \App\Venue::find($is_admin->venue_id);
        $admin_notifications = \App\TeamAdminRequest::where(['venue_id' => $venue->id, 'status' => 'pending'])->count();
        $player_notifications = \App\TeamPlayerRequest::where(['venue_id' => $venue->id, 'status' => 'pending'])->count();

        $notifications_admin = $admin_notifications;

    } else if($is_referee) {
        $venue = \App\Venue::find($is_referee->venue_id);
        $admin_notifications = \App\TeamAdminRequest::where(['venue_id' => $venue->id, 'status' => 'pending'])->count();
        $player_notifications = \App\TeamPlayerRequest::where(['venue_id' => $venue->id, 'status' => 'pending'])->count();

        $notifications_admin = $admin_notifications;
    }

    if($is_team_admin)
    {
        $team_ids = [];
        $team = \App\Team::find($is_team_admin->team_id);
        $venue = \App\Venue::find($team->venue_id);

        $admin_to_teams = \App\TeamAdmin::where('user_id', $user->id)->get();

        foreach($admin_to_teams as $admin_to_team)
        {
            array_push($team_ids, $admin_to_team->team_id);
        }

        $player_notifications = \App\TeamPlayerRequest::whereIn('team_id', $team_ids)->where(['venue_id' => $venue->id, 'status' => 'pending'])->count();

        $notifications_team_admin = $player_notifications;
    }


?>
<div class="col-12 col-md-3 order-md-1">
    <div class="card">
        @if($user_profile)
        <div class="container-image">
            <form method="post" action="/user-profile/image-change" name="change_image" id="change_image_form" style="padding: 0px; margin: 0px;" enctype="multipart/form-data">
            {{ csrf_field() }}
                <input type="image" class="card-img-top image" src="{{asset('storage/userprofile_imgs/'. $user_profile->profile_image)}}" alt="Card image cap">
                
                <div class="middle">
                    <div class="text-overlay">
                    <i class="far fa-edit"></i>
                    <div class="input-group">
                    <div class="custom-file">
                        <input type="file" onchange="this.form.submit()" onclick="clear_file()" name="img_file" id="uploadCaptureInputFile" style="display: none;" required />
                    </div>
                    </div>
                    </div>
                </div>
            </form>
        </div>
        @else
        <div class="container-image">
            <form method="post" action="/user-profile/image-change" name="change_image" id="change_image_form" style="padding: 0px; margin: 0px;" enctype="multipart/form-data">
            {{ csrf_field() }}
                <input type="image" class="card-img-top image" src="{{asset('storage/userprofile_imgs/profile_img.jpg')}}" alt="Card image cap">
                
                <div class="middle">
                    <div class="text-overlay">
                    <i class="far fa-edit"></i>
                    <div class="input-group">
                    <div class="custom-file">
                        <input type="file" onchange="this.form.submit()" onclick="clear_file()" name="img_file" id="uploadCaptureInputFile" style="display: none;" required />
                    </div>
                    </div>
                    </div>
                </div>
            </form>
        </div>
        @endif
        <div class="card-body">
            <h5 class="card-title"><a href="/user-profile" style="text-decoration: none; color: white;">{{\Auth::user()->firstname}} {{\Auth::user()->surname}}</a></h5>
        </div>
        <ul class="list-group list-group-flush">
            @if($is_admin)
            <a href="/admin/dashboard" class="main-side-bar-item"><li class="list-group-item">Dashboard &nbsp;&nbsp;<i class="fas fa-tachometer-alt"></i></li></a>
            @elseif($is_referee)
                @if(\App\Venue::find($is_referee->venue_id)->venue_type == 'indoor_soccer')
                <a href="/referee/dashboard" class="main-side-bar-item"><li class="list-group-item">Dashboard &nbsp;&nbsp;<i class="fas fa-tachometer-alt"></i></li></a>
                @elseif(\App\Venue::find($is_referee->venue_id)->venue_type == 'squash')
                <a href="/referee/squash/dashboard" class="main-side-bar-item"><li class="list-group-item">Dashboard &nbsp;&nbsp;<i class="fas fa-tachometer-alt"></i></li></a>
                @endif
            @elseif($is_coach)
            <a href="/coach/dashboard" class="main-side-bar-item"><li class="list-group-item">Dashboard &nbsp;&nbsp;<i class="fas fa-tachometer-alt"></i></li></a>
            @endif
            @if($is_admin)
            <a href="/user-profile/admin/referees" class="main-side-bar-item"><li class="list-group-item">Referees &nbsp;&nbsp;<i class="fas fa-id-card-alt"></i></li></a>
            @endif
            @if($is_admin or $is_referee)
                @if($_SERVER['REQUEST_URI'] == '/user-profile/admin/teams')
                <a href="/user-profile/admin/teams" class="main-side-bar-item"><li class="list-group-item active-sidebar">Teams &nbsp;&nbsp;<i class="fas fa-users"></i></li></a>
                @else
                <a href="/user-profile/admin/teams" class="main-side-bar-item"><li class="list-group-item">Teams &nbsp;&nbsp;<i class="fas fa-users"></i></li></a> 
                @endif
            @endif
            @if($is_coach)
                @if($_SERVER['REQUEST_URI'] == '/user-profile/coach/age-groups')
                <a href="/user-profile/coach/age-groups" class="main-side-bar-item"><li class="list-group-item active-sidebar">Age Groups &nbsp;&nbsp;<i class="fas fa-users"></i></li></a>
                @else
                <a href="/user-profile/coach/age-groups" class="main-side-bar-item"><li class="list-group-item">Age Groups &nbsp;&nbsp;<i class="fas fa-users"></i></li></a> 
                @endif
            @endif
            @if(!$is_admin and !$is_referee and !$is_coach)
                @if($_SERVER['REQUEST_URI'] == '/user-profile/edit')
                <a href="/user-profile/edit" class="main-side-bar-item"><li class="list-group-item active-sidebar">Edit Profile &nbsp;&nbsp;<i class="far fa-edit"></i></li></a>
                @else
                <a href="/user-profile/edit" class="main-side-bar-item"><li class="list-group-item">Edit Profile &nbsp;&nbsp;<i class="far fa-edit"></i></li></a>
                @endif
            @endif
            @if($is_admin)
                @if($_SERVER['REQUEST_URI'] == '/user-profile/admin/sell-credits')
                <a href="/user-profile/admin/sell-credits" class="main-side-bar-item"><li class="list-group-item active-sidebar">Sell Credits &nbsp;&nbsp;<i class="fas fa-file-invoice-dollar"></i></li></a>
                @else
                <a href="/user-profile/admin/sell-credits" class="main-side-bar-item"><li class="list-group-item">Sell Credits &nbsp;&nbsp;<i class="fas fa-file-invoice-dollar"></i></li></a>
                @endif
            @elseif($is_superuser)
                @if($_SERVER['REQUEST_URI'] == '/user-profile/admin/sell-credits')
                <a href="/user-profile/admin/sell-credits" class="main-side-bar-item"><li class="list-group-item active-sidebar">Give Credits &nbsp;&nbsp;<i class="fas fa-file-invoice-dollar"></i></li></a>
                @else
                <a href="/user-profile/admin/sell-credits" class="main-side-bar-item"><li class="list-group-item">Give Credits &nbsp;&nbsp;<i class="fas fa-file-invoice-dollar"></i></li></a>
                @endif
            @endif
            @if($is_superuser)
                @if($_SERVER['REQUEST_URI'] == '/user-profile/superuser/find-stream')
                <a href="/user-profile/superuser/find-stream" class="main-side-bar-item"><li class="list-group-item active-sidebar">Remove Stream &nbsp;&nbsp;<i class="fab fa-searchengin"></i></li></a>
                @else
                <a href="/user-profile/superuser/find-stream" class="main-side-bar-item"><li class="list-group-item">Remove Stream &nbsp;&nbsp;<i class="fab fa-searchengin"></i></li></a>
                @endif
            @endif
            @if($is_admin)
                @if($_SERVER['REQUEST_URI'] == '/user-profile/admin/balance-statistics')
                <a href="/user-profile/admin/balance-statistics" class="main-side-bar-item"><li class="list-group-item active-sidebar">Balance Statistics &nbsp;&nbsp;<i class="fas fa-calculator"></i></li></a>
                @else
                <a href="/user-profile/admin/balance-statistics" class="main-side-bar-item"><li class="list-group-item">Balance Statistics &nbsp;&nbsp;<i class="fas fa-calculator"></i></li></a>
                @endif
            @endif
            @if(!$is_admin and !$is_referee)
                @if($_SERVER['REQUEST_URI'] == '/user-profile/my-soccer-clubs')
                <a href="/user-profile/my-soccer-clubs" class="main-side-bar-item"><li class="list-group-item active-sidebar">My Soccer Clubs &nbsp;&nbsp;<i class="fas fa-shield-alt"></i></li></a>
                @else
                <a href="/user-profile/my-soccer-clubs" class="main-side-bar-item"><li class="list-group-item">My Soccer Clubs &nbsp;&nbsp;<i class="fas fa-shield-alt"></i></li></a>
                @endif
            @endif
            @if($is_admin or $is_referee)
                @if($_SERVER['REQUEST_URI'] == '/user-profile/admin/notifications')
                <a href="/user-profile/admin/notifications" class="main-side-bar-item"><li class="list-group-item active-sidebar">Notifications &nbsp;&nbsp;<i class="far fa-bell"></i>&nbsp;&nbsp; <span class="badge badge-warning float-right">{{$notifications_admin}}</span></li></a>
                @else
                <a href="/user-profile/admin/notifications" class="main-side-bar-item"><li class="list-group-item">Notifications &nbsp;&nbsp;<i class="far fa-bell"></i>&nbsp;&nbsp; <span class="badge badge-warning float-right">{{$notifications_admin}}</span></li></a>
                @endif
            @endif
            @if($is_team_admin)
                @if($_SERVER['REQUEST_URI'] == '/user-profile/team-admin/notifications')
                <a href="/user-profile/team-admin/notifications" class="main-side-bar-item"><li class="list-group-item active-sidebar">Notifications &nbsp;&nbsp;<i class="far fa-bell"></i>&nbsp;&nbsp; <span class="badge badge-warning float-right">{{$notifications_team_admin}}</span></li></a>
                @else
                <a href="/user-profile/team-admin/notifications" class="main-side-bar-item"><li class="list-group-item">Notifications &nbsp;&nbsp;<i class="far fa-bell"></i>&nbsp;&nbsp; <span class="badge badge-warning float-right">{{$notifications_team_admin}}</span></li></a>
                @endif
            @endif
            @if($is_superuser)
                @if($_SERVER['REQUEST_URI'] == '/user-profile/superuser/venues')
                <a href="/user-profile/superuser/venues" class="main-side-bar-item"><li class="list-group-item active-sidebar">Venues &nbsp;&nbsp;<i class="fas fa-map-marked-alt"></i></li></a>
                @else
                <a href="/user-profile/superuser/venues" class="main-side-bar-item"><li class="list-group-item">Venues &nbsp;&nbsp;<i class="fas fa-map-marked-alt"></i></li></a>
                @endif
            @endif
            @if($is_superuser)
                @if($_SERVER['REQUEST_URI'] == '/user-profile/superuser/admins')
                <a href="/user-profile/superuser/admins" class="main-side-bar-item"><li class="list-group-item active-sidebar">Venue Admins &nbsp;&nbsp;<i class="fas fa-id-card-alt"></i></li></a>
                @else
                <a href="/user-profile/superuser/admins" class="main-side-bar-item"><li class="list-group-item">Venue Admins &nbsp;&nbsp;<i class="fas fa-id-card-alt"></i></li></a>
                @endif
            @endif
            @if($is_superuser)
                @if($_SERVER['REQUEST_URI'] == '/user-profile/superuser/coaches')
                <a href="/user-profile/superuser/coaches" class="main-side-bar-item"><li class="list-group-item active-sidebar">Venue Coaches &nbsp;&nbsp;<i class="fas fa-chalkboard-teacher"></i></li></a>
                @else
                <a href="/user-profile/superuser/coaches" class="main-side-bar-item"><li class="list-group-item">Venue Coaches &nbsp;&nbsp;<i class="fas fa-chalkboard-teacher"></i></li></a>
                @endif
            @endif
            @if(!$is_admin and !$is_coach and !$is_admin and !$is_referee)
                @if($_SERVER['REQUEST_URI'] == '/user-profile/buy-credit')
                <a href="/user-profile/buy-credit" class="main-side-bar-item" ><li class="list-group-item active-sidebar">Buy Credits &nbsp;&nbsp;<i class="fas fa-credit-card"></i></li></a>
                @else
                <a href="/user-profile/buy-credit" class="main-side-bar-item" ><li class="list-group-item">Buy Credits &nbsp;&nbsp;<i class="fas fa-credit-card"></i></li></a>
                @endif
                @if($_SERVER['REQUEST_URI'] == '/user-profile/submit-voucher')
                <a href="/user-profile/submit-voucher" class="main-side-bar-item"><li class="list-group-item active-sidebar">Redeem Voucher &nbsp;&nbsp;<i class="fas fa-ticket-alt"></i></li></a>
                @else
                <a href="/user-profile/submit-voucher" class="main-side-bar-item"><li class="list-group-item">Redeem Voucher &nbsp;&nbsp;<i class="fas fa-ticket-alt"></i></li></a>
                @endif
                @if($_SERVER['REQUEST_URI'] == '/subscription/checkout')
                <a href="/subscription/checkout" class="main-side-bar-item"><li class="list-group-item active-sidebar">Subscribe&nbsp;&nbsp;<i class="fas fa-credit-card"></i>&nbsp;&nbsp;<i class="fas fa-arrows-alt-h"></i> &nbsp;&nbsp;<i class="fas fa-id-card"></i></li></a>
                @else
                <a href="/subscription/checkout" class="main-side-bar-item"><li class="list-group-item">Subscribe&nbsp;&nbsp;<i class="fas fa-credit-card"></i>&nbsp;&nbsp;<i class="fas fa-arrows-alt-h"></i> &nbsp;&nbsp;<i class="fas fa-id-card"></i></li></a>
                @endif       
            @endif                 
        </ul>
    </div>
</div>