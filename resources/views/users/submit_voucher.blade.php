@extends('layouts.app')

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

<br />
<div class="container">
    <div class="row">

        <div class="col-12 col-md-9 order-md-2">
        <br />
        <h2>Submit Voucher Key</h2><hr />
            <form method="POST" action="/submit-voucher">
                @csrf

                <div class="form-group">
                    <label for="voucher_key" class="form-label">{{ __('Voucher Key') }}</label>

                    <input id="voucher_key" type="text" class="form-control {{ $errors->has('voucher_key') ? ' is-invalid' : '' }}" name="voucher_key" value="{{ old('voucher_key') }}" required autofocus>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-warning float-right">
                        {{ __('Submit') }}
                    </button>
                </div>
            </form>
        </div>

        @include('includes.user_side_panel')<!-- side panel -->

    </div>
</div>
</div>

@endsection