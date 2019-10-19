@extends('layouts.app')

@section('content')

<div class="container-fluid">
    <div class="row">
        @include('include.sidebar')
        <div class="col-md-2" style="float:right">
            <a class="btn btn-primary btn-md"  href="/sysmg/units/create">ADD</a>
        </div>
        
        <div class="col-md-8  main">


            <h1 class="page-header">FUNCTIONAL UNITS</h1>

            <div class="container">
                    {!! Form::open(['action' => 'FunctionalUnitsController@search', 'method' => 'POST', 'class' => 'form-inline']) !!}
                    <div class="form-group">
                        {{Form::text('search_term', '', ['class' => 'form-control', 'placeholder' => 'Search', 'style' => 'width:720px'])}}
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
                <p>No Functional Units</p>
            @endif
        </div>
    </div>    
</div>

@endsection