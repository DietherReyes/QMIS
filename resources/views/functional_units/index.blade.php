@extends('layouts.app')

@section('content')

<div class="container-fluid">
    <div class="row">
        @include('include.units_sidebar')
       
        
        <div class="col-md-9  main">
            <ol class="breadcrumb">
                <li><a href="/">Home</a></li>
                <li class="active">Functional Units</li>
            </ol>
            <div style="float:right">
                <a class="btn btn-primary btn-md"  href="/sysmg/units/create">ADD</a>
            </div>

            <h1 class="page-header">Functional Units</h1>

            <div class="container">
                    {!! Form::open(['action' => 'FunctionalUnitsController@search', 'method' => 'POST', 'class' => 'form-inline']) !!}
                    <div class="form-group">
                        {{Form::text('search_term', '', ['class' => 'form-control', 'placeholder' => 'Search', 'style' => 'width:745px'])}}
                    </div>
                    {{Form::submit('SEARCH', ['class'=>'btn btn-primary'])}}
                    <a href="/sysmg/units" class="btn btn-primary btn-md">
                        <span class="glyphicon glyphicon-refresh"></span> Refresh
                    </a>
                {!! Form::close() !!}
            </div>
           
                
            

            @if(count($functional_units) > 0)
        
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Abbreviation</th>
                            <th>Name</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @for ($i = 0; $i < count($functional_units); $i++)
                            <tr>
                                <td>{{$i + 1}}</td>
                                <td>{{$functional_units[$i]->abbreviation}}</td>
                                <td>{{$functional_units[$i]->name}}</td>
                                <td> 
                                    <a class="btn btn-primary btn-sm"  href="/sysmg/units/{{$functional_units[$i]->id}}">VIEW</a> 
                                    <a class="btn btn-success btn-sm"  href="/sysmg/units/{{$functional_units[$i]->id}}/edit">EDIT</a> 
                                </td>
                            </tr>
                        @endfor
                        
                        </tbody>
                    </table>
                </div>
                {{$functional_units->links()}}
        
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