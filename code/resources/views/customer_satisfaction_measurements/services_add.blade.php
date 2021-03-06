@extends('layouts.app')

@section('content')

<div class="container-fluid">
    <div class="row">
        @include('include.services_sidebar');
        <div class="col-md-9  main">
            <ol class="breadcrumb">
                <li><a href="/">Home</a></li>
                <li><a href="/csm">Customer Satisfaction Measurement</a></li>
                <li><a href="/csm/services/idx">Services</a></li>
                <li class="active"> Add Services</li>
            </ol>
            <div style="float:right">
                <a class="btn btn-primary btn-md"  href="/csm/services/idx">BACK</a>
            </div>
            <h1 class="page-header">Add Service</h1>

            
                {!! Form::open(['action' => 'CustomerSatisfactionMeasurementsController@store_service', 'method' => 'POST']) !!}
                   <div class="row">
                        <div class="col-md-12">
                           

                            <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                                {{Form::label('name', 'Service Name')}}
                                {{Form::text('name', '', ['class' => 'form-control'])}}
            
                                @if ($errors->has('name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                               
                            </div>
                        
                                
                        </div>
                   </div>
                   <br> 
                   
                   {{Form::submit('ADD', ['class'=>'btn btn-primary submit-btn'])}}   
                    
                {!! Form::close() !!}
           
        </div>
    </div>    
</div>

@endsection