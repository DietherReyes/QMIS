@extends('layouts.app')

@section('content')

<div class="container">
                <ol class="breadcrumb">
                    <li><a href="/">Home</a></li>
                    <li><a href="/profiles/{{$user->id}}">My Profile</a> </li>
                    <li class="active"> Edit Profile</li>
                </ol>
                    
                <div style="float:right">
                    <a class="btn btn-primary btn-md"  href="/profiles/{{$user->id}}">BACK</a>
                </div>
                    
                <h1 class="page-header"> Edit {{$user->name}} </h1>
            
                <img style="width:150px;" src="/storage/profile_photos/{{$user->profile_photo}}">

                {!! Form::open(['action' => ['ProfilesController@update', $user->id], 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}

                    <div class="form-group">
                        {{Form::label('profile_photo', 'Profile Photo (.jpg)')}}
                        {{Form::file('profile_photo')}}
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
                        {{Form::label('username', 'Username')}}
                        {{Form::text('username', $user->username, ['class' => 'form-control'])}}
                    </div>
                    
                    {{Form::hidden('_method','PUT')}}
                    {{Form::submit('Submit', ['class'=>'btn btn-primary'])}}

                {!! Form::close() !!}
        
</div>

@endsection