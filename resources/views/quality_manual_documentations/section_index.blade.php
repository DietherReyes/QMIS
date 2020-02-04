@extends('layouts.app')

@section('content')

<div class="container-fluid">
    <div class="row">
        @include('include.add_section_sidebar');
        <div class="col-md-9  main">
            <ol class="breadcrumb">
                <li><a href="/">Home</a></li>
                <li><a href="/qmsd">Quality Management System Documentation</a></li>
                <li class="active"> Quality Manual Section</li>
            </ol>


            <div style="float:right">
                {{-- <a class="btn btn-primary btn-md" data-toggle="modal" data-target="#addSection">ADD</a> --}}
                <a class="btn btn-primary btn-md" href="/qmsd/sections/add">ADD</a>
                <a class="btn btn-primary btn-md" href="/qmsd">BACK</a>
            </div>

            
            <div class="modal fade" id="addSection" tabindex="-1" role="dialog" aria-labelledby="addSection" aria-hidden="true">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                      <h4 class="modal-title">Add Section</h4>
                    </div>

                    {!! Form::open(['action' => 'QualityManualDocumentationsController@store_section', 'method' => 'POST']) !!}
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-12">
                                
        
                                    <div class="form-group{{ $errors->has('section_name') ? ' has-error' : '' }}">
                                        {{Form::label('section_name', 'Section Name')}}
                                        {{Form::text('section_name', '', ['class' => 'form-control'])}}
                    
                                        @if ($errors->has('section_name'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('section_name') }}</strong>
                                            </span>
                                        @endif
                                    
                                    </div>
                                
                                        
                                </div>
                        </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            {{Form::submit('ADD', ['class'=>'btn btn-primary submit-btn'])}}  
                        </div>
                    
                    {!! Form::close() !!}
                   
                  </div>
                </div>
            </div>


            <h1 class="page-header">Quality Manual Sections</h1>

            <div class="container">
                
                    {!! Form::open(['action' => 'QualityManualDocumentationsController@search_section', 'method' => 'POST']) !!}
                    <div class="row">
                        <div class="col-md-9">
                            <div class="input-group">
                            <input name="search_term" type="text" class="form-control" >
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