@extends('layouts.app')

@section('content')

<div class="container-fluid">
    <div class="row">
        
        <div class="col-md-1"></div>

        <div class="col-md-10 main">

                <ol class="breadcrumb">
                    <li><a href="/">Home</a></li>
                    <li><a href="/csm">Customer Satisfaction Measurement</a></li>
                    <li class="active">CSM Statistics</li>
                </ol>
                    
                <div style="float:right">
                    <a class="btn btn-primary btn-md"  href="/csm">BACK</a>
                </div>

                <h1 class="page-header">CSM Statistics</h1>
            
                <div class="container">
                    {!! Form::open(['action' => 'CustomerSatisfactionMeasurementsController@search_graphs', 'method' => 'POST']) !!}
                        <div class="row">
                            <div class="col-md-7">
                                <div class="form-group">
                                    {{Form::label('functional_unit', 'Functional Unit')}}
                                    {{Form::select('functional_unit', $data, $unit_name , ['class' => 'form-control'])}}
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    {{Form::label('year', 'CSM Year')}}
                                    {{Form::select('year', $years, $unit_year , ['class' => 'form-control'])}}
                                </div>
                            </div>

                            <div class="col-md-2">
                                    <span class="input-group-btn filter-button">
                                            <button type="submit" class="btn btn-primary btn-md">
                                                    <span class="glyphicon glyphicon-search"> </span>
                                            </button>
                                            <a href="/csm/statistics/idx" class="btn btn-primary btn-md">
                                                <span class="glyphicon glyphicon-refresh"></span> REFRESH
                                            </a>
                                    </span>
                            </div>
                            
                        </div>
                    
                    {!! Form::close() !!}
                </div>
                <br>

               

                <div class="row">

                    <div class="col-md-6"> 
                        {!! $overall_ratings_chart->container() !!}
                            {!! $overall_ratings_chart->script() !!}
                       
                    </div>

                    <div class="col-md-6">

                            

                            {!! $customer_ratings_chart->container() !!}
                            {!! $customer_ratings_chart->script() !!}
                            
                    </div>
                    

                </div>


                <div class="row">

                    <div class="col-md-6"> 
                        {!! $customer_services_chart->container() !!}
                            {!! $customer_services_chart->script() !!}
                       
                    </div>

                    <div class="col-md-6">

                            
                        {!! $customer_addresses_chart->container() !!}
                            {!! $customer_addresses_chart->script() !!}
                           
                            
                    </div>
                    

                </div>

                
                    

        </div>
        <div class="col-md-1"> </div>
    </div>
</div>



@endsection