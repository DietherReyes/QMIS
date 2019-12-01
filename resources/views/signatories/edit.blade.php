@extends('layouts.app')

@section('content')

<div class="container-fluid">
    <div class="row">

        @include('include.signatories_sidebar')
        <div class="col-md-9  main">
                <ol class="breadcrumb">
                    <li><a href="/">Home</a></li>
                    <li><a href="/sysmg/signatories">Signatories</a></li>
                    <li class="active"> Edit Signatory </li>
                </ol>
                
                <div style="float:right">
                        
                        <a class="btn btn-primary btn-md"  href="/sysmg/signatories">BACK</a>
                </div>
                
                <h1 class="page-header"> Edit Signatory </h1>
                {!! Form::open(['action' => ['SignatoriesController@update', $signatory->id], 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}
                <div class="row">
                        <div class="col-md-8">
                            
                            <div class="form-group">
                                {{Form::label('name', 'Name')}}
                                {{Form::text('name', $signatory->name, ['class' => 'form-control'])}}
                            </div>
        
                            <div class="form-group">
                                {{Form::label('position', 'Position')}}
                                {{Form::text('position', $signatory->position, ['class' => 'form-control'])}}
                            </div>
                            <div class="form-group">
                                {{Form::label('signature_photo', 'Signature(.png)')}}
                                {{Form::file('signature_photo', [ 'class' => 'hidden', 'id' => 'signature_photo' ,'onChange' => 'uploadName(this.id, \'signature_photo_text\')'])}}
                                <div class="row">
                                    <div class="col-md-9">
                                        {{Form::text('signature_photo_text', $signatory->signature_photo, ['class' => 'form-control', 'id' => 'signature_photo_text', 'disabled'])}}
                                    </div>
                                    <div class="col-md-3">
                                        {{Form::label('signature_photo', 'Upload File', ['class' => 'file-input', 'for' => 'signature_photo'])}}
                                    </div> 
                                </div>
                            </div>
                            {{Form::hidden('_method','PUT')}}

                            {{Form::submit('EDIT', ['class'=>'btn btn-primary submit-btn'])}}
                            
                        </div>
                        <div class="col-md-4">
                                <img class="profile-photo" src="/storage/signature_photos/{{$signatory->signature_photo}}">
                        </div>
                    </div>
                  
                    

                    
                    

                {!! Form::close() !!}
        </div>
    
    </div>
</div>

@endsection