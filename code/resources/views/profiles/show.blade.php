@extends('layouts.app')

@section('content')



<div class="container-fluid">
    <div class="row">
        <div class="col-md-1"></div>

        <div class="col-md-10 main">
                    <ol class="breadcrumb">
                        <li><a href="/">Home</a></li>
                        <li class="active">My Profile </li>
                    </ol>
                        
                    <div style="float:right">
                        <a class="btn btn-success btn-md"  href="/profiles/{{$user->id}}/edit">EDIT</a>
                        <a class="btn btn-success btn-md"  href="/profiles/{{$user->id}}/change_pass">CHANGE PASSWORD</a>
                    </div>
                        
                    <h1 class="page-header"> {{$user->name}} </h1>


                    <div class="row">
                        <div class="col-md-9">
                            <h5> <strong>Username</strong> </h5>
                            <li class="list-group-item">{{$user->username}}</li>
                            <h5> <strong>Name</strong> </h5>
                            <li class="list-group-item">{{$user->name}}</li>
                            <h5> <strong>Position</strong> </h5>
                            <li class="list-group-item">{{$user->position}}</li>
                            <h5> <strong>Functional Unit</strong> </h5>
                            <li class="list-group-item">{{$user->functional_unit}}</li>
                            <h5> <strong>Role</strong> </h5>

                            @if ($user->role === 'admin')
                                <li class="list-group-item">Administrator</li>
                            @endif

                            @if ($user->role === 'manager')
                                <li class="list-group-item">Manager</li>
                            @endif

                            @if ($user->role === 'employee')
                                <li class="list-group-item">Employee</li>    
                            @endif

                            <h5> <strong>Status</strong> </h5>
                            @if ($user->isActivated === 1)
                                <li class="list-group-item"> Activated Account </li>
                            @else
                                <li class="list-group-item"> Deactivated Account </li> 
                            @endif
                            
                            
                        </div>
                        <div class="col-md-3">
                            <img id="profile-photo"  src="/storage/profile_photos/{{$user->profile_photo}}">
                        </div>
                    </div>

                    
                    <div class="table-responsive">
                        <table class="table table-striped">
                            
                            <thead>
                                    <h3> PRIVELEGES ON MODULE </h3>
                                <tr>
                                    <th style="text-align:center"></th>
                                    <th style="text-align:center">View</th>
                                    <th style="text-align:center">Add</th>
                                    <th style="text-align:center">Edit</th>
                                    {{-- <th style="text-align:center">Generate</th> --}}
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td style="text-align:center">CSM</td>
                                    @for ($i = 0; $i <= 2; $i++)
                                        @if ($user->permission[$i] === '1')
                                            <td style="text-align:center"><span class="glyphicon glyphicon-ok" style="color:green"> </td>
                                        @else
                                            <td style="text-align:center"> <span class="glyphicon glyphicon-remove" style="color:red" ></span> </td>
                                        @endif
                                    @endfor
                                </tr>
                                <tr>
                                    <td style="text-align:center">QOA</td>
                                    @for ($i = 3; $i <= 5; $i++)
                                        @if ($user->permission[$i] === '1')
                                            <td style="text-align:center"><span class="glyphicon glyphicon-ok" style="color:green"> </td>
                                        @else
                                            <td style="text-align:center"> <span class="glyphicon glyphicon-remove" style="color:red"></span> </td>
                                        @endif
                                    @endfor
                                </tr>
                                <tr>
                                    <td style="text-align:center">MR</td>
                                    @for ($i = 6; $i <= 8; $i++)
                                        @if ($user->permission[$i] === '1')
                                            <td style="text-align:center"><span class="glyphicon glyphicon-ok" style="color:green"> </td>
                                        @else
                                            <td style="text-align:center"> <span class="glyphicon glyphicon-remove" style="color:red" ></span> </td>
                                        @endif
                                    @endfor
                                </tr>
                                <tr>
                                    <td style="text-align:center">QMSD</td>
                                    @for ($i = 9; $i <= 11; $i++)
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


        <div class="col-md-1"></div>

    </div>
    
    

</div>

@endsection