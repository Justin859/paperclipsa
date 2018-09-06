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
        <h2 class="pt-2"> Venue Admins </h2><hr />
        <div class="row d-flex d-sm-flex justify-content-end p-2">
            <button class="btn btn-large btn-danger" id="newAdminButton" data-toggle="modal" data-target="#AddNewAdmin"><i class="far fa-plus-square"></i>&nbsp;&nbsp;ADD ADMIN</button>
        </div>
        <br />
        @if($admin_users)
        <table class="table table-striped table-dark table-responsive-sm table-sm text-center">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Venue</th>
                    <th scope="col">Name</th>
                    <th scope="col">Status</th>
                    <th scope="col">Edit</th>
                    <th scope="col">Delete</th>
                </tr>
            </thead>
            <tbody>
                @foreach($admin_users as $key=>$admin)
                @if(\App\Venue::find(\App\Admin::where('user_id', $admin->id)->first()->venue_id))
                <tr>
                    <th scope="row">{{$key + 1}}</th>
                    <td class="float-left">
                    <div class="media">
                        <img class="mr-3" height="64" width="64" style="border: 1px solid;" src="{{asset('/storage/venues/logos/'. \App\Venue::find(\App\Admin::where('user_id', $admin->id)->first()->venue_id)->logo_img)}}" alt="Venue Logo">
                        <div class="media-body">
                        {{\App\Venue::find(\App\Admin::where('user_id', $admin->id)->first()->venue_id)->name}}
                        </div>
                    </div>
                    </td>
                    <td>{{$admin->firstname}} {{$admin->surname}}</td>
                    <td>
                    <form name="form_active{{$key + 1}}" action="/user-profile/superuser/admin/set-active" method="post">
                        @csrf
                        <input type="text" name="admin_id" value="{{$admin->id}}" hidden readonly />
                        
                        <button type="submit" name="submit_{{$key + 1}}" class="btn" style="background-color: transparent;">
                            @if($admin->active_status == 'active')
                            <i class="fas fa-toggle-on"></i>
                            @else
                            <i class="fas fa-toggle-off"></i>
                            @endif
                        </button>
                    </form>  
                    </td>
                    <td><a class="btn" href="/user-profile/superuser/admin/edit/{{$admin->id}}/{{$admin->firstname}}"><i class="far fa-edit"></i></a></td>
                    <td>
                        <form name="form_{{$key + 1}}" action="/user-profile/superuser/admin/delete" method="post" onsubmit="return confirm('Are you sure you want to delete {{$admin->name}}?');">
                            @csrf
                            <input type="hidden" name="admin_user_id" value="{{$admin->id}}" readonly />
                            <button type="submit" name="submit_{{$key + 1}}" class="btn" style="background-color: transparent;"><i class="fas fa-trash"></i></button>
                        </form>  
                    </td>
                </tr>
                @endif
                @endforeach
            </tbody>
        </table>
        <br />
        <?php $current_page = $admin_users->currentPage(); 
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
                    @if($page_number < $admin_users->lastPage())
                        @if($page_number == $admin_users->currentPage())
                        <li class="page-item disabled"><a class="page-link pagination-number" href="?page={{$page_number}}" style="background-color: #181818 !important;">{{$page_number}}</a></li>
                        @else
                        <li class="page-item"><a class="page-link pagination-number" href="?page={{$page_number}}">{{$page_number}}</a></li>
                        @endif
                    @endif
                @endforeach
                @if(($admin_users->lastPage() - $current_page) > 1)
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
                @if(($admin_users->lastPage() - $current_page) > 3)
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

        <p>There are no admins added to this venue yet</p>

        @endif
    </div>
    
    @include('includes.user_side_panel')<!-- side panel -->
    </div>
</div>

@endsection

@section('modal')

<div class="modal fade" id="AddNewAdmin" tabindex="-1" role="dialog" aria-labelledby="AddNewAdminLabel" aria-hidden="true">
<form action="/user-profile/superuser/admin/save" method="post">
{{ csrf_field() }}
<!-- Add New Admin -->
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="AddNewAdminLabel">Add New Admin</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">        
        <div class="form-group row">
            <label for="adminName" class="col-sm-4 text-md-right" style="color:#000000">First Name</label>
            <div class="col-md-6">
                <input type="text" class="form-control {{ $errors->has('firstname') ? ' is-invalid' : '' }}" maxlength="100" minlength="1" name="firstname" value="{{ old('firstname') }}"id="adminName" placeholder="First name" autofocus/>
                @if ($errors->has('firstname'))
                    <span class="invalid-feedback">
                        <strong>{{ $errors->first('firstname') }}</strong>
                    </span>
                @endif
            </div>
        </div>
        <div class="form-group row">
            <label for="adminSurname" class="col-sm-4 text-md-right" style="color:#000000">Last Name</label>
            <div class="col-md-6">
                <input type="text" class="form-control {{ $errors->has('surname') ? ' is-invalid' : '' }}" maxlength="100" name="surname" value="{{ old('surname') }}"id="adminSurname" placeholder="Last name" />
                @if ($errors->has('surname'))
                    <span class="invalid-feedback">
                        <strong>{{ $errors->first('surname') }}</strong>
                    </span>
                @endif
            </div>
        </div>
        <div class="form-group row">
            <label for="adminEmail" class="col-sm-4 text-md-right" style="color:#000000">Email Address</label>
            <div class="col-md-6">
                <input type="email" class="form-control {{ $errors->has('email') ? ' is-invalid' : '' }}" maxlength="100" name="email" value="{{ old('email') }}" id="adminEmail" placeholder="Email address" />
                @if ($errors->has('email'))
                    <span class="invalid-feedback">
                        <strong>{{ $errors->first('email') }}</strong>
                    </span>
                @endif
            </div>
        </div>
        <div class="form-group row">
            <label for="adminGender" class="col-sm-4 text-md-right" style="color:#000000">Gender</label>
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
            <label for="adminVenue" class="col-sm-4 text-md-right" style="color:#000000">Venue</label>
            <div class="col-md-6">
            <select type="text" class="form-control" name="venue_id" id="validationTooltip08">
                @foreach($indoor_soccer_venues as $key=>$indoor_soccer_venue)
                    @if($key == 1)
                        <option value="{{$indoor_soccer_venue->id}}" selected="selected">{{$indoor_soccer_venue->name}}</option>
                    @else
                        <option value="{{$indoor_soccer_venue->id}}" selected="selected">{{$indoor_soccer_venue->name}}</option>
                    @endif
                @endforeach
            </select>
            @if ($errors->has('venue_id'))
                <span class="invalid-feedback">
                    <strong>{{ $errors->first('gender') }}</strong>
                </span>
            @endif
            </div>
        </div>
        <div class="form-group row">
            <label for="adminSurname" class="col-sm-4 text-md-right" style="color:#000000">Password</label>
            <div class="col-md-6">
                <input type="password" class="form-control {{ $errors->has('password') ? ' is-invalid' : '' }}" maxlength="100" name="password" value="{{ old('password') }}" id="adminEmail" placeholder="Password"/>
                @if ($errors->has('password'))
                    <span class="invalid-feedback">
                        <strong>{{ $errors->first('password') }}</strong>
                    </span>
                @endif
            </div>
        </div>
        <div class="form-group row">
            <label for="adminSurname" class="col-sm-4 text-md-right" style="color:#000000">Confirm Password</label>
            <div class="col-md-6">
                <input type="password" class="form-control {{ $errors->has('password_confirmation') ? ' is-invalid' : '' }}" maxlength="100" name="password_confirmation" value="{{ old('password_confirmation') }}" id="adminEmail" placeholder="Confirm password"/>
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
        <button type="submit" class="btn btn-warning">Add Admin</button>
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
        $('#AddNewAdmin').modal('show');
        $('#AddNewAdmin').on('shown.bs.modal', function () {
        $('#newAdminButton').trigger('focus');
        });

    });
    </script>

@endif
@endsection
