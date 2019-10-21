@extends('layouts.app')

@section('content')



<div class="container-fluid">
    <div class="row">

        @include('include.sidebar')

        <div class="col-md-2" style="float:right">
            <a class="btn btn-primary btn-md"  href="/sysmg/accounts">BACK</a>
        </div>

        <div class="col-md-8  main">
                <h1 class="page-header">USER ACCOUNTS</h1>
                <h3>EDIT ADMINISTRATOR</h3>

                <img style="width:150px;" src="/storage/profile_photos/{{$user->profile_photo}}">

                {!! Form::open(['action' => ['UsersController@update', $user->id], 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}

                    <div class="form-group">
                        {{Form::label('profile_photo', 'Profile Photo (.jpg)')}}
                        {{Form::file('profile_photo')}}
                    </div>
                    <div class="form-group">
                            {{Form::label('status', 'Account Status')}}
                            {{Form::select('isActivated', array('1' => 'Activated', '0' => 'Deactivated'), $user->isActivated, ['class' => 'form-control'])}}
                    </div>

                    <div class="form-group">
                        {{Form::label('name', 'Name')}}
                        {{Form::text('name', $user->name, ['class' => 'form-control'])}}
                    </div> 

                   <div class="form-group">
                        {{Form::label('position', 'Position')}}
                        {{Form::text('position', $user->position, ['class' => 'form-control'])}}
                    </div>

                    <div class="form-group">
                        {{Form::label('functional_unit', 'Functional Unit')}}
                        {{Form::select('functional_unit', $data, $user->functional_unit, ['class' => 'form-control'])}}
                    </div> 

                    <div class="form-group">
                            {{Form::label('role', 'Role')}}
                            {{Form::select('role', array('admin' => 'Administrator', 'manager' => 'Manager','employee' => 'Employee'), $user->role, ['class' => 'form-control'])}}
                    </div> 

                    <div class="form-group">
                        {{Form::label('username', 'Username')}}
                        {{Form::text('username', $user->username, ['class' => 'form-control'])}}
                    </div>


                    <div class="container-fluid">
                        
                        <div class="row">
                            <h3>PRIVELEGES ON MODULE </h3>
                            <div class="table-responsive">
                                    <table class="table table-striped">

                                            <thead>
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
                                                        <td style="text-align:center"><span class="glyphicon glyphicon-ok" style="color:green"> </td>
                                                    @endfor
                                                </tr>
                                                <tr>
                                                    <td style="text-align:center">QOA</td>
                                                    @for ($i = 4; $i <= 7; $i++)
                                                        <td style="text-align:center"><span class="glyphicon glyphicon-ok" style="color:green"> </td>
                                                    @endfor
                                                </tr>
                                                <tr>
                                                    <td style="text-align:center">MR</td>
                                                    @for ($i = 8; $i <= 11; $i++)
                                                        <td style="text-align:center"><span class="glyphicon glyphicon-ok" style="color:green"> </td>
                                                    @endfor
                                                </tr>
                                                <tr>
                                                    <td style="text-align:center">QMSD</td>
                                                    @for ($i = 12; $i <= 15; $i++)
                                                        <td style="text-align:center"><span class="glyphicon glyphicon-ok" style="color:green"> </td>
                                                    @endfor
                                                </tr>
                                            </tbody>
                                        </table>
                            </div>

                        </div>
                        
                    </div>
                    {{Form::hidden('_method','PUT')}}
                    {{Form::submit('Submit', ['class'=>'btn btn-primary'])}}

                {!! Form::close() !!}
        </div>
    
    </div>
</div>

@endsection