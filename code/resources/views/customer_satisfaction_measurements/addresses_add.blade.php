@extends('layouts.app')

@section('content')

<div class="container-fluid">
    <div class="row">
        @include('include.addresses_sidebar');
        <div class="col-md-9  main">
            <ol class="breadcrumb">
                <li><a href="/">Home</a></li>
                <li><a href="/csm">Customer Satisfaction Measurement</a></li>
                <li><a href="/csm/addresses/idx">Addresses</a></li>
                <li class="active"> Add Address</li>
            </ol>
            <div style="float:right">
                <a class="btn btn-primary btn-md"  href="/csm/addresses/idx">BACK</a>
            </div>
            <h1 class="page-header">Add Address</h1>

            
                {!! Form::open(['action' => 'CustomerSatisfactionMeasurementsController@store_address', 'method' => 'POST']) !!}
                   <div class="row">
                        <div class="col-md-12">
                           

                            <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                                {{Form::label('name', 'Address')}}
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


