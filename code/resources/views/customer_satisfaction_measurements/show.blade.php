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

                                <h5><strong>Functional Unit </strong></h5>
                                <li class="list-group-item">{{$csm->functional_unit}}</li>
        
                                <h5><strong>Total Customer </strong></h5>
                                <li class="list-group-item">{{$csm->total_customer}}</li>

                            </ul>                           

                    </div>

                    <div class="col-md-3">

                        <ul class="list-group">

                            <h5><strong>CSM Year </strong></h5>
                            <li class="list-group-item">{{$csm->year}}</li>
    
                            <h5><strong>Total Male </strong></h5>
                            <li class="list-group-item">{{$csm->total_male}}</li>

                        </ul> 
            
                    </div>

                    <div class="col-md-3">
                            <ul class="list-group">

                                <h5><strong>Quarter </strong></h5>
                                <li class="list-group-item">{{$csm->quarter}}</li>
        
                                <h5><strong>Total Female </strong></h5>
                                <li class="list-group-item">{{$csm->total_female}}</li>

                            </ul> 
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <h3 class="page-header">Customer Classification</h3>
                    </div>
                </div>

                <div id="classifications" class="row">

                    @for($i = 0; $i < count($customer_classifications); $i++)
                        <div id="address">
                            <div class="col-md-6">
                                    <ul class="list-group">

                                        <h5><strong>Classification </strong></h5>
                                        <li class="list-group-item">{{$customer_classifications[$i]}}</li>
            
                                    </ul> 
                            </div>
                            <div class="col-md-6">
                                <ul class="list-group">
                                    <h5><strong>Number of Customers </strong></h5>
                                    <li class="list-group-item">{{$customer_classifications_count[$i]}}</li>
                                </ul>
                            </div>
                            
                        </div>
                    @endfor

                </div>



                <div id="other_classifications" class="row">

                    @for($i = 0; $i < count($customer_other_classifications); $i++)
                        <div id="address">
                            <div class="col-md-6">
                                    <ul class="list-group">

                                        <h5><strong>Classification </strong></h5>
                                        <li class="list-group-item">{{$customer_other_classifications[$i]->name}}</li>
            
                                    </ul> 
                            </div>
                            <div class="col-md-6">
                                <ul class="list-group">
                                    <h5><strong>Number of Customers </strong></h5>
                                    <li class="list-group-item">{{$customer_other_classifications[$i]->count}}</li>
                                </ul>
                            </div>
                            
                        </div>
                    @endfor

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

                                        <h5><strong>City/Province </strong></h5>
                                        <li class="list-group-item">{{$customer_addresses[$i]->address}}</li>
            
                                    </ul> 
                            </div>
                            <div class="col-md-6">
                                <ul class="list-group">
                                    <h5><strong>Number of Customers </strong></h5>
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
    
                                                <h5><strong>Service Offered </strong></h5>
                                                <li class="list-group-item">{{$customer_services_offered[$i]->service_name}}</li>
                    
                                            </ul> 
                                    </div>
                                    <div class="col-md-6">
                                            <ul class="list-group">
                                                <h5><strong>Number of Customers </strong></h5>
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
                                
                                <h5><strong>No. of rating of 5 </strong></h5>
                                <li class="list-group-item">{{(($customer_rating->five_star !== null) ? $customer_rating->five_star : '0') }}</li>
                            </ul> 
                           
                                
                        </div>

                        <div class="col-md-4">
                            <ul class="list-group">
                                    
                                <h5><strong>No. of rating of 4 </strong></h5>
                                <li class="list-group-item">{{(($customer_rating->four_star !== null) ? $customer_rating->four_star : '0')}}</li>
                            </ul> 
                                
                        </div>

                        <div class="col-md-4">
                            <ul class="list-group">
                                <h5><strong>No. of rating of 3 and below </strong></h5>
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
                                <h5><strong>Response Delivery </strong></h5>
                                <li class="list-group-item">{{$customer_overall_rating->response_delivery}}</li>
                            </ul> 
                                
                        </div>

                        <div class="col-md-3">
                            <ul class="list-group">
                                <h5><strong>Work Quality </strong></h5>
                                <li class="list-group-item">{{$customer_overall_rating->work_quality}}</li>
                            </ul> 
                               
                                
                        </div>

                        <div class="col-md-3">
                            <ul class="list-group">
                                <h5><strong>Personnels Quality </strong></h5>
                                <li class="list-group-item">{{$customer_overall_rating->personnels_quality}}</li>
                            </ul> 
                               
                        </div>
                        <div class="col-md-3">
                                <ul class="list-group">
                                    <h5><strong>Overall Rating </strong></h5>
                                    <li class="list-group-item">{{$customer_overall_rating->overall_rating}}</li>
                                </ul> 
                                
                        </div>

                    </div>

                    <ul class="list-group">

                        @if($csm->comments !== null)
                            <h5><strong>Comments and Suggestions </strong></h5>
                            {{Form::textarea('comments', $csm->comments, ['class' => 'form-control', 'disabled'])}}
                        @endif

                        
                        <h5><strong>Supporting Documents </strong></h5>
                        <li class="list-group-item">{{$csm->supporting_documents}}</li>
                    </ul> 

                        
                    
                  
                

        </div>
        <div class="col-md-1"> </div>
    </div>
</div>



@endsection