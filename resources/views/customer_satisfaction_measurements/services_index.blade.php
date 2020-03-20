@extends('layouts.app')

@section('content')

<div class="container-fluid">
    <div class="row">
        @include('include.services_sidebar');
        <div class="col-md-9  main">
            <ol class="breadcrumb">
                <li><a href="/">Home</a></li>
                <li><a href="/csm">Customer Satisfaction Measurement</a></li>
                <li class="active"> Services </li>
            </ol>
            <div style="float:right">
                <a class="btn btn-primary btn-md" href="/csm/services/add">ADD</a>
                <a class="btn btn-primary btn-md"  href="/csm">BACK</a>
            </div>
            <h1 class="page-header">Services</h1>

            <div class="container">
                
                    {!! Form::open(['action' => 'CustomerSatisfactionMeasurementsController@search_service', 'method' => 'POST']) !!}
                    <div class="row">
                        <div class="col-md-10">
                            <div class="input-group">
                            <input name="search_term" type="text" class="form-control">
                            <span class="input-group-btn">
                                    <button type="submit" class="btn btn-primary btn-md">
                                            <span class="glyphicon glyphicon-search"> </span>
                                    </button>
                                    <a href="/csm/services/idx" class="btn btn-primary btn-md">
                                        <span class="glyphicon glyphicon-refresh"></span> REFRESH
                                    </a>
                            </span>
                            </div>
                        </div>
                    </div>
                    
                    {!! Form::close() !!}
                </div>
           
                
            

            @if(count($services) > 0)
            
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Service Name</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @for ($i = 0; $i < count($services); $i++)
                                <tr>
                                    <td>{{$i + 1}}</td>
                                    <td>{{$services[$i]->name}}</td>
                                    <td> 
                                        <a class="btn btn-success btn-sm"  href="/csm/services/{{$services[$i]->id}}/edit">EDIT</a> 
                                    </td>
                                </tr>
                            @endfor
                            
                            </tbody>
                        </table>
                    </div>
                    {{$services->links()}}
            
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