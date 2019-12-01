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
        <div class="col-md-1"></div>

        <div class="col-md-10 main">
                <ol class="breadcrumb">
                    <li><a href="/">Home</a></li>
                    <li><a href="/profiles/{{$user->id}}">My Profile</a> </li>
                    <li class="active"> Edit Profile</li>
                </ol>
                    
                <div style="float:right">
                    <a class="btn btn-primary btn-md"  href="/profiles/{{$user->id}}">BACK</a>
                </div>
                    
                <h1 class="page-header"> Edit {{$user->name}} </h1>
            
                

                {!! Form::open(['action' => ['ProfilesController@update', $user->id], 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}

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
                            {{Form::hidden('_method','PUT')}}
                            {{Form::submit('EDIT', ['class'=>'btn btn-primary submit-btn'])}}

                        </div>
                        <div class="col-md-3">
                            <img id="profile-photo" src="/storage/profile_photos/{{$user->profile_photo}}">
                        </div>
                    </div>

            
                    
                {!! Form::close() !!}

        </div>

        <div class="col-md-1"></div>

    </div>
                
</div>

@endsection