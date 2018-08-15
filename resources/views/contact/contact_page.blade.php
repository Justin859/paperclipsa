@extends('layouts.app')

@section('styles')

<style>
    label, h1
    {
        color:#ffffff;
    }

    h1 small
    {
        color: #D3D3D3;
    }

    .list-unstyled strong
    {
        color: #FFCC00;
    }

    .list-unstyled li
    {
        color: #ffffff;
    }
</style>

@endsection

@section('content')

<div class="container">
    <div class="row">
        <div class="col-12">
            <img src="{{asset('images/contact-page.jpg')}}" class="img-fluid" />
        </div>
    </div>
    <br />
    <div class="row">

        <div class="col-12 col-md-6">
            <h1>Contact us <small>for queries and support.</small></h1>
            @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
            <form action="/contact/send" method="post" class="needs-validation" id="contact-form" novalidate>
            @csrf
                <div class="form-row">
                    <div class="col-md-6 mb-3">
                        <label for="validationTooltipName">Name</label>
                        <input type="text" name="name" class="form-control"  placeholder="your name" id="validationTooltipName" required />
                        <div class="invalid-tooltip">
                            Please provide a name
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="validationTooltipEmail">Email Address</label>
                        <input type="email" name="email" class="form-control" placeholder="example@exapmle.com" id="validationTooltipEmail" required/>
                        <div class="invalid-tooltip">
                            Please provide an email address.
                        </div>
                    </div>
                </div>

                <label for="validationTooltipQuery">Query</label>
                <div class="form-row">
                <div class="col-md-12 mb-3">
                    <textarea class="form-control" rows="8" maxlength="1001" name="user_query" placeholder="query" id="validationTooltipQuery" required></textarea>
                    <div class="invalid-tooltip">
                        Please provide a query.
                    </div>
                </div>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-warning float-right" id="verify-button">Submit Query</button>
                </div>
                <br />
            </form>
            <br />
        </div>
        <div class="col-12 col-md-6">
        <iframe
        width="100%"
        height="65%"
        frameborder="0" style="border:0"
        src="https://www.google.com/maps/embed/v1/place?key=AIzaSyCO06gpuz2dteQKafDCZevSGMsIQ-VILi8
            &q=Meadowbrook Office Park,Jacaranda+Avenue,Olivedale,Randburg" allowfullscreen>
        </iframe>
        <ul class="list-unstyled">
            <li><strong>Address</strong></li>
            <li>Meadowbrook Office Park, Block D 2nd Floor, 0A Jacaranda Ave, Olivedale, Randburg, 2188, South Africa</li>
            <li><strong>Contact</strong></li>
            <li>+27 011 792 4657</li>
            <li><a href="mailto:info@paperclipsa.co.za?Subject=Online%20Query">info@paperclipsa.co.za</a></li>
        </ul>
        </div>
    </div>
</div>

@endsection

@section('scripts')

<script>

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
        } else {
            document.getElementById('verify-button').innerHTML = 'Submit Query <i class="fas fa-spinner fa-spin"></i>';
        }
        form.classList.add('was-validated');
      }, false);
    });
  }, false);
})();
</script>

@endsection