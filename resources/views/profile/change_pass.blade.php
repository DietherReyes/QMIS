@extends('layouts.app')

@section('content')

<div class="container">
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

                    <div class="form-group">
                        {{Form::label('old_password','Old Password')}}
                        <input type="password" class="form-control" name="old_password">
                    </div>

                    <div class="form-group">
                        {{Form::label('password','New Password')}}
                        <input type="password" class="form-control" name="password">
                    </div>

                    <div class="form-group">
                        {{Form::label('password_confirmation','Confirm New Password')}}
                        <input type="password" class="form-control" name="password_confirmation">
                    </div>

                    
                    {{Form::hidden('_method','PUT')}}
                    {{Form::submit('Submit', ['class'=>'btn btn-primary'])}}

                {!! Form::close() !!}
       
</div>

@endsection