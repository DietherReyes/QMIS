@extends('layouts.app')

@section('content')

<div class="container-fluid">
    <div class="row">
        @include('include.add_section_sidebar');
        <div class="col-md-10  main">
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
                            <div class="input-group {{ $errors->has('section_name') ? ' has-error' : '' }}">
                                <input name="section_name" type="text" class="form-control" placeholder="New Section Name">
                                
                                <span class="input-group-btn">
                                        {{Form::submit('ADD SECTION', ['class'=>'btn btn-primary'])}}
                                </span>
                               
                            </div>

                            @if ($errors->has('section_name'))
                                <div class="has-error">
                                    <span class="help-block">
                                        <strong>{{ $errors->first('section_name') }}</strong>
                                    </span>
                                </div>
                                
                            @endif
                        </div>
                        
                    </div>
                    
                    {!! Form::close() !!}
            </div>

            <br>
        

            <div class="container">
                
                    {!! Form::open(['action' => 'QualityManualDocumentationsController@search_section', 'method' => 'POST']) !!}
                    <div class="row">
                        <div class="col-md-11">
                            <div class="input-group">
                            <input name="search_term" type="text" class="form-control" placeholder="Search">
                            <span class="input-group-btn">
                                    <button type="submit" class="btn btn-primary btn-md">
                                            <span class="glyphicon glyphicon-search"> </span> SEARCH
                                    </button>
                                    <a href="/qmsd/sections/idx" class="btn btn-primary btn-md">
                                        <span class="glyphicon glyphicon-refresh"></span> REFRESH
                                    </a>
                            </span>
                            </div>
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