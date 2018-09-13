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
        <h2 class="pt-2"> Remove Stream </h2><hr />
        <br>
        @if ($errors->any())
                @foreach ($errors->all() as $error)
                <div class="alert alert-danger">
                    {{ $error }}
                </div>
                @endforeach
            </ul>
        @endif
        <form action="/user-profile/superuser/delete-stream/request" method="post">
        @csrf
            <div class="form-group search-div">
                <label for="search" class="w-100">Stream Unique Name</label>
                <i class="fas fa-search"></i>
                <input type="text" id="search" name="stream_name" class="form-control {{ $errors->has('stream_name') ? ' is-invalid' : '' }}" value="{{ old('stream_name') }}" placeholder="Type to search streams" autocomplete="off" >
                <small id="passwordHelpBlock" class="form-text text-warning">
                    <ul>
                        <li>The name of the stream must be in the format -> Test_1_VS_Test_2_2018_09_12_212931.</li>
                        <li>If the video file must be deleted select the option to delete both the file and the video. If only the database record is deleted there will be no record to find the file on the servers storage.</li>
                    </ul>
                </small>
            </div>
            <div class="form-group">
                <label for="search" class="w-100">Action</label>
                <select name="action" id="selectAction" class="form-control">
                    <option value="stream_and_file" selected="selected">Delete Stream From Database And Video File</option>
                    <option value="stream_only">Delete Stream From Database</option>
                </select>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-lg btn-outline-danger mb-5 float-right">Delete&nbsp;&nbsp;<i class="far fa-trash-alt"></i></button>
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
                 url: '/stream/find?q=%QUERY%',
                 wildcard: '%QUERY%'
             },
         });
         
         $('#search').typeahead({
             hint: true,
             highlight: true,
             minLength: 1,
         }, {
             name: 'streams',
             source: bloodhound,
             display: function(data) {
                 return data.name  //Input value to be set when you select a suggestion. 
             },
             templates: {
                 empty: [
                     '<div class="list-group search-results-dropdown"><div class="list-group-item">Nothing found.</div></div>'
                 ],
                 header: [
                     '<div class="list-group search-results-dropdown">'
                 ],
                 suggestion: function(data) {
                 return '<div style="font-weight:normal; margin-top:-10px ! important;" class="list-group-item">' + data.name + '</div></div>'
                 }
             }
         });
     });
 </script>
@endsection
