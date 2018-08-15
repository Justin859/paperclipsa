@extends('layouts.app')

@section('styles')

<style>
    .col-md-8
    {
        background-color: #202020;
        border-width: 2px;
        border-color: #181818;
        box-shadow: rgba(0, 0, 0, 9);
        border-style: solid;
    }

    h1, p, label
    {
        color: #ffffff;
    }

    h2
    {
        color: #FFCC00;
    }
</style>

@endsection

@section('content')
<br />
<div class="container">
    <div class="row">
        <div class="col-12 col-md-8 offset-md-2">
            <h1>User Registration</h1>
            <h2>Complete all the fields to register a new account</h2>
            @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
        </div>
    </div>
    <div class="row">
        <div class="col-12 col-md-8 offset-md-2">
        <form method="post" action="/registration/new" name="register_account" id="register_account" class="needs-validation" novalidate>
        {{ csrf_field() }}
            <div class="form-row">
                <div class="col-md-4 mb-3">
                <label for="validationTooltip01">First name</label>
                <input type="text" class="form-control" name="firstname" id="validationTooltip01" value="{{ old('firstname') }}" placeholder="First name" required>
                </div>
                <div class="col-md-4 mb-3">
                <label for="validationTooltip02">Last name</label>
                <input type="text" class="form-control" name="surname" id="validationTooltip02" value="{{ old('surname') }}" placeholder="Last name" required>
                </div>
                <div class="col-md-4 mb-3">
                <label for="validationTooltipEmail">Email address</label>
                <div class="input-group">
                    <input type="email" class="form-control" name="email" id="validationTooltipEmail" placeholder="Email address" value="{{ old('email') }}" aria-describedby="validationTooltipEmailPrepend" required>
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
                <input type="text" class="form-control" name="city" id="validationTooltip04" value="{{ old('city') }}" placeholder="City" required>
                <div class="invalid-tooltip">
                    Please provide a city.
                </div>
                </div>
                <div class="col-md-4 mb-3">
                <label for="validationTooltip05">Province</label>
                <input type="text" class="form-control" name="province" id="validationTooltip05" value="{{ old('privince') }}" placeholder="Province" required>
                <div class="invalid-tooltip">
                    Please provide a valid province.
                </div>
                </div>
            </div>
            <div class="form-row">
                <div class="col-md-4 mb-3">
                <label for="validationTooltip06">Tel</label>
                <input type="text" class="form-control" minlength="10" maxlength="10" name="tel" value="{{ old('tel') }}" onkeyup="this.value=this.value.replace(/[^\d]/,''); checkPhoneNumber()" onchange="checkPhoneNumber()" id="validationTooltip06" placeholder="phone number. ex. 0711112223" required>
                <div class="invalid-tooltip">
                    Please provide a valid phone number.
                </div>
                </div>      
                <div class="col-md-4 mb-3">
                <label for="validationTooltip07">ID Number</label>
                <input type="text" class="form-control" minlength="13" maxlength="13" name="id_number" value="{{ old('id_number') }}" onkeyup="this.value=this.value.replace(/[^\d]/,''); checkIdNumber()" onchange="checkIdNumber()" id="validationTooltip07" placeholder="id number" required>
                <div class="invalid-tooltip">
                    Please provide a valid id number.
                </div>
                </div>    
                <div class="col-md-4 mb-3">
                <label for="validationTooltip08">Gender</label>
                <select type="text" class="form-control" name="gender" id="validationTooltip08">
                    @if(old('gender') == 'm')
                    <option value="m" selected="selected">Male</option>
                    <option value="f">Female</option>
                    @elseif(old('gender') == 'f')
                    <option value="f" selected="selected">Female</option>
                    <option value="m">Male</option>
                    @else
                    <option value="m" selected="selected">Male</option>
                    <option value="f">Female</option>
                    @endif
                </select>
                </div>            
            </div>
            <div class="form-row">
                <div class="col-md-4 mb-3">
                <label for="ValidationTooltip10">Password</label>
                <input type="password" onkeyup="checkPasswords()" class="form-control" name="password" minlength="6" id="ValidationTooltip10" placeholder="New Password" required />
                <div class="invalid-tooltip">
                    Please provide a password.
                </div>
                </div>
                <div class="col-md-4 mb-3">
                <label for="ValidationTooltip11">Confirm Password</label>
                <input type="password" onkeyup="checkPasswords()" class="form-control" name="password_confirmation" id="ValidationTooltip11" placeholder="Confirm Password" required />
                <div class="invalid-tooltip">
                    Passwords do not match.
                </div>
                </div>
            </div>
            <div class="btn-group float-right" style="margin-bottom: 15px;" role="group" aria-label="form buttons">
                <button class="btn btn-outline-warning" type="submit">Register</button>
            </div>
            </form>        
            <p>Already have an account? <a href="/login">Login</a></p>
            <p>Forgot your password? <a href="#">Request Password Reset</a></p>
        </div>
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

        if(el_password.value.trim() === '')
        {
            el_password.setCustomValidity("Please provide a password");
        } else {
            if(el_password.value.length >= 6)
            {
                el_password.setCustomValidity("");

            } else {
                el_password.setCustomValidity("Please provide a password at least 6 characters long.");

            }
        }

        if(el_confirm_password.value.trim() != el_password.value.trim())
        {
            el_confirm_password.setCustomValidity("Passwords do not match.");
        } else {
            el_confirm_password.setCustomValidity("");
        }
    };

    var checkPhoneNumber = function(){
        var el_phone_number = document.getElementById('validationTooltip06');

        if (el_phone_number.value.trim().length != 10)
        {
            el_phone_number.setCustomValidity("Invalid phone number.");
        } else {
            el_phone_number.setCustomValidity("");
        }

    };

    var checkIdNumber = function(){
        var el_id_number = document.getElementById('validationTooltip07');

        if (el_id_number.value.trim().length != 13)
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