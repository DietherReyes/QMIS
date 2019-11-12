@extends('layouts.app')

@section('content')

<div class="container-fluid">
    <div class="row">

        @include('include.signatories_sidebar')
        <div class="col-md-9  main">
                <<ol class="breadcrumb">
                    <li><a href="/">Home</a></li>
                    <li><a href="/sysmg/signatories">Signatories</a></li>
                    <li class="active"> Edit Signatory </li>
                </ol>
                
                <div style="float:right">
                        
                        <a class="btn btn-primary btn-md"  href="/sysmg/signatories">BACK</a>
                </div>
                
                <h1 class="page-header"> Edit {{$signatory->name}} </h1>
                <img style="width:150px;" src="/storage/signature_photos/{{$signatory->signature_photo}}">
                {!! Form::open(['action' => ['SignatoriesController@update', $signatory->id], 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}

                    <div class="form-group">
                        {{Form::label('signature_photo', 'Signaure (.png)')}}
                        {{Form::file('signature_photo')}}
                    </div>

                    <div class="form-group">
                        {{Form::label('name', 'Name')}}
                        {{Form::text('name', $signatory->name, ['class' => 'form-control'])}}
                    </div>

                    <div class="form-group">
                        {{Form::label('position', 'Position')}}
                        {{Form::text('position', $signatory->position, ['class' => 'form-control'])}}
                    </div>
                    {{Form::hidden('_method','PUT')}}

                    {{Form::submit('Submit', ['class'=>'btn btn-primary'])}}

                {!! Form::close() !!}
        </div>
    
    </div>
</div>

@endsection