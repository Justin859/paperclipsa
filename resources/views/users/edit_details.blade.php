@extends('layouts.app')

@section('styles')

<style>
    h2, p
    {
        color: #ffffff;
    }
</style>

@include('includes.user_profile_styles')

@endsection

@section('content')

<div class="container">
    <div class="row">
        
        <div class="col-12 col-md-9 order-md-2">
        <br />
        <h2>Edit Details</h2><hr />
        @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif
        <form method="post" action="/user-profile/update" name="update_profile" id="update_profile" class="needs-validation" novalidate>
        {{ csrf_field() }}
            <div class="form-row">
                <div class="col-md-4 mb-3">
                <label for="validationTooltip01">First name</label>
                <input type="text" class="form-control" name="firstname" id="validationTooltip01" placeholder="First name" value="{{$user->firstname}}" required>
                <!-- <div class="valid-tooltip">
                    Looks good!
                </div> -->
                </div>
                <div class="col-md-4 mb-3">
                <label for="validationTooltip02">Last name</label>
                <input type="text" class="form-control" name="surname" id="validationTooltip02" placeholder="Last name" value="{{$user->surname}}" required>
                <!-- <div class="valid-tooltip">
                    Looks good!
                </div> -->
                </div>
                <div class="col-md-4 mb-3">
                <label for="validationTooltipEmail">Email address</label>
                <div class="input-group">
                    <input type="email" class="form-control" name="email" id="validationTooltipEmail" value="{{$user->email}}" placeholder="Email address" aria-describedby="validationTooltipEmailPrepend" required>
                    <div class="invalid-tooltip">
                    Please choose a valid email address.
                    </div>
                </div>
                </div>
            </div>
            <div class="form-row">
                <div class="col-md-4 mb-3">
                <label for="validationTooltip03">Country</label>
                <select type="text" class="form-control" name="country" id="validationTooltip03">
                    <option value="South Africa" selected="selected">South Africa</option>                
                </select>
                
                </div>
                <div class="col-md-4 mb-3">
                <label for="validationTooltip04">City</label>
                @if($user->city)
                <input type="text" class="form-control" name="city" value="{{$user->city}}" id="validationTooltip04" placeholder="City" required>
                @else
                <input type="text" class="form-control" name="city" id="validationTooltip04" placeholder="City" required>
                @endif
                <div class="invalid-tooltip">
                    Please provide a city.
                </div>
                </div>
                <div class="col-md-4 mb-3">
                <label for="validationTooltip05">Province</label>
                @if($user->province)
                <input type="text" class="form-control" name="province" value="{{$user->province}}" id="validationTooltip05" placeholder="Province" required>
                @else
                <input type="text" class="form-control" name="province" id="validationTooltip05" placeholder="Province" required>
                @endif
                <div class="invalid-tooltip">
                    Please provide a valid province.
                </div>
                </div>
            </div>
            <div class="form-row">
                <div class="col-md-4 mb-3">
                <label for="validationTooltip06">Tel</label>
                @if($user->tel)
                <input type="text" class="form-control" minlength="10" maxlength="10" name="phone_number" onkeyup="this.value=this.value.replace(/[^\d]/,''); checkPhoneNumber()" value="{{$user->tel}}" id="validationTooltip06" placeholder="phone number. ex. 0711112223" required>
                @else
                <input type="text" class="form-control" minlength="10" maxlength="10" name="phone_number" onkeyup="this.value=this.value.replace(/[^\d]/,''); checkPhoneNumber()" id="validationTooltip06" placeholder="phone number. ex. 0711112223" required>
                @endif
                <div class="invalid-tooltip">
                    Please provide a valid phone number.
                </div>
                </div>      
                <div class="col-md-4 mb-3">
                <label for="validationTooltip07">ID Number</label>
                @if($user->id_number)
                <input type="text" class="form-control" minlength="13" maxlength="13" name="id_number" onkeyup="this.value=this.value.replace(/[^\d]/,''); checkIdNumber()"  value="{{$user->id_number}}" id="validationTooltip07" placeholder="id number" required>
                @else
                <input type="text" class="form-control" minlength="13" maxlength="13" name="id_number" onkeyup="this.value=this.value.replace(/[^\d]/,''); checkIdNumber()" id="validationTooltip07" placeholder="id number" required>
                @endif                
                <div class="invalid-tooltip">
                    Please provide a valid id number.
                </div>
                </div>    
                <div class="col-md-4 mb-3">
                <label for="validationTooltip08">Gender</label>
                <select type="text" class="form-control" name="gender" id="validationTooltip08">
                    @if($user->gender == 'none' or $user->gender == 'm')
                    <option value="m" selected="selected">Male</option>
                    <option value="f">Female</option>
                    @else
                    <option value="f" selected="selected">Female</option>
                    <option value="m">Male</option>
                    @endif
                </select>
                </div>            
            </div>
            <div class="form-row">
                <div class="col-md-4 mb-3">
                <label for="ValidationTooltip10">Password</label>
                <input type="password" onkeyup="checkPasswords()" class="form-control" name="password_new" id="ValidationTooltip10" placeholder="New Password">
                <small id="passwordHelpBlock" class="form-text text-muted">
                    Leave this field blank if you would like to keep your current password.
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
                <a href="/user-profile" class="btn btn-outline-danger">Back</a>
                <button class="btn btn-outline-warning" type="submit">Save</button>
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

    window.onload = function() {
        const phone_number = document.getElementById('validationTooltip06');
        const id_number = document.getElementById('validationTooltip07');
        const confirm_password = document.getElementById('ValidationTooltip11');
        phone_number.onpaste = function(e) {
        e.preventDefault();
        }
        id_number.onpaste = function(e) {
        e.preventDefault();
        }
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

    var checkPhoneNumber = function(){
        var el_phone_number = document.getElementById('validationTooltip06');

        if (el_phone_number.value.length != 10)
        {
            el_phone_number.setCustomValidity("Invalid phone number.");
        } else {
            el_phone_number.setCustomValidity("");
        }

    };

    var checkIdNumber = function(){
        var el_id_number = document.getElementById('validationTooltip07');

        if (el_id_number.value.length != 13)
        {
            el_id_number.setCustomValidity("Invalid id number.");
        } else {
            el_id_number.setCustomValidity("");
        }
    };

// Example starter JavaScript for disabling form submissions if there are invalid fields
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