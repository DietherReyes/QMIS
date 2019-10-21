@extends('layouts.app')

@section('content')

<div class="container-fluid">
    <div class="row">

        

        <div class="col-md-2" style="float:right">
            <a class="btn btn-primary btn-md"  href="/sysmg/accounts/{{$user->id}}">BACK</a>
        </div>

        <div class="col-md-8  main">
                <h1 class="page-header"> USER ACCOUNTS</h1>
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
                    {{Form::submit('Submit', ['class'=>'btn btn-primary'])}}

                {!! Form::close() !!}
        </div>
    
    </div>
</div>

@endsection