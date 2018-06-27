@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="row">
        <div class="col-12">
        <h1>Superuser Dashboard</h1>

            <ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="nav-link" href="/superuser/dashboard">Home</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/superuser/dashboard/create-vouchers">Create Voucher</a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" href="/superuser/dashboard/download-vouchers">Download Vouchers</a>
            </li>
            <!-- <li class="nav-item">
                <a class="nav-link disabled" href="#">Disabled</a>
            </li> -->
            </ul> 
        </div>
    </div>    
    <div class="row">
        <div class="col-12">
        
        <table class="table table-dark">
            <thead>
                <tr>
                <th scope="col">#</th>
                <th scope="col">Venue</th>
                <th scope="col">Download</th>
                <th scope="col">Vouchers Available</th>
                </tr>
            </thead>
            <tbody>
            @foreach($venues as $key=>$venue)
                <tr>
                <th scope="row">{{$key + 1}}</th>
                <td>{{$venue->name}}</td>
                <td><a href="/vouchers-csv/{{$venue->id}}"><i class="fas fa-download"></i></a></td>
                <td>{{\App\Voucher::where('venue_id', $venue->id)->get()->count()}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>            

        </div>
    </div>
</div>

@endsection