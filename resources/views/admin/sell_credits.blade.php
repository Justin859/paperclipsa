@extends('layouts.app')

@section('styles')

@include('includes.user_profile_styles')
<style>
    h2, p
    {
        color: #ffffff;
    }

    .twitter-typeahead,
        .tt-hint,
        .tt-input,
        .tt-menu{
            width: 100% ! important;
            background-color: #181818 !important;
            font-weight: normal;
            color: #ffffff !important;
        
        }

    .tt-selectable:hover
    {
        cursor: pointer;
        background-color: #D50000;
    }

    .search-results-dropdown
    {
        margin-top: 25px;
    }

    .fa-search
    {
        position: absolute;
        right: 18px;
        top: 44px;
        color: #ffffff;
        z-index: 999;
    }

    .search-div
    {
        position: relative;
    }

</style>
@endsection

@section('content')

<div class="container">

    <div class="row">
    <div class="col-12 col-md-9 order-md-2">
        <br />
        <h2 class="pt-2"> Add credits to user account balance. </h2><hr />
        <br>
        @if ($errors->any())
                @foreach ($errors->all() as $error)
                <div class="alert alert-danger">
                    {{ $error }}
                </div>
                @endforeach
            </ul>
        @endif
        <form action="/user-profile/admin/sell-credits/request" method="post">
        @csrf
            <div class="form-group search-div">
                <label for="search" class="w-100">User account email address</label>
                <i class="fas fa-search"></i>
                <input type="email" id="search" name="email" class="form-control {{ $errors->has('email') ? ' is-invalid' : '' }}" value="{{ old('email') }}" placeholder="Type to search users email" autocomplete="off" >
            </div>
            <div class="form-group">
                <label for="search" class="w-100">Credits</label>
                <input type="number" id="credits" name="credits" class="form-control" value="50" min="5" max="999999" autocomplete="off" >
                <small id="passwordHelpBlock" class="form-text text-muted">
                    The minimum is 5 credits.
                </small>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-lg btn-outline-warning mb-5 float-right">Add Credits to Account&nbsp;&nbsp;<i class="fas fa-money-bill-wave"></i></button>
            </div>
        </form>
    </div>
    
    @include('includes.user_side_panel')<!-- side panel -->
    </div>
</div>

@endsection

@section('modal')



@endsection

@section('scripts')
<!-- Import typeahead.js -->
<script src="https://twitter.github.io/typeahead.js/releases/latest/typeahead.bundle.js"></script>
 
 <!-- Initialize typeahead.js on the input -->
 <script>
     $(document).ready(function() {
         var bloodhound = new Bloodhound({
             datumTokenizer: Bloodhound.tokenizers.whitespace,
             queryTokenizer: Bloodhound.tokenizers.whitespace,
             remote: {
                 url: '/user/find?q=%QUERY%',
                 wildcard: '%QUERY%'
             },
         });
         
         $('#search').typeahead({
             hint: true,
             highlight: true,
             minLength: 2
         }, {
             name: 'users',
             source: bloodhound,
             display: function(data) {
                 return data.email  //Input value to be set when you select a suggestion. 
             },
             templates: {
                 empty: [
                     '<div class="list-group search-results-dropdown"><div class="list-group-item">Nothing found.</div></div>'
                 ],
                 header: [
                     '<div class="list-group search-results-dropdown">'
                 ],
                 suggestion: function(data) {
                 return '<div style="font-weight:normal; margin-top:-10px ! important;" class="list-group-item">' + data.email + '</div></div>'
                 }
             }
         });
     });
 </script>
@endsection
