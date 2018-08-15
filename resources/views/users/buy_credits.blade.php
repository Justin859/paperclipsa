@extends('layouts.app')

@section('header')
<link rel="stylesheet" href="{{asset('/add_ons/breadcrumbs-and-multistep-indicator/css/style.css')}}">
<link rel="stylesheet" href="{{asset('/add_ons/breadcrumbs-and-multistep-indicator/css/reset.css')}}">

@endsection

@section('styles')

<style>
    h2
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
        <div class="row">
            <div class="col-12">
            <h2>Buy Credits</h2><hr />
            <nav>
                <ol class="cd-multi-steps text-center">
                    <li class="current"><em style="font-size: 20px;">Checkout</em></li>
                    <li><em style="font-size: 20px;">Confirm</em></li>
                    <li><em style="font-size: 20px;">Done</em></li>
                </ol>
            </nav>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <form method="post" action="/user-profile/buy-credits" id="validation-purchase" class="needs-validation-purchase" name="purchase_credits" novalidate>
                {{ csrf_field() }}
                <div class="form-group">
                    <label for="validationTooltipAmount">Choose an Amount (ZAR)</label>
                    <input type="number" name="credit_amount" id="validationTooltipAmount" class="form-control form-control-lg" min="50" value="50" max="999999" aria-describedby="validationTooltipAmountPrepend" required>
                    <div class="invalid-tooltip">
                    Please choose a valid amount.
                    </div>
                    <small id="passwordHelpBlock" class="form-text text-muted">
                        <ul>
                            <li>R50 is equal to 50 credits</li>
                            <li>The minimum amount of credits that you can buy at a time is 50.</li>
                        </ul>
                    </small>
                </div>
                <div class="form-group">
                    <input type="submit" class="btn btn-outline-warning float-right mb-3" value="proceed to checkout" />
                </div>
                </form>
            </div>  
        </div>
    </div>
    @include('includes.user_side_panel')<!-- side panel -->

    </div>
</div>

@endsection 

@section('scripts')
<script type="text/javascript" src="{{asset('/add_ons/breadcrumbs-and-multistep-indicator/js/modernizr.js')}}"></script>
<script>
(function() {
  'use strict';
  window.addEventListener('load', function() {
    // Fetch all the forms we want to apply custom Bootstrap validation styles to
    var forms = document.getElementsByClassName('needs-validation-purchase');
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