@extends('layouts.app')

@section('content')

<div class="container-fluid">
    <div class="row">
        
        <div class="col-md-1"></div>

        <div class="col-md-10 main">

                <ol class="breadcrumb">
                    <li><a href="/">Home</a></li>
                    <li class="active">Quality Management System Documentation </li>
                </ol>
                    
                <div style="float:right">
                    <a class="btn btn-primary btn-md"  href="/qmsd/create">ADD</a>
                </div>
                    
                <h1 class="page-header"> Quality Managment System Documentation </h1>
            
               
                <div class="container">
                
                        {!! Form::open(['action' => 'QualityManualDocumentationsController@search', 'method' => 'POST']) !!}
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    
                                    {{Form::select('type', $data, $data['QM'] , ['class' => 'form-control'])}}
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="input-group">
                                    
                                    <input name="search_term" type="text" class="form-control" placeholder="Search">
                                    <span class="input-group-btn">
                                            <button type="submit" class="btn btn-primary btn-md">
                                                    <span class="glyphicon glyphicon-search"> </span>
                                            </button>
                                            <a href="/qmsd" class="btn btn-primary btn-md">
                                                <span class="glyphicon glyphicon-refresh"></span> REFRESH
                                            </a>
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        {!! Form::close() !!}
                    </div>
            
                
            
            
                @if(count($manual_docs) > 0)
            
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Document Code</th>
                                <th>Subject</th>
                                <th>Page Number</th>
                                <th>Revision Number</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @for ($i = 0; $i < count($manual_docs); $i++)
                                <tr>
                                    <td>{{$i + 1}}</td>
                                    <td>{{$manual_docs[$i]->document_code}}</td>
                                    <td>{{$manual_docs[$i]->subject}}</td>
                                    <td>{{$manual_docs[$i]->page_number}}</td>
                                    <td>{{$manual_docs[$i]->revision_number}}</td>
                                    <td> 
                                        <a class="btn btn-primary btn-sm"  href="/qmsd/{{$manual_docs[$i]->id}}">VIEW</a> 
                                        <a class="btn btn-success btn-sm"  href="/qmsd/{{$manual_docs[$i]->id}}/edit">EDIT</a> 
                                    </td>
                                </tr>
                            @endfor
                            
                            </tbody>
                        </table>
                    </div>
                    {{$manual_docs->links()}}
            
                @else
                    <img class="center" src="/storage/assets/nothing_found.png">
                    <div id="notfound" >
                        <h1  >No Results Found</h1>
                    </div>
                @endif

        </div>
        <div class="col-md-1"> </div>
    </div>
</div>



@endsection