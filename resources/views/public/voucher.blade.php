@extends('layouts.app')

@section('content')

<br />
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Submit Voucher Key') }}</div>

                <div class="card-body">
                    <form method="POST" action="/submit-voucher">
                        @csrf

                        <div class="form-group row">
                            <label for="voucher_key" class="col-sm-4 col-form-label text-md-right">{{ __('Voucher Key') }}</label>

                            <div class="col-md-6">
                                <input id="voucher_key" type="text" class="form-control{{ $errors->has('voucher_key') ? ' is-invalid' : '' }}" name="voucher_key" value="{{ old('voucher_key') }}" required autofocus>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-warning">
                                    {{ __('Submit') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

@endsection