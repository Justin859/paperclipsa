<div class="col-12 col-md-3 order-md-1">
    <div class="card">
        <?php $user_profile = \App\UserProfile::where('user_id', \Auth::user()->id)->first(); ?>

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
            <a href="/referee/dashboard" class="main-side-bar-item"><li class="list-group-item">Dashboard &nbsp;&nbsp;<i class="fas fa-tachometer-alt"></i></li></a>
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
            @if($_SERVER['REQUEST_URI'] == '/user-profile/edit')
            <a href="/user-profile/edit" class="main-side-bar-item"><li class="list-group-item active-sidebar">Edit Profile &nbsp;&nbsp;<i class="far fa-edit"></i></li></a>
            @else
            <a href="/user-profile/edit" class="main-side-bar-item"><li class="list-group-item">Edit Profile &nbsp;&nbsp;<i class="far fa-edit"></i></li></a>
            @endif
            @if($is_superuser)
                @if($_SERVER['REQUEST_URI'] == '/user-profile/superuser/venues')
                <a href="/user-profile/superuser/venues" class="main-side-bar-item"><li class="list-group-item active-sidebar">Venues &nbsp;&nbsp;<i class="fas fa-map-marked-alt"></i></li></a>
                @else
                <a href="/user-profile/superuser/venues" class="main-side-bar-item"><li class="list-group-item">Venues &nbsp;&nbsp;<i class="fas fa-map-marked-alt"></i></li></a>
                @endif
            @endif
            @if($is_superuser)
                @if($_SERVER['REQUEST_URI'] == '/user-profile/superuser/coaches')
                <a href="/user-profile/superuser/coaches" class="main-side-bar-item"><li class="list-group-item active-sidebar">Coaches &nbsp;&nbsp;<i class="fas fa-chalkboard-teacher"></i></li></a>
                @else
                <a href="/user-profile/superuser/coaches" class="main-side-bar-item"><li class="list-group-item">Coaches &nbsp;&nbsp;<i class="fas fa-chalkboard-teacher"></i></li></a>
                @endif
            @endif
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
        </ul>
    </div>
</div>