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
                    {!! Form::open(['action' => 'QualityManualDocumentationsController@search', 'method' => 'POST', 'class' => 'form-inline']) !!}
                        <div class="form-group">
                            {{Form::text('search_term', '', ['class' => 'form-control', 'placeholder' => 'Search', 'style' => 'width:850px'])}}
                        </div>
                    {{Form::submit('SEARCH', ['class'=>'btn btn-primary'])}}
                        <a href="/qmsd" class="btn btn-primary btn-md">
                            <span class="glyphicon glyphicon-refresh"></span> Refresh
                        </a>
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
                                <th>Division</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @for ($i = 0; $i < count($manual_docs); $i++)
                                <tr>
                                    <td>{{$i + 1}}</td>
                                    <td>{{$manual_docs[$i]->document_code}}</td>
                                    <td>{{$manual_docs[$i]->subject}}</td>
                                    <td>{{$manual_docs[$i]->division}}</td>
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