@extends('layouts.app')

@section('content')


<script type="application/javascript"> 
        // upload name for single file
        function uploadName(input_id , textarea_id){
            var input = document.getElementById(input_id);
            var textarea = document.getElementById(textarea_id);
            var file_name = input.files[0].name;
            textarea.value = file_name;
        }
        // Check column of checkboxes
        function selectAll(val){                      
            checkboxes = document.getElementsByClassName(val);
            checkboxes[0].checked = true;
            checkboxes[1].checked = true;
            checkboxes[2].checked = true;
            checkboxes[3].checked = true;
            
        }
        // Update permissions
        function updatePermission(name,functional_units){
            functional_units.forEach(unit => {
                if(unit.name === name){
                    console.log(unit.permission);
                    for(var i = 0; i < unit.permission.length; i++){
                        
                        var checkboxes = document.getElementsByName('permission[' + i + ']');
                        if(unit.permission[i] === '1'){
                            checkboxes[0].checked = true;
                            console.log(true);
                        }else{
                            checkboxes[0].checked = false;
                            console.log(false);
                        }
                        
                    }
                    return;
                }
            });
        }

    </script>

<div class="container-fluid">
    <div class="row">

        @include('include.account_sidebar')

    
        <div class="col-md-9  main">
                <ol class="breadcrumb">
                        <li><a href="/">Home</a></li>
                        <li><a href="/sysmg/accounts">User Account Management</a></li>
                        <li class="active"> Edit Manager </li>
                </ol>

                <div style="float:right">
                    <a class="btn btn-primary btn-md"  href="/sysmg/accounts">BACK</a>
                </div>

                <h1 class="page-header">Edit {{ $user->name}}</h1>

                {!! Form::open(['action' => ['UsersController@update', $user->id], 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}

                <div class="row">
                        <div class="col-md-9">

                            <div class="form-group">
                                {{Form::label('username', 'Username')}}
                                {{Form::text('username', $user->username, ['class' => 'form-control'])}}
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
                                    {{Form::label('status', 'Account Status')}}
                                    {{Form::select('isActivated', array('1' => 'Activated', '0' => 'Deactivated'), $user->isActivated, ['class' => 'form-control'])}}
                            </div>

                            <div class="form-group">
                                {{Form::label('profile_photo', 'Profile Photo(.jpg)')}}
                                {{Form::file('profile_photo', [ 'class' => 'hidden', 'id' => 'profile_photo' ,'onChange' => 'uploadName(this.id, \'profile_photo_text\')'])}}
                                <div class="row">
                                    <div class="col-md-10">
                                        {{Form::text('profile_photo_text', $user->profile_photo, ['class' => 'form-control', 'id' => 'profile_photo_text', 'disabled'])}}
                                    </div>
                                    <div class="col-md-2">
                                        {{Form::label('profile_photo', 'Upload File', ['class' => 'file-input', 'for' => 'profile_photo'])}}
                                    </div> 
                                </div>
                            </div>

                        </div>
                        <div class="col-md-3">
                            <img id="profile-photo" src="/storage/profile_photos/{{$user->profile_photo}}">
                        </div>
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
                                                        <td style="text-align:center" > Check All</td>
                                                        <td style="text-align:center" > <button type="button" class="btn btn-sm btn-success" onclick="selectAll('view')"> <span class="glyphicon glyphicon-ok">  </button></td>
                                                        <td style="text-align:center" > <button type="button" class="btn btn-sm btn-success" onclick="selectAll('add')"> <span class="glyphicon glyphicon-ok"> </button></td>
                                                        <td style="text-align:center" > <button type="button" class="btn btn-sm btn-success" onclick="selectAll('edit')"> <span class="glyphicon glyphicon-ok"> </button></td>
                                                        <td style="text-align:center" > <button type="button" class="btn btn-sm btn-success" onclick="selectAll('generate')"> <span class="glyphicon glyphicon-ok"> </button></td>
                                        
                                                    </tr>
                                                    <tr>
                                                        <td style="text-align:center">CSM</td>
                                                        <td style="text-align:center">{{Form::checkbox('permission[0]', 0, ($user->permission[0] === '1') ? true : false ,['class' => 'view'])}}</td>
                                                        <td style="text-align:center">{{Form::checkbox('permission[1]', 1, ($user->permission[1] === '1') ? true : false ,['class' => 'add'])}}</td>
                                                        <td style="text-align:center">{{Form::checkbox('permission[2]', 2, ($user->permission[2] === '1') ? true : false ,['class' => 'edit'])}}</td>
                                                        <td style="text-align:center">{{Form::checkbox('permission[3]', 3, ($user->permission[3] === '1') ? true : false ,['class' => 'generate'])}}</td>
                                                        
                                                    </tr>
                                                    <tr>
                                                        <td style="text-align:center">QOA</td>
                                                        <td style="text-align:center">{{Form::checkbox('permission[4]', 4, ($user->permission[4] === '1') ? true : false ,['class' => 'view'])}}</td>
                                                        <td style="text-align:center">{{Form::checkbox('permission[5]', 5, ($user->permission[5] === '1') ? true : false ,['class' => 'add'])}}</td>
                                                        <td style="text-align:center">{{Form::checkbox('permission[6]', 6, ($user->permission[6] === '1') ? true : false ,['class' => 'edit'])}}</td>
                                                        <td style="text-align:center">{{Form::checkbox('permission[7]', 7, ($user->permission[7] === '1') ? true : false ,['class' => 'generate'])}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td style="text-align:center">MR</td>
                                                        <td style="text-align:center">{{Form::checkbox('permission[8]', 8, ($user->permission[8] === '1')   ? true : false ,['class' => 'view'])}}</td>
                                                        <td style="text-align:center">{{Form::checkbox('permission[9]', 9, ($user->permission[9] === '1')   ? true : false ,['class' => 'add'])}}</td>
                                                        <td style="text-align:center">{{Form::checkbox('permission[10]', 10, ($user->permission[10] === '1') ? true : false ,['class' => 'edit'])}}</td>
                                                        <td style="text-align:center">{{Form::checkbox('permission[11]', 11, ($user->permission[11] === '1') ? true : false ,['class' => 'generate'])}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td style="text-align:center">QMSD</td>
                                                        <td style="text-align:center">{{Form::checkbox('permission[12]', 12, ($user->permission[12] === '1') ? true : false ,['class' => 'view'])}}</td>
                                                        <td style="text-align:center">{{Form::checkbox('permission[13]', 13, ($user->permission[13] === '1') ? true : false ,['class' => 'add'])}}</td>
                                                        <td style="text-align:center">{{Form::checkbox('permission[14]', 14, ($user->permission[14] === '1') ? true : false ,['class' => 'edit'])}}</td>
                                                        <td style="text-align:center">{{Form::checkbox('permission[15]', 15, ($user->permission[15] === '1') ? true : false ,['class' => 'generate'])}}</td>
                                                
                                                    </tr>
                                                </tbody>
                                            </table>
                                </div>
    
                            </div>
                            
                        </div>
                    {{Form::hidden('_method','PUT')}}
                    {{Form::submit('EDIT', ['class'=>'btn btn-primary submit-btn'])}}

                {!! Form::close() !!}
        </div>
    
    </div>
</div>

@endsection