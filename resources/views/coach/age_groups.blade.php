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
        <h2 class="pt-2"> Venue Age Groups </h2><hr />
        <div class="row d-flex d-sm-flex justify-content-end p-2">
            <button class="btn btn-large btn-danger" id="newTeamButton" data-toggle="modal" data-target="#AddNewTeam"><i class="far fa-plus-square"></i>&nbsp;&nbsp;ADD AGE GROUP</button>
        </div>
        <br />
        @if($age_groups)
        <table class="table table-striped table-dark table-responsive-sm table-sm text-center">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Team Name</th>
                    <th scope="col">Status</th>
                    <th scope="col">Edit</th>
                    <th scope="col">Delete</th>
                </tr>
            </thead>
            <tbody>
                @foreach($age_groups as $key=>$age_group)
                <tr>
                    <th scope="row">{{$key + 1}}</th>
                    <td>{{$age_group->name}}</td>
                    <td>
                    <form name="form_active{{$key + 1}}" action="/user-profile/coach/age-group/set-active" method="post">
                        @csrf
                        <input type="text" name="age_group_id" value="{{$age_group->id}}" hidden readonly />
                        
                        <button type="submit" name="submit_{{$key + 1}}" class="btn" style="background-color: transparent;">
                            @if($age_group->active_status == 'active')
                            <i class="fas fa-toggle-on"></i>
                            @else
                            <i class="fas fa-toggle-off"></i>
                            @endif
                        </button>
                    </form>  
                    </td>
                    <td><a class="btn" href="#" data-toggle="modal" data-target="#age_group_{{$age_group->id}}"><i class="far fa-edit"></i></a></td>
                    <td>
                        <form name="form_{{$key + 1}}" action="/user-profile/coach/age-group/delete" method="post" onsubmit='return confirm("Are you sure you want to delete {{utf8_encode($age_group->name)}}?");'>
                            @csrf
                            <input type="text" name="age_group_id" value="{{$age_group->id}}" hidden readonly />
                            
                            <button type="submit" name="submit_{{$key + 1}}" class="btn" style="background-color: transparent;"><i class="fas fa-trash"></i></button>
                        </form>  
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <br />
        <?php $current_page = $age_groups->currentPage(); 
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
                    @if($page_number < $age_groups->lastPage())
                        @if($page_number == $age_groups->currentPage())
                        <li class="page-item disabled"><a class="page-link pagination-number" href="?page={{$page_number}}" style="background-color: #181818 !important;">{{$page_number}}</a></li>
                        @else
                        <li class="page-item"><a class="page-link pagination-number" href="?page={{$page_number}}">{{$page_number}}</a></li>
                        @endif
                    @endif
                @endforeach
                @if(($age_groups->lastPage() - $current_page) > 1)
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
                @if(($age_groups->lastPage() - $current_page) > 3)
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

        <p>There are no age groups added to this venue yet</p>

        @endif
    </div>
    
    @include('includes.user_side_panel')<!-- side panel -->
    </div>
</div>

@endsection

@section('modal')

<div class="modal fade" id="AddNewTeam" tabindex="-1" role="dialog" aria-labelledby="AddNewTeamLabel" aria-hidden="true">
<form action="/user-profile/coach/add-age-group" method="post">
{{ csrf_field() }}
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="AddNewTeamLabel">Add New Age Group</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">        
        <div class="form-group row">
            <label for="ageGroupName" class="col-sm-2 text-md-right" style="color:#000000">Name</label>
            <div class="col-md-8">
                <input type="text" class="form-control {{ $errors->has('name') ? ' is-invalid' : '' }}" aria-describedby="AddTeamblock" pattern="^([a-zA-Z0-9_\s\-]*)$" maxlength="100" minlength="1" name="name" value="{{ old('name') }}"id="ageGroupName" placeholder="Team Name" autofocus/>
                <small id="AddTeamblock" class="form-text text-muted">
                    <strong>Please dont use special characters ex. * / ' " . ; ( ) etc.</strong>
                </small>
                @if ($errors->has('name'))
                    <span class="invalid-feedback">
                        <strong>{{ $errors->first('name') }}</strong>
                    </span>
                @endif
            </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-warning">Add Age Group</button>
      </div>
    </div>
  </div>
  </form>

</div>

<!-- modals for editing name of age group -->

@foreach($age_groups as $age_group)

<div class="modal fade" id="age_group_{{$age_group->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <form action="/user-profile/coach/age-group-edit" method="post" name="modal_form_{{$age_group->id}}">
  {{ csrf_field() }}

  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="age_group_{{$age_group->id}}">Edit - {{$age_group->name}}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <div class="form-group row">
        <label class="col-sm-2 text-md-right" style="color:#000000;">Name</label>
        <div class="form-group col-md-8">
        <input type="text" class="form-control {{ $errors->has('age_group_name') ? ' is-invalid' : '' }}" aria-describedby="EditTeamBlock_{{$key}}" pattern="^([a-zA-Z0-9_\s\-]*)$" maxlength="100" minlength="1" name="age_group_name" value="{{ $age_group->name }}" id="age_groupName" placeholder="Age Group Name" autofocus/>
        <small id="EditTeamBlock_{{$key}}" class="form-text text-muted">
            <strong>Please dont use special characters ex. * / ' " . ; ( ) etc.</strong>
        </small>
        @if ($errors->has('age_group_name'))
            <span class="invalid-feedback">
                <strong>{{ $errors->first('age_group_name') }}</strong>
            </span>
        @endif
        <input type="hidden" value="{{$age_group->id}}" name="age_group_id" />
      </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
  </form>
</div>

@endforeach

@endsection

@section('scripts')
@if ($errors->has('name'))
	<script type="text/javascript">
    $(document).ready(function() {
        $('#AddNewTeam').modal('show');
        $('#AddNewTeam').on('shown.bs.modal', function () {
        $('#newTeamButton').trigger('focus');
        });

    });
    </script>

@endif
@endsection