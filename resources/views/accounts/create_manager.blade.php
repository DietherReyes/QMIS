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
                        <li class="active"> Add Manager </li>
                </ol>

                <div style="float:right">
                    <a class="btn btn-primary btn-md"  href="/sysmg/accounts">BACK</a>
                </div>
                <h1 class="page-header">Add Manager</h1>
                

                {!! Form::open(['action' => 'UsersController@store_manager', 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}

                <div class="row">
                        <div class="col-md-12">

                            <div class="form-group">
                                {{Form::label('name', 'Name')}}
                                {{Form::text('name', '', ['class' => 'form-control'])}}
                            </div> 
        
                            <div class="form-group">
                                {{Form::label('position', 'Position')}}
                                {{Form::text('position','', ['class' => 'form-control'])}}
                            </div>

                          
                            <div class="form-group">
                                {{Form::label('functional_unit', 'Functional Unit')}}
                                {{Form::select('functional_unit', $data, '', ['class' => 'form-control'])}}
                            </div> 
        
                            <div class="form-group">
                                    {{Form::label('role', 'Role')}}
                                    {{Form::select('role', array('admin' => 'Administrator', 'manager' => 'Manager','employee' => 'Employee'), '', ['class' => 'form-control'])}}
                            </div> 
                            <div class="form-group">
                                    {{Form::label('status', 'Account Status')}}
                                    {{Form::select('isActivated', array('1' => 'Activated', '0' => 'Deactivated'), '', ['class' => 'form-control'])}}
                            </div>


                            <div class="form-group">
                                {{Form::label('username', 'Username')}}
                                {{Form::text('username', '', ['class' => 'form-control'])}}
                            </div>

                            <div class="form-group">
                                    {{Form::label('password','Password')}}
                                    <input type="password" class="form-control" name="password">
                            </div>
        
                            <div class="form-group">
                                {{Form::label('password_confirmation','Confirm Password')}}
                                <input type="password" class="form-control" name="password_confirmation">
                            </div>

                            <div class="form-group">
                                {{Form::label('profile_photo', 'Profile Photo(.jpg)')}}
                                {{Form::file('profile_photo', [ 'class' => 'hidden', 'id' => 'profile_photo' ,'onChange' => 'uploadName(this.id, \'profile_photo_text\')'])}}
                                <div class="row">
                                    <div class="col-md-10">
                                        {{Form::text('profile_photo_text', '', ['class' => 'form-control', 'id' => 'profile_photo_text', 'disabled'])}}
                                    </div>
                                    <div class="col-md-2">
                                        {{Form::label('profile_photo', 'Upload File', ['class' => 'file-input', 'for' => 'profile_photo'])}}
                                    </div> 
                                </div>
                            </div>

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
                                                        {{-- <th style="text-align:center">Generate</th> --}}
                                                    </tr>
                                                </thead>
                                                
                                                <tbody>
                                                        
                                                    
                                        
                                                    <tr>
                                                        <td style="text-align:center" > Check All</td>
                                                        <td style="text-align:center" > <button type="button" class="btn btn-sm btn-success" onclick="selectAll('view')"> <span class="glyphicon glyphicon-ok">  </button></td>
                                                        <td style="text-align:center" > <button type="button" class="btn btn-sm btn-success" onclick="selectAll('add')"> <span class="glyphicon glyphicon-ok"> </button></td>
                                                        <td style="text-align:center" > <button type="button" class="btn btn-sm btn-success" onclick="selectAll('edit')"> <span class="glyphicon glyphicon-ok"> </button></td>
                                                        {{-- <td style="text-align:center" > <button type="button" class="btn btn-sm btn-success" onclick="selectAll('generate')"> <span class="glyphicon glyphicon-ok"> </button></td> --}}
                                        
                                                    </tr>
                                                    <tr>
                                                        <td style="text-align:center">CSM</td>
                                                        <td style="text-align:center">{{Form::checkbox('permission[0]', 0, false ,['class' => 'view'])}}</td>
                                                        <td style="text-align:center">{{Form::checkbox('permission[1]', 1, false ,['class' => 'add'])}}</td>
                                                        <td style="text-align:center">{{Form::checkbox('permission[2]', 2, false ,['class' => 'edit'])}}</td>
                                                        {{-- <td style="text-align:center">{{Form::checkbox('permission[3]', 3, false ,['class' => 'generate'])}}</td> --}}
                                                        
                                                    </tr>
                                                    <tr>
                                                        <td style="text-align:center">QOA</td>
                                                        <td style="text-align:center">{{Form::checkbox('permission[3]', 3, false ,['class' => 'view'])}}</td>
                                                        <td style="text-align:center">{{Form::checkbox('permission[4]', 4, false ,['class' => 'add'])}}</td>
                                                        <td style="text-align:center">{{Form::checkbox('permission[5]', 5, false ,['class' => 'edit'])}}</td>
                                                        {{-- <td style="text-align:center">{{Form::checkbox('permission[7]', 7, false ,['class' => 'generate'])}}</td> --}}
                                                    </tr>
                                                    <tr>
                                                        <td style="text-align:center">MR</td>
                                                        <td style="text-align:center">{{Form::checkbox('permission[6]', 6, false ,['class' => 'view'])}}</td>
                                                        <td style="text-align:center">{{Form::checkbox('permission[7]', 7, false ,['class' => 'add'])}}</td>
                                                        <td style="text-align:center">{{Form::checkbox('permission[8]', 8, false ,['class' => 'edit'])}}</td>
                                                        {{-- <td style="text-align:center">{{Form::checkbox('permission[11]', 11, false ,['class' => 'generate'])}}</td> --}}
                                                    </tr>
                                                    <tr>
                                                        <td style="text-align:center">QMSD</td>
                                                        <td style="text-align:center">{{Form::checkbox('permission[9]', 9, false ,['class' => 'view'])}}</td>
                                                        <td style="text-align:center">{{Form::checkbox('permission[10]', 10, false ,['class' => 'add'])}}</td>
                                                        <td style="text-align:center">{{Form::checkbox('permission[11]', 11, false ,['class' => 'edit'])}}</td>
                                                        {{-- <td style="text-align:center">{{Form::checkbox('permission[15]', 15, false ,['class' => 'generate'])}}</td> --}}
                                                
                                                    </tr>
                                                </tbody>
                                            </table>
                                </div>
    
                            </div>
                            
                        </div>
                        {{Form::submit('ADD', ['class'=>'btn btn-primary submit-btn'])}}

                {!! Form::close() !!}
        </div>
    
    </div>
</div>

@endsection