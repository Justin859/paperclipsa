@extends('layouts.app')


@section('styles')

@include('includes.user_profile_styles')

@endsection

@section('content')

<div class="container">
    <div class="row">
        <div class="col-12 col-md-9 order-md-2">
        <br />
        <h2 style="color:#ffffff;">Edit Admin</h2>
        <hr />
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form method="post" action="/user-profile/superuser/admin/save" name="update_profile" id="update_profile" class="needs-validation" novalidate>
        {{ csrf_field() }}
        <input type="hidden" name="admin_user_id" value="{{$admin_user->id}}" required/>
        <div class="form-row">
            <div class="col-md-4 mb-3">
            <label for="validationTooltip01">First name</label>
            <input type="text" class="form-control" name="firstname" id="validationTooltip01" placeholder="First name" value="{{$admin_user->firstname}}" required>
            <div class="invalid-tooltip">
                Please provide a first name.
            </div>
            </div>
            <div class="col-md-4 mb-3">
            <label for="validationTooltip02">Last name</label>
            <input type="text" class="form-control" name="surname" id="validationTooltip02" placeholder="Last name" value="{{$admin_user->surname}}" required>
            <div class="invalid-tooltip">
                Please provide a last name.
            </div>
            </div>
            <div class="col-md-4 mb-3">
            <label for="validationTooltipEmail">Email address</label>
            <div class="input-group">
                <input type="email" class="form-control" name="email" id="validationTooltipEmail" value="{{$admin_user->email}}" placeholder="Email address" aria-describedby="validationTooltipEmailPrepend" required>
                <div class="invalid-tooltip">
                Please choose a valid email address.
                </div>
            </div>
            </div>
        </div>
        <div class="form-row">
            <div class="col-md-4 mb-3">
            <label for="validationTooltip08">Gender</label>
            <select type="text" class="form-control" name="gender" id="validationTooltip08">
                @if($admin_user->gender == 'none' or $admin_user->gender == 'm')
                <option value="m" selected="selected">Male</option>
                <option value="f">Female</option>
                @else
                <option value="f" selected="selected">Female</option>
                <option value="m">Male</option>
                @endif
            </select>
            </div> 
            <div class="col-md-4 mb-3">
                <label for="ValidationTooltip10">Password</label>
                <input type="password" onkeyup="checkPasswords()" class="form-control" name="password_new" id="ValidationTooltip10" placeholder="New Password">
                <small id="passwordHelpBlock" class="form-text text-muted">
                    Leave this field blank if you would like to keep the current password.
                </small>
                <div class="invalid-tooltip">
                    Please provide a password.
                </div>
                </div>
                <div class="col-md-4 mb-3">
                <label for="ValidationTooltip11">Confirm Password</label>
                <input type="password" onkeyup="checkPasswords()" class="form-control" name="password_confrim" id="ValidationTooltip11" placeholder="Confirm Password">
                <div class="invalid-tooltip">
                    Passwords do not match.
                </div>
            </div> 
        </div>
        <div class="btn-group float-right" style="margin-bottom: 15px;" role="group" aria-label="form buttons">
            <a href="/user-profile/superuser/admins" class="btn btn-outline-danger">Back</a>
            <button class="btn btn-outline-warning" type="submit">Save</button>
        </div>
        </form>
        </div>
        @include('includes.user_side_panel')<!-- side panel -->

    </div>
</div>

@endsection

@section('scripts')

<script type="text/javascript">

window.onload = function() {
        const confirm_password = document.getElementById('ValidationTooltip11');
        confirm_password.onpaste = function(e) {
        e.preventDefault();
        }
    }

    var checkPasswords = function(){
        var el_password = document.getElementById('ValidationTooltip10');
        var el_confirm_password = document.getElementById('ValidationTooltip11');

        if(el_confirm_password.value.trim() != el_password.value.trim())
        {
            el_confirm_password.setCustomValidity("Passwords do not match.");
        } else {
            el_confirm_password.setCustomValidity("");
        }
    };

(function() {
  'use strict';
  window.addEventListener('load', function() {
    // Fetch all the forms we want to apply custom Bootstrap validation styles to
    var forms = document.getElementsByClassName('needs-validation');
    // Loop over them and prevent submission
    var validation = Array.prototype.filter.call(forms, function(form) {
      form.addEventListener('submit', function(event) {
        if (form.checkValidity() === false) {
          event.preventDefault();
          event.stopPropagation();
        }
        form.classList.add('was-validated');
      }, false);
    });
  }, false);
})();

</script>

@endsection