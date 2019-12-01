@extends('layouts.app')

@section('content')

<div class="container-fluid">
    <div class="row">
        @include('include.add_section_sidebar');
        <div class="col-md-11  main">
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

            <div class="container">
                {!! Form::open(['action' => ['QualityManualDocumentationsController@update_section', $section->id], 'method' => 'POST']) !!}
                    <div class="row">
                        <div class="col-md-11">
                            <div class="form-group">
                                {{Form::label('section_name', 'Section Name')}}
                                {{Form::text('section_name', $section->section_name, ['class' => 'form-control', 'placeholder' => 'Section Name'])}}
                            </div>
                        </div>

                        <div class="col-md-1">
                            {{Form::hidden('_method','PUT')}}
                            {{Form::submit('EDIT', ['class'=>'btn btn-primary filter-button'])}}
                        </div>
                        
                    </div>
                
                {!! Form::close() !!}
            </div>

        </div>
    </div>    
</div>

@endsection