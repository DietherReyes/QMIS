@extends('layouts.app')

@section('content')

<div class="container-fluid">
    <div class="row">

        @include('include.account_sidebar')

        <div class="col-md-9  main">
                <ol class="breadcrumb">
                        <li><a href="/">Home</a></li>
                        <li><a href="/sysmg/accounts">User Account Management</a></li>
                        <li class="active"> Change Password </li>
                </ol>

                <div style="float:right">
                    <a class="btn btn-primary btn-md"  href="/sysmg/accounts">BACK</a>
                </div>

                <h1 class="page-header"> {{ $user->name}}</h1>
                <h3>CHANGE PASSWORD</h3>
                {!! Form::open(['action' => ['UsersController@update_password', $user->id], 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}

                    <div class="form-group">
                        {{Form::label('password','New Password')}}
                        <input type="password" class="form-control" name="password">
                    </div>

                    <div class="form-group">
                        {{Form::label('password_confirmation','Confirm New Password')}}
                        <input type="password" class="form-control" name="password_confirmation">
                    </div>

                    
                    {{Form::hidden('_method','PUT')}}
                    {{Form::submit('CHANGE PASSWORD', ['class'=>'btn btn-primary submit-btn'])}}

                {!! Form::close() !!}
        </div>
    
    </div>
</div>

@endsection