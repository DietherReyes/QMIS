@extends('layouts.app')

@section('content')

<div class="container-fluid">
    <div class="row">

        @include('include.account_sidebar')

       

        <div class="col-md-9  main">
                <ol class="breadcrumb">
                        <li><a href="/">Home</a></li>
                        <li><a href="/sysmg/accounts">User Account Management</a></li>
                        <li class="active"> Edit Employee </li>
                </ol>

                <div style="float:right">
                    <a class="btn btn-primary btn-md"  href="/sysmg/accounts">BACK</a>
                </div>

                <h1 class="page-header">Edit {{ $user->name}}</h1>

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
                                                </tr>
                                            </thead>
                                            
                                            <tbody>

                                                {{-- 
                                                    Check column of checkboxes
                                                --}}
                                                <script language="JavaScript">
                                                        function selectAll(val){                      
                                                            checkboxes = document.getElementsByClassName(val);
                                                            checkboxes[0].checked = true;
                                                            checkboxes[1].checked = true;
                                                            checkboxes[2].checked = true;
                                                            checkboxes[3].checked = true;
                                                            
                                                        }
                                                </script>
                                    
                                                <tr>
                                                    <td style="text-align:center" > Check All</td>
                                                    <td style="text-align:center" > <button type="button" class="btn btn-sm btn-success" onclick="selectAll('view')"> <span class="glyphicon glyphicon-ok">  </button></td>
                                                    
                                                </tr>
                                                <tr>
                                                    <td style="text-align:center">CSM</td>
                                                    <td style="text-align:center">{{Form::checkbox('permission[]', 0, ($user->permission[0] === '1') ? true : false ,['class' => 'view'])}}</td>
                                                   
                                                </tr>
                                                <tr>
                                                    <td style="text-align:center">QOA</td>
                                                    <td style="text-align:center">{{Form::checkbox('permission[]', 4, ($user->permission[4] === '1') ? true : false ,['class' => 'view'])}}</td>
                                                    
                                                </tr>
                                                <tr>
                                                    <td style="text-align:center">MR</td>
                                                    <td style="text-align:center">{{Form::checkbox('permission[]', 8, ($user->permission[8] === '1') ? true : false ,['class' => 'view'])}}</td>
                                                    
                                                </tr>
                                                <tr>
                                                    <td style="text-align:center">QMSD</td>
                                                    <td style="text-align:center">{{Form::checkbox('permission[]', 12, ($user->permission[12] === '1') ? true : false ,['class' => 'view'])}}</td>
                                                    
                                            
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