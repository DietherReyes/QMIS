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

</script>
<div class="container-fluid">
    <div class="row">

        @include('include.account_sidebar')

        

        <div class="col-md-9  main">

                <ol class="breadcrumb">
                        <li><a href="/">Home</a></li>
                        <li><a href="/sysmg/accounts">User Account Management</a></li>
                        <li class="active"> Add Employee </li>
                </ol>

                <div style="float:right">
                    <a class="btn btn-primary btn-md"  href="/sysmg/accounts">BACK</a>
                </div>

                <h1 class="page-header">Add Employee</h1>
                

                {!! Form::open(['action' => 'UsersController@store_employee', 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}

                <div class="row">
                        <div class="col-md-12">

                            <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                                {{Form::label('name', 'Name')}}
                                {{Form::text('name', '', ['class' => 'form-control'])}}
        
                                @if ($errors->has('name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                                
                            </div>
        

                            <div class="form-group{{ $errors->has('position') ? ' has-error' : '' }}">
                                {{Form::label('position', 'Position')}}
                                {{Form::text('position', '', ['class' => 'form-control'])}}
        
                                @if ($errors->has('position'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('position') }}</strong>
                                    </span>
                                @endif
                                
                            </div>

                          
                            <div class="form-group {{ $errors->has('functional_unit') ? ' has-error' : '' }}">
                                    {{Form::label('functional_unit', 'Functional Unit')}}
                                    {{Form::select('functional_unit', $data, null, ['class' => 'form-control', 'placeholder' => 'Click to select functional unit'])}}
                                    
                                    @if ($errors->has('functional_unit'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('functional_unit') }}</strong>
                                        </span>
                                    @endif
                                </div>


                           

                            <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">
                                {{Form::label('username', 'username')}}
                                {{Form::text('username', '', ['class' => 'form-control'])}}
        
                                @if ($errors->has('username'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('username') }}</strong>
                                    </span>
                                @endif
                                
                            </div>

                            

                            <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                                {{Form::label('password', 'Password')}}
                                <input type="password" class="form-control" name="password">
        
                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                                
                            </div>

                            <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                                {{Form::label('password_confirmation','Confirm Password')}}
                                <input type="password" class="form-control" name="password_confirmation">
        
                                @if ($errors->has('password_confirmation'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password_confirmation') }}</strong>
                                    </span>
                                @endif
                                
                            </div>

                            <div class="form-group{{ $errors->has('profile_photo') ? ' has-error' : '' }}">
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
                                @if ($errors->has('profile_photo'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('profile_photo') }}</strong>
                                    </span>
                                @endif
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
                                                    <td style="text-align:center">{{Form::checkbox('permission[]', 0, false ,['class' => 'view'])}}</td>
                                                   
                                                </tr>
                                                <tr>
                                                    <td style="text-align:center">QOA</td>
                                                    <td style="text-align:center">{{Form::checkbox('permission[]', 3, false ,['class' => 'view'])}}</td>
                                                    
                                                </tr>
                                                <tr>
                                                    <td style="text-align:center">MR</td>
                                                    <td style="text-align:center">{{Form::checkbox('permission[]', 6, false ,['class' => 'view'])}}</td>
                                                    
                                                </tr>
                                                <tr>
                                                    <td style="text-align:center">QMSD</td>
                                                    <td style="text-align:center">{{Form::checkbox('permission[]', 9, false ,['class' => 'view'])}}</td>
                                                    
                                            
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