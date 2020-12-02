@extends('layouts.app')

@section('content')

<div class="container-fluid">
    <div class="row">
        @include('include.add_section_sidebar');
        <div class="col-md-9  main">
            <ol class="breadcrumb">
                <li><a href="/">Home</a></li>
                <li><a href="/qmsd">Quality Management System Documentation</a></li>
                <li><a href="/qmsd/sections/idx">Quality Manual Section</a></li>
                <li class="active"> Edit Section</li>
            </ol>
            <div style="float:right">
                <a class="btn btn-primary btn-md"  href="/qmsd/sections/idx">BACK</a>
            </div>
            <h1 class="page-header">Edit Section</h1>

            
                {!! Form::open(['action' => ['QualityManualDocumentationsController@update_section', $section->id], 'method' => 'POST']) !!}
                   <div class="row">
                        <div class="col-md-12">
                           

                            <div class="form-group{{ $errors->has('section_name') ? ' has-error' : '' }}">
                                {{Form::label('section_name', 'Section Name')}}
                                {{Form::text('section_name', $section->section_name, ['class' => 'form-control'])}}
            
                                @if ($errors->has('section_name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('section_name') }}</strong>
                                    </span>
                                @endif
                               
                            </div>
                        
                                
                        </div>
                   </div>
                   <br> 
                   {{Form::hidden('_method','PUT')}}
                   {{Form::submit('EDIT', ['class'=>'btn btn-primary submit-btn'])}}   
                    
                {!! Form::close() !!}
           
        </div>
    </div>    
</div>

@endsection