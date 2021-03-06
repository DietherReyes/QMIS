@extends('layouts.app')

@section('content')

<div class="container-fluid">
        <div class="row">
            <div class="col-md-1"></div>
    
            <div class="col-md-10 main">
                    <ol class="breadcrumb">
                            <li><a href="/">Home</a></li>
                            <li><a href="/profiles/{{$user->id}}">My Profile</a> </li>
                            <li class="active"> Change Password</li>
                        </ol>
                            
                        <div style="float:right">
                            <a class="btn btn-primary btn-md"  href="/profiles/{{$user->id}}">BACK</a>
                        </div>
                            
                        <h1 class="page-header">Change Password </h1>
                                {!! Form::open(['action' => ['ProfilesController@update_password', $user->id], 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}
                
                               
                                <div class="form-group{{ $errors->has('old_password') ? ' has-error' : '' }} {{ ($old_password_error === 1) ?   ' has-error' : ''}}">
                                        {{Form::label('old_password','Old Password')}}
                                        <input type="password" class="form-control" name="old_password">
                    
                                        @if ($errors->has('old_password'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('old_password') }}</strong>
                                            </span>
                                        @endif

                                        @if ($old_password_error === 1)
                                            <span class="help-block">
                                                <strong> Incorrect old password input.</strong>
                                            </span>
                                        @endif
                                    </div>
                
                                    <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                                        {{Form::label('password', 'New Password')}}
                                        <input type="password" class="form-control" name="password">
                
                                        @if ($errors->has('password'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('password') }}</strong>
                                            </span>
                                        @endif
                                        
                                    </div>
                
                                    <div class="form-group">
                                        {{Form::label('password_confirmation','Confirm New Password')}}
                                        <input type="password" class="form-control" name="password_confirmation">
                                    </div>
                
                                    
                                    {{Form::hidden('_method','PUT')}}
                                    {{Form::submit('CHANGE PASSWORD', ['class'=>'btn btn-primary submit-btn'])}}
                
                                {!! Form::close() !!}
    
            </div>
    
            <div class="col-md-1"></div>
    
        </div>
                    
    </div>


@endsection