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
                        <li class="active"> Edit Administrator </li>
                </ol>

                <div style="float:right">
                    <a class="btn btn-primary btn-md"  href="/sysmg/accounts">BACK</a>
                </div>

                <h1 class="page-header">Edit {{ $user->name}}</h1>

               

                {!! Form::open(['action' => ['UsersController@update', $user->id], 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}

                <div class="row">
                        <div class="col-md-9">


                                <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                                        {{Form::label('name', 'Name')}}
                                        {{Form::text('name', $user->name, ['class' => 'form-control'])}}
                
                                        @if ($errors->has('name'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('name') }}</strong>
                                            </span>
                                        @endif
                                        
                                    </div>
                
        
                                    <div class="form-group{{ $errors->has('position') ? ' has-error' : '' }}">
                                        {{Form::label('position', 'Position')}}
                                        {{Form::text('position', $user->position, ['class' => 'form-control'])}}
                
                                        @if ($errors->has('position'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('position') }}</strong>
                                            </span>
                                        @endif
                                        
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
        
        
                                   
        
                                    <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">
                                        {{Form::label('username', 'username')}}
                                        {{Form::text('username', $user->username, ['class' => 'form-control'])}}
                
                                        @if ($errors->has('username'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('username') }}</strong>
                                            </span>
                                        @endif
                                        
                                    </div>
        
        
                                    <div class="form-group{{ $errors->has('profile_photo') ? ' has-error' : '' }}">
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
                                        @if ($errors->has('profile_photo'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('profile_photo') }}</strong>
                                            </span>
                                        @endif
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
                                                        {{-- <th style="text-align:center">Generate</th> --}}
                                                </tr>
                                            </thead>
                                            
                                            <tbody>

                                                <tr>
                                                    <td style="text-align:center">CSM</td>
                                                    @for ($i = 0; $i <= 2; $i++)
                                                        <td style="text-align:center"><span class="glyphicon glyphicon-ok" style="color:green"> </td>
                                                    @endfor
                                                </tr>
                                                <tr>
                                                    <td style="text-align:center">QOA</td>
                                                    @for ($i = 3; $i <= 5; $i++)
                                                        <td style="text-align:center"><span class="glyphicon glyphicon-ok" style="color:green"> </td>
                                                    @endfor
                                                </tr>
                                                <tr>
                                                    <td style="text-align:center">MR</td>
                                                    @for ($i = 6; $i <= 8; $i++)
                                                        <td style="text-align:center"><span class="glyphicon glyphicon-ok" style="color:green"> </td>
                                                    @endfor
                                                </tr>
                                                <tr>
                                                    <td style="text-align:center">QMSD</td>
                                                    @for ($i = 9; $i <= 11; $i++)
                                                        <td style="text-align:center"><span class="glyphicon glyphicon-ok" style="color:green"> </td>
                                                    @endfor
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