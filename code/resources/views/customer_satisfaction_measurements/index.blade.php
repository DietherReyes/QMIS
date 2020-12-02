@extends('layouts.app')

@section('content')

<div class="container-fluid">
    <div class="row">
        
        <div class="col-md-1"></div>

        <div class="col-md-10 main">

                <ol class="breadcrumb">
                    <li><a href="/">Home</a></li>
                    <li class="active">Customer Satisfaction Measurement</li>
                </ol>
                    
                <div style="float:right">
                    <a class="btn btn-primary btn-md"  data-toggle="modal" data-target="#generate">GENERATE</a>
                    <a class="btn btn-primary btn-md"  href="/csm/statistics/idx">GRAPHS</a>
                    <a class="btn btn-primary btn-md"  href="/csm/create">ADD</a>
                </div>


                <div class="modal fade" id="generate" tabindex="-1" role="dialog" aria-labelledby="generate" aria-hidden="true">
                    <div class="modal-dialog modal-sm">
                        <div class="modal-content">

                            

                            {!! Form::open(['action' => 'SpreadsheetsController@generate', 'method' => 'POST']) !!}
                        
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                    <h4 class="modal-title" >Genarate CSM Report</h4>
                                </div>
    
                                <div class="modal-body">
                                    <div class="row">
                                            <div class="col-md-12">
                                                    <div class="form-group">
                                                        {{Form::label('year', 'Year')}}
                                                        {{Form::select('year', $generate_year, '', ['class' => 'form-control', 'placeholder' => 'Click to select year'])}}
                                                    </div>
                                                </div>

                                    </div>
                                    
                                </div>
    
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    {{Form::submit('GENERATE', ['class'=>'btn btn-primary submit-btn'])}}
                                </div>

                                

                    
                            {!! Form::close() !!}

                        </div>
                    </div>
                </div>
                    
                <h1 class="page-header"> Customer Satisfaction Measurement </h1>
            
                <div class="container">
                    {!! Form::open(['action' => 'CustomerSatisfactionMeasurementsController@filter', 'method' => 'POST']) !!}
                        <div class="row">
                            <div class="col-md-5">
                                <div class="form-group">
                                    {{Form::label('functional_unit', 'Functional Unit')}}
                                    {{Form::select('functional_unit', $data, $data['all'] , ['class' => 'form-control'])}}
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    {{Form::label('year', 'CSM Year')}}
                                    {{Form::select('year', $year, $year['all'] , ['class' => 'form-control'])}}
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    {{Form::label('quarter', 'Quarter')}}
                                    {{Form::select('quarter', $quarter, $quarter['all'], ['class' => 'form-control'])}}
                                </div>
                            </div>

                            <div class="col-md-2">
                                    <span class="input-group-btn filter-button">
                                            <button type="submit" class="btn btn-primary btn-md">
                                                    <span class="glyphicon glyphicon-search"> </span>
                                            </button>
                                            <a href="/csm" class="btn btn-primary btn-md">
                                                <span class="glyphicon glyphicon-refresh"></span> REFRESH
                                            </a>
                                    </span>
                            </div>
                            
                        </div>
                    
                    {!! Form::close() !!}
                </div>
            
                
            
            
                @if(count($csm) > 0)
            
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Functional Unit</th>
                                <th>CSM Year</th>
                                <th>Quarter</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @for ($i = 0; $i < count($csm); $i++)
                                <tr>
                                    <td>{{$i + 1}}</td>
                                    <td>{{$csm[$i]->functional_unit}}</td>
                                    <td>{{$csm[$i]->year}}</td>
                                    <td>{{$csm[$i]->quarter}}</td>
                                    <td> 
                                        <a class="btn btn-primary btn-sm"  href="/csm/{{$csm[$i]->id}}">VIEW</a> 
                                        <a class="btn btn-success btn-sm"  href="/csm/{{$csm[$i]->id}}/edit">EDIT</a> 
                                    </td>
                                </tr>
                            @endfor
                            
                            </tbody>
                        </table>
                    </div>
                    {{$csm->links()}}
            
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