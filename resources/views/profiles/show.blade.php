@extends('layouts.app')

@section('content')

<div class="container">
    <ol class="breadcrumb">
        <li><a href="/">Home</a></li>
        <li class="active">My Profile </li>
    </ol>
        
    <div style="float:right">
        <a class="btn btn-success btn-md"  href="/profiles/{{$user->id}}/edit">EDIT</a>
        <a class="btn btn-success btn-md"  href="/profiles/{{$user->id}}/change_pass">CHANGE PASSWORD</a>
    </div>
        
    <h1 class="page-header"> {{$user->name}} </h1>
    <ul class="list-group">
        <img style="width:150px;" src="/storage/profile_photos/{{$user->profile_photo}}">
        
        @if ($user->isActivated === 1)
            <h5>Status</h5>
            <li class="list-group-item"> Activated Account </li>
        @else
            <h5>Status</h5>
            <li class="list-group-item"> Deactivated Account </li> 
        @endif
        <h5>Name</h5>
        <li class="list-group-item">{{$user->name}}</li>
        <h5>Position</h5>
        <li class="list-group-item">{{$user->position}}</li>
        <h5>Functional Unit</h5>
        <li class="list-group-item">{{$user->functional_unit}}</li>
        <h5>Role</h5>
        <li class="list-group-item">{{$user->role}}</li>
        <h5>Username</h5>
        <li class="list-group-item">{{$user->username}}</li>

    </ul>
    
    <div class="table-responsive">
        <table class="table table-striped">
            
            <thead>
                    <h3>PRIVELEGES ON MODULE</h3>
                <tr>
                    <th style="text-align:center"></th>
                    <th style="text-align:center">View</th>
                    <th style="text-align:center">Add</th>
                    <th style="text-align:center">Edit</th>
                    <th style="text-align:center">Generate</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="text-align:center">CSM</td>
                    @for ($i = 0; $i <= 3; $i++)
                        @if ($user->permission[$i] === '1')
                            <td style="text-align:center"><span class="glyphicon glyphicon-ok" style="color:green"> </td>
                        @else
                            <td style="text-align:center"> <span class="glyphicon glyphicon-remove" style="color:red" ></span> </td>
                        @endif
                    @endfor
                </tr>
                <tr>
                    <td style="text-align:center">QOA</td>
                    @for ($i = 4; $i <= 7; $i++)
                        @if ($user->permission[$i] === '1')
                            <td style="text-align:center"><span class="glyphicon glyphicon-ok" style="color:green"> </td>
                        @else
                            <td style="text-align:center"> <span class="glyphicon glyphicon-remove" style="color:red"></span> </td>
                        @endif
                    @endfor
                </tr>
                <tr>
                    <td style="text-align:center">MR</td>
                    @for ($i = 8; $i <= 11; $i++)
                        @if ($user->permission[$i] === '1')
                            <td style="text-align:center"><span class="glyphicon glyphicon-ok" style="color:green"> </td>
                        @else
                            <td style="text-align:center"> <span class="glyphicon glyphicon-remove" style="color:red" ></span> </td>
                        @endif
                    @endfor
                </tr>
                <tr>
                    <td style="text-align:center">QMSD</td>
                    @for ($i = 12; $i <= 15; $i++)
                        @if ($user->permission[$i] === '1')
                            <td style="text-align:center"><span class="glyphicon glyphicon-ok" style="color:green"> </td>
                        @else
                            <td style="text-align:center"> <span class="glyphicon glyphicon-remove" style="color:red"></span> </td>
                        @endif
                    @endfor
                </tr>
                
            </tbody>
        </table>
    </div>

</div>

@endsection