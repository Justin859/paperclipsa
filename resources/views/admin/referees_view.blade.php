@extends('layouts.app')

@section('styles')

@include('includes.user_profile_styles')
<style>
    h2, p
    {
        color: #ffffff;
    }
    .fa-plug, .fa-trash, .fa-edit, .fa-toggle-on
    , .fa-toggle-off {
        font-size: 24px;
    }

    .fa-toggle-on
    {
        color: #228B22;
    }
    .table {
        margin: auto;
    }

    td, th {
        vertical-align: middle !important;
    }

    .pagination
    {
        font-size: 24px;
    }
    .fa-step-forward, .fa-step-backward, .fa-fast-forward, .fa-fast-backward 
    {
        color: rgba(208, 0, 0);
    }

    .pagination-number
    {
        color: #FFFFFF !important;
        background-color: rgba(208, 0, 0) !important;
    }

    .pagination-skip, .pagination-number-active
    {
        background-color: #181818 !important;
    }
</style>
@endsection

@section('content')

<div class="container">

    <div class="row">
        
    <div class="col-12 col-md-9 order-md-2">
        <br />
        <h2 class="pt-2"> Venue Referees </h2><hr />
        <div class="row d-flex d-sm-flex justify-content-end p-2">
            <button class="btn btn-large btn-danger" id="newRefereeButton" data-toggle="modal" data-target="#AddNewReferee"><i class="far fa-plus-square"></i>&nbsp;&nbsp;ADD REFEREE</button>
        </div>
        <br />
        @if($referee_users)
        <table class="table table-striped table-dark table-responsive-sm table-sm text-center">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Referee Name</th>
                    <th scope="col">Status</th>
                    <th scope="col">Edit</th>
                    <th scope="col">Delete</th>
                </tr>
            </thead>
            <tbody>
                @foreach($referee_users as $key=>$referee)
                <tr>
                    <th scope="row">{{$key + 1}}</th>
                    <td>{{$referee->firstname}} {{$referee->surname}}</td>
                    <td>
                    <form name="form_active{{$key + 1}}" action="/user-profile/admin/referee/set-active" method="post">
                        @csrf
                        <input type="text" name="referee_id" value="{{$referee->id}}" hidden readonly />
                        
                        <button type="submit" name="submit_{{$key + 1}}" class="btn" style="background-color: transparent;">
                            @if($referee->active_status == 'active')
                            <i class="fas fa-toggle-on"></i>
                            @else
                            <i class="fas fa-toggle-off"></i>
                            @endif
                        </button>
                    </form>  
                    </td>
                    <td><a class="btn" href="/user-profile/admin/referees/edit/{{$referee->id}}/{{$referee->firstname}}"><i class="far fa-edit"></i></a></td>
                    <td>
                        <form name="form_{{$key + 1}}" action="/user-profile/admin/referee/delete" method="post" onsubmit="return confirm('Are you sure you want to delete {{$referee->name}}?');">
                            @csrf
                            <input type="hidden" name="referee_user_id" value="{{$referee->id}}" readonly />
                            <button type="submit" name="submit_{{$key + 1}}" class="btn" style="background-color: transparent;"><i class="fas fa-trash"></i></button>
                        </form>  
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <br />
        <?php $current_page = $referee_users->currentPage(); 
        $page_numbers = [$current_page, $current_page + 1, $current_page + 2];
        ?>
        <div class="d-flex justify-content-center">
            <nav aria-label="Page navigation example" width="100%">
            <ul class="pagination">
                @if($current_page > 2)
                <li class="page-item">
                    <a class="page-link pagination-skip" href="?page={{$current_page - 3}}" aria-label="Next">
                        <span class="fas fa-fast-backward" aria-hidden="true"></span>
                        <span class="sr-only">back</span>
                    </a>
                </li>
                @else
                <li class="page-item disabled">
                    <a class="page-link pagination-skip" href="?page={{$current_page - 3}}" aria-label="Next">
                        <span class="fas fa-fast-backward" style="color: #ffffff !important;" aria-hidden="true"></span>
                        <span class="sr-only">back</span>
                    </a>
                </li>
                @endif
                @if($current_page > 1)
                <li class="page-item">
                    <a class="page-link pagination-skip" href="?page={{$current_page - 1}}" aria-label="Next">
                        <span class="fas fa-step-backward" aria-hidden="true"></span>
                        <span class="sr-only">back 3</span>
                    </a>
                </li>
                @else
                <li class="page-item disabled">
                    <a class="page-link pagination-skip" href="?page={{$current_page - 1}}" style="color: #ffffff !important;" aria-label="Next">
                        <span class="fas fa-step-backward" style="color: #ffffff !important;" aria-hidden="true"></span>
                        <span class="sr-only">back 3</span>
                    </a>
                </li>
                @endif
                @foreach($page_numbers as $page_number)
                    @if($page_number < $referee_users->lastPage())
                        @if($page_number == $referee_users->currentPage())
                        <li class="page-item disabled"><a class="page-link pagination-number" href="?page={{$page_number}}" style="background-color: #181818 !important;">{{$page_number}}</a></li>
                        @else
                        <li class="page-item"><a class="page-link pagination-number" href="?page={{$page_number}}">{{$page_number}}</a></li>
                        @endif
                    @endif
                @endforeach
                @if(($referee_users->lastPage() - $current_page) > 1)
                <li class="page-item">
                    <a class="page-link pagination-skip" href="?page={{$current_page + 1}}" aria-label="Next">
                        <span class="fas fa-step-forward" aria-hidden="true"></span>
                        <span class="sr-only">Next</span>
                    </a>
                </li>
                @else
                <li class="page-item disabled">
                    <a class="page-link pagination-skip"  href="?page={{$current_page + 1}}" aria-label="Next">
                        <span class="fas fa-step-forward" style="color: #ffffff !important;" aria-hidden="true"></span>
                        <span class="sr-only">Next</span>
                    </a>
                </li>
                @endif
                @if(($referee_users->lastPage() - $current_page) > 3)
                <li class="page-item">
                    <a class="page-link pagination-skip" href="?page={{$current_page + 3}}" aria-label="Next">
                        <span class="fas fa-fast-forward" aria-hidden="true"></span>
                        <span class="sr-only">Next 3</span>
                    </a>
                </li>
                @else
                <li class="page-item disabled">
                    <a class="page-link pagination-skip" href="?page={{$current_page + 3}}" aria-label="Next">
                        <span class="fas fa-fast-forward" style="color: #ffffff !important;" aria-hidden="true"></span>
                        <span class="sr-only">Next 3</span>
                    </a>
                </li>
                @endif
            </ul>
            </nav>
        </div>        
        @else

        <p>There are no referees added to this venue yet</p>

        @endif
    </div>
    
    @include('includes.user_side_panel')<!-- side panel -->
    </div>
</div>

@endsection

@section('modal')

<div class="modal fade" id="AddNewReferee" tabindex="-1" role="dialog" aria-labelledby="AddNewRefereeLabel" aria-hidden="true">
<form action="/user-profile/admin/referee/new" method="post">
{{ csrf_field() }}
<input type="hidden" value="{{$venue_id}}" name="venue_id" required />
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="AddNewRefereeLabel">Add New Referee</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">        
        <div class="form-group row">
            <label for="refereeName" class="col-sm-4 text-md-right" style="color:#000000">First Name</label>
            <div class="col-md-6">
                <input type="text" class="form-control {{ $errors->has('firstname') ? ' is-invalid' : '' }}" maxlength="100" minlength="1" name="firstname" value="{{ old('firstname') }}"id="refereeName" placeholder="First name" autofocus/>
                @if ($errors->has('firstname'))
                    <span class="invalid-feedback">
                        <strong>{{ $errors->first('firstname') }}</strong>
                    </span>
                @endif
            </div>
        </div>
        <div class="form-group row">
            <label for="refereeSurname" class="col-sm-4 text-md-right" style="color:#000000">Last Name</label>
            <div class="col-md-6">
                <input type="text" class="form-control {{ $errors->has('firstname') ? ' is-invalid' : '' }}" maxlength="100" name="surname" value="{{ old('surname') }}"id="refereeSurname" placeholder="Last name" />
                @if ($errors->has('surname'))
                    <span class="invalid-feedback">
                        <strong>{{ $errors->first('surname') }}</strong>
                    </span>
                @endif
            </div>
        </div>
        <div class="form-group row">
            <label for="refereeEmail" class="col-sm-4 text-md-right" style="color:#000000">Email Address</label>
            <div class="col-md-6">
                <input type="email" class="form-control {{ $errors->has('email') ? ' is-invalid' : '' }}" maxlength="100" name="email" value="{{ old('email') }}" id="refereeEmail" placeholder="Email address" />
                @if ($errors->has('email'))
                    <span class="invalid-feedback">
                        <strong>{{ $errors->first('email') }}</strong>
                    </span>
                @endif
            </div>
        </div>
        <div class="form-group row">
            <label for="refereeGender" class="col-sm-4 text-md-right" style="color:#000000">Gender</label>
            <div class="col-md-6">
            <select type="text" class="form-control" name="gender" id="validationTooltip08">
                <option value="m" selected="selected">Male</option>
                <option value="f">Female</option> 
            </select>
            @if ($errors->has('gender'))
                <span class="invalid-feedback">
                    <strong>{{ $errors->first('gender') }}</strong>
                </span>
            @endif
            </div>
        </div>
        <div class="form-group row">
            <label for="refereeSurname" class="col-sm-4 text-md-right" style="color:#000000">Password</label>
            <div class="col-md-6">
                <input type="password" class="form-control {{ $errors->has('password') ? ' is-invalid' : '' }}" maxlength="100" name="password" value="{{ old('password') }}" id="refereeEmail" placeholder="Password"/>
                @if ($errors->has('password'))
                    <span class="invalid-feedback">
                        <strong>{{ $errors->first('password') }}</strong>
                    </span>
                @endif
            </div>
        </div>
        <div class="form-group row">
            <label for="refereeSurname" class="col-sm-4 text-md-right" style="color:#000000">Confirm Password</label>
            <div class="col-md-6">
                <input type="password" class="form-control {{ $errors->has('password_confirmation') ? ' is-invalid' : '' }}" maxlength="100" name="password_confirmation" value="{{ old('password_confirmation') }}" id="refereeEmail" placeholder="Confirm password"/>
                @if ($errors->has('email'))
                    <span class="invalid-feedback">
                        <strong>{{ $errors->first('password confirm') }}</strong>
                    </span>
                @endif
            </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-warning">Add Referee</button>
      </div>
    </div>
  </div>
  </form>

</div>

@endsection

@section('scripts')
@if ($errors->any())
	<script type="text/javascript">
    $(document).ready(function() {
        $('#AddNewReferee').modal('show');
        $('#AddNewReferee').on('shown.bs.modal', function () {
        $('#newRefereeButton').trigger('focus');
        });

    });
    </script>

@endif
@endsection
