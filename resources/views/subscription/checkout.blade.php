@extends('layouts.app')

@section('styles')

<style>
    /* Customize the label (the container) */
    .container-w3 {
      display: block;
      position: relative;
      padding-left: 35px;
      margin-bottom: 12px;
      cursor: pointer;
      font-size: 22px;
      -webkit-user-select: none;
      -moz-user-select: none;
      -ms-user-select: none;
      user-select: none;
    }

    /* Hide the browser's default radio button */
    .container-w3 input {
      position: absolute;
      opacity: 0;
    }

    /* Create a custom radio button */
    .checkmark {
      position: absolute;
      top: 0;
      left: 0;
      height: 25px;
      width: 25px;
      background-color: #eee;
      border-radius: 50%;
    }

    /* On mouse-over, add a grey background color */
    .container-w3:hover input ~ .checkmark {
      background-color: #ccc;
    }

    /* When the radio button is checked, add a blue background */
    .container-w3 input:checked ~ .checkmark {
      background-color: #2196F3;
    }

    /* Create the indicator (the dot/circle - hidden when not checked) */
    .checkmark:after {
      content: "";
      position: absolute;
      display: none;
    }

    /* Show the indicator (dot/circle) when checked */
    .container-w3 input:checked ~ .checkmark:after {
      display: block;
    }

    /* Style the indicator (dot/circle) */
    .container-w3 .checkmark:after {
      top: 9px;
      left: 9px;
      width: 8px;
      height: 8px;
      border-radius: 50%;
      background: white;
    }
    </style>

@endsection

@section('content')

<br />
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Purchase Subscription') }}</div>

                <div class="card-body">
                <form action="/subscription/checkout" method="POST">
                {{ csrf_field() }}
                    <div class="form-check form-check-inline">
                        <label class="form-check-label container-w3" for="inlineRadio1">Single Channel Subscription &nbsp;<span class="badge badge-warning">R30.00</span><small>&nbsp;(Indoor soccer and soccer schools)</small>
                        <input class="form-check-input" checked="checked" type="radio" name="subscription_type" id="inlineRadio1" value="single_channel" />
                        <span class="checkmark"></span>
                        </label>
                    </div>
                    <div class="form-check form-check-inline">
                        <label class="form-check-label container-w3" for="inlineRadio2">Full Access Subscription &nbsp;<span class="badge badge-warning">R60.00</span><small>&nbsp;(Indoor soccer only)</small>
                        <input class="form-check-input" type="radio" name="subscription_type" id="inlineRadio2" value="full_access" />
                        <span class="checkmark"></span>
                        </label>
                    </div>
                    <div class="form-group row" id="select_channel">
                        <label class="col-sm-2 col-form-label" for="channel">Channel</label>
                        <div class="col-sm-10">
                        <select type="hidden" class="form-control" name="channel">
                            @foreach($channels as $channel)
                            <option value="{{$channel->id}}">{{$channel->name}}</option>
                            @endforeach
                        </select>  
                        </div>
                    </div>
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-danger"><i class="fas fa-credit-card"></i>&nbsp;&nbsp;Pay now</button>
                        </div>
                </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')

<script type="text/javascript">
    $( document ).ready(function() {
        if ($('input[type=radio][name=subscription_type]:checked').val() == 'single_channel') {
            $('#select_channel').show();
        } else {
            $('#select_channel').hide();
        }

        console.log($('input[type=radio][name=subscription_type]:checked').val());

        $('input[type=radio][name=subscription_type]').change(function() {
          if (this.value == 'single_channel') {
              $('#select_channel').show();
          }
          else if (this.value == 'full_access') {
              $('#select_channel').hide();
          }
      });
    });
</script>

@endsection