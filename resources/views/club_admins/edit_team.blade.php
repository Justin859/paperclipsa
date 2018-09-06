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
    .fa-pen-square
    {
        color: #ffffff;
    } 
    .fa-pen-square:hover
    {

        color: #D50000;
    }
</style>

@include('includes.user_profile_styles')

@endsection

@section('content')

<div class="container">
    <div class="row">
        
        <div class="col-12 col-md-9 order-md-2">
        <br />
        <h2>{{$club->name}} <small class="text-muted">{{$venue->name}}</small></h2><hr />
            <form action="/user-profile/team-admin/my-soccer-club/save" method="post" class="mb-5" name="editClubForm" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="team_id" value="{{$club->id}}">
            <div class="form-group">
                <label for="clubLogo">Club Logo</label>
                <input type="file" id="clubLogo" name="logo_img" class="form-control">
                <small id="clubLogoHelp" class="form-text text-muted">
                    preferably .jpg or .png and equal dimensions ex. 170 X 170.
                </small>
            </div>
            <div class="form-group">
                <label for="clubBio">Club Bio</label>
                @if($has_team_profile)
                <textarea name="club_bio" id="clubBio" class="form-control" cols="30" rows="10" maxlength="1001" placeholder="Club Bio">{{$has_team_profile->description}}</textarea>
                @else
                <textarea name="club_bio" id="clubBio" class="form-control" cols="30" rows="10" maxlength="1001" placeholder="Club Bio"></textarea>
                @endif
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-lg btn-warning float-right"><i class="far fa-save"></i>&nbsp;&nbsp;Save Changes</button>
            </div>
            
            </form>
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