@extends('layouts.app')

@section('content')

<div class="container-fluid">
    <div class="row">
        
        <div class="col-md-1"></div>

        <div class="col-md-10 main">

                <ol class="breadcrumb">
                    <li><a href="/">Home</a></li>
                    <li><a href="/csm">Customer Satisfaction Measurement</a></li>
                    <li class="active">View CSM </li>
                </ol>
                    
                <div style="float:right">
                    <a class="btn btn-primary btn-md" href="/csm/supporting_documents/{{$csm->id}}">Download File(s)</a>
                    <a class="btn btn-success btn-md"  href="/csm/{{$csm->id}}/edit">EDIT</a>
                    <a class="btn btn-primary btn-md"  href="/csm">BACK</a>
                </div>
                    
                <h1 class="page-header"> View CSM </h1>
                
                <div class="row">
                
                    <div class="col-md-6">

                            <ul class="list-group">

                                <h5>Functional Unit</h5>
                                <li class="list-group-item">{{$csm->functional_unit}}</li>
        
                                <h5>Total Customer</h5>
                                <li class="list-group-item">{{$csm->total_customer}}</li>

                            </ul>                           

                    </div>

                    <div class="col-md-3">

                        <ul class="list-group">

                            <h5>CSM Year</h5>
                            <li class="list-group-item">{{$csm->year}}</li>
    
                            <h5>Total Male</h5>
                            <li class="list-group-item">{{$csm->total_male}}</li>

                        </ul> 
            
                    </div>

                    <div class="col-md-3">
                            <ul class="list-group">

                                <h5>Quarter</h5>
                                <li class="list-group-item">{{$csm->quarter}}</li>
        
                                <h5>Total Female</h5>
                                <li class="list-group-item">{{$csm->total_female}}</li>

                            </ul> 
                    </div>
                </div>

                <div class="row">
                        <div class="col-md-12">
                            <h3 class="page-header">Customer Classification</h3>
                        </div>
                    </div>

                <div class="row">

                    <div class="col-md-4">
                            <ul class="list-group">

                                <h5>Student</h5>
                                <li class="list-group-item">{{ (($customer_classification->student !== null) ? $customer_classification->student : 0) }}</li>
        
                                <h5>Business</h5>
                                <li class="list-group-item">{{(($customer_classification->business !== null) ? $customer_classification->business : 0)}}</li>

                                <h5>Entrepreneur</h5>
                                <li class="list-group-item">{{(($customer_classification->entrepreneur !== null) ? $customer_classification->entrepreneur : 0) }}</li>

                            </ul> 
                      
                    </div>

                    <div class="col-md-4">

                            <ul class="list-group">

                                <h5>Government Employee</h5>
                                <li class="list-group-item">{{(($customer_classification->government_employee !== null) ? $customer_classification->government_employee : 0) }}</li>
        
                                <h5>Homemaker</h5>
                                <li class="list-group-item">{{(($customer_classification->homemaker !== null) ? $customer_classification->homemaker : 0) }}</li>

                                <h5>Others</h5>
                                <li class="list-group-item">{{(($customer_classification->others !== null) ? $customer_classification->others : 0) }}</li>

                            </ul> 
                          
                    </div>

                    <div class="col-md-4">
                            <ul class="list-group">

                                    <h5>Internal</h5>
                                    <li class="list-group-item">{{(($customer_classification->internal !== null) ? $customer_classification->internal : 0) }}</li>
            
                                    <h5>Private Organization</h5>
                                    <li class="list-group-item">{{(($customer_classification->private_organization !== null) ? $customer_classification->private_organization : 0) }}</li>
    
                                    <h5>Please Specify</h5>
                                    <li class="list-group-item">{{(($customer_classification->others_specify !== null) ? $customer_classification->others_specify : 'NULL') }}</li>
    
                                </ul> 
                    </div>

                </div>

                <div class="row">
                        <div class="col-md-12">
                           
                            <h3 class="page-header">Customer Address</h3>
                        </div>
                    </div>

                    <div id="addresses" class="row">

                        @for($i = 0; $i < count($customer_addresses); $i++)
                            <div id="address">
                                <div class="col-md-6">
                                        <ul class="list-group">

                                            <h5>Internal</h5>
                                            <li class="list-group-item">{{$customer_addresses[$i]->address}}</li>
                
                                        </ul> 
                                </div>
                                <div class="col-md-6">
                                    <ul class="list-group">
                                        <h5>Number of Customers</h5>
                                        <li class="list-group-item">{{$customer_addresses[$i]->count}}</li>
                                    </ul>
                                </div>
                                
                            </div>
                        @endfor

                    </div>

                    <br>

                    <div class="row">
                        <div class="col-md-12">
                          
                            <h3 class="page-header">Services Offerred</h3>
                        </div>
                    </div>

                    <div id="services" class="row">
                        @for($i = 0; $i < count($customer_services_offered); $i++)
                            <div id="service">
                                    <div class="col-md-6">
                                            <ul class="list-group">
    
                                                <h5>Internal</h5>
                                                <li class="list-group-item">{{$customer_services_offered[$i]->service_name}}</li>
                    
                                            </ul> 
                                    </div>
                                    <div class="col-md-6">
                                            <ul class="list-group">
                                                <h5>Number of Customers</h5>
                                                <li class="list-group-item">{{$customer_services_offered[$i]->count}}</li>
                                            </ul>
                                    </div>
                            </div>
                        @endfor  
                        
                    </div>

                    <br>


                <div class="row">
                        <div class="col-md-12">
                            <h3 class="page-header">Customer Rating</h3>
                        </div>
                    </div>

                    <div class="row">
                        
                        <div class="col-md-4">
                            <ul class="list-group">
                                <h5>No. of rating of 5</h5>
                                <li class="list-group-item">{{(($customer_rating->five_star !== null) ? $customer_rating->five_star : '0') }}</li>
                            </ul> 
                           
                                
                        </div>

                        <div class="col-md-4">
                            <ul class="list-group">
                                <h5>No. of rating of 4</h5>
                                <li class="list-group-item">{{(($customer_rating->four_star !== null) ? $customer_rating->four_star : '0')}}</li>
                            </ul> 
                                
                        </div>

                        <div class="col-md-4">
                            <ul class="list-group">
                                <h5>No. of rating of 3 and below</h5>
                                <li class="list-group-item">{{(($customer_rating->three_below !== null) ? $customer_rating->three_below : '0')}}</li>
                            </ul> 
                        </div>

                        </div>

                        <div class="row">
                                <div class="col-md-12">
                                    <h3 class="page-header">Overall Rating</h3>
                                </div>
                            </div>
    
                    <div class="row">

                        <div class="col-md-3">
                            <ul class="list-group">
                                <h5>Response Delivery</h5>
                                <li class="list-group-item">{{$customer_overall_rating->response_delivery}}</li>
                            </ul> 
                                
                        </div>

                        <div class="col-md-3">
                            <ul class="list-group">
                                <h5>Work Quality</h5>
                                <li class="list-group-item">{{$customer_overall_rating->work_quality}}</li>
                            </ul> 
                               
                                
                        </div>

                        <div class="col-md-3">
                            <ul class="list-group">
                                <h5>Personnels Quality</h5>
                                <li class="list-group-item">{{$customer_overall_rating->personnels_quality}}</li>
                            </ul> 
                               
                        </div>
                        <div class="col-md-3">
                                <ul class="list-group">
                                    <h5>Overall Rating</h5>
                                    <li class="list-group-item">{{$customer_overall_rating->overall_rating}}</li>
                                </ul> 
                                
                        </div>

                    </div>

                    <ul class="list-group">
                        <h5>Comments and Suggestions</h5>
                        <li class="list-group-item">{{$csm->comments}}</li>

                        <h5>Supporting Documents</h5>
                        <li class="list-group-item">{{$csm->other_files}}</li>
                    </ul> 

                        
                    
                  
                

        </div>
        <div class="col-md-1"> </div>
    </div>
</div>



@endsection