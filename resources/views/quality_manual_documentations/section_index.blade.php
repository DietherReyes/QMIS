@extends('layouts.app')

@section('content')

<div class="container-fluid">
    <div class="row">
        @include('include.add_section_sidebar');
        <div class="col-md-11  main">
            <ol class="breadcrumb">
                <li><a href="/">Home</a></li>
                <li><a href="/qmsd">Quality Management System Documentation</a></li>
                <li class="active"> Quality Manual Section</li>
            </ol>
            <div style="float:right">
                <a class="btn btn-primary btn-md"  href="/qmsd">BACK</a>
            </div>
            <h1 class="page-header">Quality Manual Sections</h1>

            <div class="container">
                {!! Form::open(['action' => 'QualityManualDocumentationsController@add_section', 'method' => 'POST']) !!}
                    <div class="row">
                        <div class="col-md-11">
                            <div class="form-group">
                                {{Form::label('section_name', 'Section Name')}}
                                {{Form::text('section_name', '', ['class' => 'form-control', 'placeholder' => 'Section Name'])}}
                            </div>
                        </div>

                        <div class="col-md-1">
                            {{Form::submit('ADD', ['class'=>'btn btn-primary filter-button'])}}
                        </div>
                        
                    </div>
                
                {!! Form::close() !!}
            </div>

            <div class="container">
                    
                {!! Form::open(['action' => 'QualityManualDocumentationsController@search_section', 'method' => 'POST']) !!}
                    <div class="row">
                        <div class="col-md-10">
                            <div class="form-group">
                                {{Form::label('search_term', 'Search')}}
                                {{Form::text('search_term', '', ['class' => 'form-control', 'placeholder' => 'Search'])}}
                            </div>
                        </div>

                        <div class="col-md-2">
                            {{Form::submit('SEARCH', ['class'=>'btn btn-primary filter-button'])}}
                            <a href="/qmsd/sections/idx" class="btn btn-primary btn-md filter-button">
                                <span class="glyphicon glyphicon-refresh"></span>
                            </a>
                        </div>
                        
                    </div>
                
                {!! Form::close() !!}
            </div>
           
                
            

            @if(count($sections) > 0)
            
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Section Name</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @for ($i = 0; $i < count($sections); $i++)
                                <tr>
                                    <td>{{$i + 1}}</td>
                                    <td>{{$sections[$i]->section_name}}</td>
                                    <td> 
                                        <a class="btn btn-success btn-sm"  href="/qmsd/sections/{{$sections[$i]->id}}/edit">EDIT</a> 
                                    </td>
                                </tr>
                            @endfor
                            
                            </tbody>
                        </table>
                    </div>
                    {{$sections->links()}}
            
                @else
                    <img class="center" src="/storage/assets/nothing_found.png">
                    <div id="notfound" >
                        <h1  >No Results Found</h1>
                    </div>
                @endif
        </div>
    </div>    
</div>

@endsection