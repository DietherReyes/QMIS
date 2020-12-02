@extends('layouts.app')

@section('content')

<div class="container-fluid">
    <div class="row">
        @include('include.logs_sidebar')
       
        
        <div class="col-md-9  main">
            <ol class="breadcrumb">
                <li><a href="/">Home</a></li>
                <li class="active">Logs</li>
            </ol>

            <h1 class="page-header">System Logs</h1>

            <div class="container">
            
                {!! Form::open(['action' => 'LogsController@search', 'method' => 'POST']) !!}
                <div class="row">
                    <div class="col-md-10">
                        <div class="input-group">
                        <input name="search_term" type="text" class="form-control" placeholder="Search">
                        <span class="input-group-btn">
                                <button type="submit" class="btn btn-primary btn-md">
                                        <span class="glyphicon glyphicon-search"> </span>
                                </button>
                                <a href="/sysmg/units" class="btn btn-primary btn-md">
                                    <span class="glyphicon glyphicon-refresh"></span> REFRESH
                                </a>
                        </span>
                        </div>
                    </div>
                </div>
                
                {!! Form::close() !!}
            </div>
           
                
            

            @if(count($logs) > 0)
        
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Action</th>
                            <th>Module</th>
                            <th>Description</th>
                            <th>Timestamp</th>
                        </tr>
                        </thead>
                        <tbody>
                        @for ($i = 0; $i < count($logs); $i++)
                            <tr>
                                <td>{{$i + 1}}</td>
                                <td>{{$logs[$i]->name}}</td>
                                <td>{{$logs[$i]->action}}</td>
                                <td>{{$logs[$i]->module}}</td>
                                <td>{{$logs[$i]->description}}</td>
                                <td>{{$logs[$i]->created_at}}</td>
                            </tr>
                        @endfor
                        
                        </tbody>
                    </table>
                </div>
                {{$logs->links()}}
        
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