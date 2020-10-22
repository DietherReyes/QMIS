@extends('layouts.app')

@section('content')

<script type="application/javascript"> 
   

    // upload names for multiple files
    function uploadNames(input_id , textarea_id){
        var input = document.getElementById(input_id);
        var textarea = document.getElementById(textarea_id);
        var files = input.files;
        var file_name = '';

        for(var i = 0; i < files.length; i++){
            file_name += files[i].name + ', '
        }
        
        textarea.value = file_name;
    }

</script>

<script type="application/javascript"> 
   
    $(document).ready(function(){



        $("#add_address").click(function(e){
            event.preventDefault();
            $("#addresses").append('<div id="address">' +
                                        '<div class="col-md-5"> {{Form::label("address", "Province/City Name")}} {{Form::select("address[]", $addresses, null, ["class" => "form-control", "placeholder" => "Click to select address"])}} </div>' +
                                        '<div class="col-md-5"> {{Form::label("address_count", "Number of Customers")}} {{Form::number("address_count[]", "", ["class" => "form-control"])}} </div>' +
                                        '<div class="col-md-2"> <button class="btn btn-danger btn-md delete-address" id="delete">DELETE </button> </div>' +  
                                    '</div>');

           
            
        });

        $("#add_service").click(function(e){
            event.preventDefault();
            $("#services").append('<div id="service">' +
                                        '<div class="col-md-5"> {{Form::label("service", "Service Offered")}} {{Form::select("service[]", $services, null, ["class" => "form-control", "placeholder" => "Click to select service"])}} </div>' +
                                        '<div class="col-md-5"> {{Form::label("service_count", "Number of Customers")}} {{Form::number("service_count[]", "", ["class" => "form-control"])}} </div>' +
                                        '<div class="col-md-2"> <button class="btn btn-danger btn-md delete-address" id="delete">DELETE </button> </div>' +  
                                    '</div>');
            
        });


        $("#add_other_classification").click(function(e){
            event.preventDefault();
            $("#other_classifications").append('<div id="other_classification">' +
                                        '<div class="col-md-5"> {{Form::label("other_classification", "Other Classification")}} {{Form::text("other_classification[]", "", ["class" => "form-control"])}} </div>' +
                                        '<div class="col-md-5"> {{Form::label("other_classification_count", "Number of Customers")}} {{Form::number("other_classification_count[]", "", ["class" => "form-control"])}} </div>' +
                                        '<div class="col-md-2"> <button class="btn btn-danger btn-md delete-address" id="delete">DELETE </button> </div>' +  
                                    '</div>');
            
        });

        $("#add_classification").click(function(e){
            event.preventDefault();
            $("#classifications").append('<div id="classification">' +
                                                '<div class="col-md-5"> {{Form::label("classification", "Classification")}} {{Form::select("classification[]", $classifications, null, ["class" => "form-control", "placeholder" => "Click to select classification"])}} </div>' +
                                                '<div class="col-md-5"> {{Form::label("classification_count", "Number of Customers")}} {{Form::number("classification_count[]", "", ["class" => "form-control"])}} </div>' +
                                                '<div class="col-md-2"> <button class="btn btn-danger btn-md delete-address" id="delete">DELETE </button> </div>' +  
                                         '</div>');
            
        });

        $("body").on("click", "#delete", function(e){
            event.preventDefault();
            $(this).parent('div').parent('div').remove();
        });
        

    });
</script>

<div class="container-fluid">
    <div class="row">
        
        @include('include.add_csm_sidebar')

        <div class="col-md-9 main">

                <ol class="breadcrumb">
                    <li><a href="/">Home</a></li>
                    <li><a href="/csm">Customer Satisfaction Measurement</a></li>
                    <li class="active">Add CSM </li>
                </ol>
                    
                <div style="float:right">
                    <a class="btn btn-primary btn-md"  href="/csm">BACK</a>
                </div>
                    
                <h1 class="page-header"> Add CSM </h1>
                {!! Form::open(['action' => 'CustomerSatisfactionMeasurementsController@store', 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}

                    
                    
                    <div class="row">

                        <div class="col-md-12">
                            @if ($errors->has('error_duplicate'))
                                <div class="alert alert-danger">
                                    <ul>
                                        <li>The system does not allow duplicate entry.".</li>
                                        <li>Please check the details of the 'Functional Unit', 'Year' and 'Quarter' again.</li>
                                    </ul>
                                </div>
                            @endif
                        </div>
                        
                        <div class="col-md-6">  
                            
                    
                            <div class="form-group{{ $errors->has('functional_unit') ? ' has-error' : '' }}">
                                {{Form::label('functional_unit', 'Functional Unit')}}
                                {{Form::select('functional_unit', $data, null, ['class' => 'form-control', 'placeholder' => 'Click to select functional unit'])}}
        
                                @if ($errors->has('functional_unit'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('functional_unit') }}</strong>
                                    </span>
                                @endif
                                
                            </div>

                         
                            
                            <div class="form-group{{ $errors->has('total_customer') ? ' has-error' : '' }}">
                                {{Form::label('total_customer', 'Total Customer')}}
                                {{Form::text('total_customer', '', ['class' => 'form-control'])}}
        
                                @if ($errors->has('total_customer'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('total_customer') }}</strong>
                                    </span>
                                @endif

                                
                            </div>

                        </div>

                        <div class="col-md-3">
                           

                            <div class="form-group{{ $errors->has('year') ? ' has-error' : '' }}">
                                {{Form::label('year', 'CSM Year')}}
                                {{Form::text('year', '', ['class' => 'form-control'])}}
        
                                @if ($errors->has('year'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('year') }}</strong>
                                    </span>
                                @endif
                                
                            </div>

                            <div class="form-group {{ $errors->has('total_male') ? ' has-error' : '' }}  {{ $errors->has('error_gender_count') ? ' has-error' : '' }}">
                                {{Form::label('total_male', 'Total Male')}}
                                {{Form::text('total_male', '', ['class' => 'form-control'])}}
        
                                @if ($errors->has('total_male'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('total_male') }}</strong>
                                    </span>
                                @endif

                                @if ($errors->has('error_gender_count')) 
                                    <span class="help-block">
                                        <strong> {{ $errors->first('error_gender_count') }}</strong>
                                    </span>
                                @endif
                                
                            </div>
                
                        </div>

                        <div class="col-md-3">
                                

                                <div class="form-group{{ $errors->has('quarter') ? ' has-error' : '' }}">
                                    {{Form::label('quarter', 'Quarter')}}
                                    {{Form::select('quarter', $quarter, null, ['class' => 'form-control', 'placeholder' => 'Click to select Quarter'])}}
            
                                    @if ($errors->has('quarter'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('quarter') }}</strong>
                                        </span>
                                    @endif
                                    
                                </div>
    
                               

                                <div class="form-group{{ $errors->has('total_female') ? ' has-error' : '' }}  {{ $errors->has('error_gender_count') ? ' has-error' : '' }}">
                                    {{Form::label('total_female', 'Total Female')}}
                                    {{Form::text('total_female', '', ['class' => 'form-control'])}}
            
                                    @if ($errors->has('total_female'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('total_female') }}</strong>
                                        </span>
                                    @endif

                                    @if ($errors->has('error_gender_count')) 
                                        <span class="help-block">
                                            <strong> {{ $errors->first('error_gender_count') }}</strong>
                                        </span>
                                    @endif
                                    
                                </div>
                        </div>
                    </div>

                    <br>

                    <div class="row">
                        <div class="col-md-12">
                            <div style="float:right">
                                    <button class="btn btn-primary btn-md" id="add_classification"> ADD </button>
                                    <button class="btn btn-primary btn-md" id="add_other_classification"> ADD OTHERS </button>
                            </div>
                            <h3 class="page-header">Customer Classification</h3>
                            @if ($errors->has('classification.*') or $errors->has('classification_count.*'))
                                <div class="alert alert-danger">
                                    <ul>
                                       <li>The field "Classification" is required.</li>
                                       <li>The filed "Number of Customers" reqiures numeric input greater than 0.</li>
                                    </ul>
                                </div>
                            @endif
                            @if ($errors->has('other_classification.*') or $errors->has('other_classification_count.*'))
                                <div class="alert alert-danger">
                                    <ul>
                                       <li>The field "Other Classification" is required with the field "Number of Customers".</li>
                                       <li>The filed "Number of Customers" reqiures numeric input greater than 0.</li>
                                    </ul>
                                </div>
                            @endif
                            @if ($errors->has('error_classification_count')) 
                                <div class="alert alert-danger">
                                    <ul>
                                        <li>{{$errors->first('error_classification_count')}}</li>
                                    </ul>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div id="classifications" class="row">
                         
                            <div id="classification">
                                <div class="col-md-5">
                                    {{Form::label('classification', 'Classification')}}
                                    {{Form::select("classification[]", $classifications, null, ["class" => "form-control", "placeholder" => "Click to select classification"])}}
                                </div>


                                <div class="col-md-5">
                                    {{Form::label('classification_count', 'Number of Customers')}}
                                    {{Form::number('classification_count[]', '', ['class' => 'form-control'])}}
                                </div>

                            </div>
                        
                    </div>


                    <div id="other_classifications" class="row">
                         
                        
                    
                    </div>

                    <br>

                    <div class="row">
                        <div class="col-md-12">
                            <div style="float:right">
                                    <button class="btn btn-primary btn-md" id="add_address"> ADD </button>
                            </div>
                            <h3 class="page-header">Customer Address</h3>
                            @if ($errors->has('address.*') or $errors->has('address_count.*'))
                                <div class="alert alert-danger">
                                    <ul>
                                        <li>The field "Province/City Name" is required.</li>
                                        <li>The filed "Number of Customers" reqiures numeric input greater than 0.</li>
                                    </ul>
                                </div>
                            @endif
                            @if ($errors->has('error_address_count')) 
                                <div class="alert alert-danger">
                                    <ul>
                                        <li>{{$errors->first('error_address_count')}}</li>
                                    </ul>
                                </div>
                            @endif

                        </div>
                        
                    </div>

                    <div id="addresses" class="row">
                         
                            <div id="address">
                                <div class="col-md-5">

                                    {{Form::label('address', 'Province/City Name')}}
                                    {{Form::select('address[]', $addresses, null, ['class' => 'form-control', 'placeholder' => 'Click to select address'])}}
                                    
                                </div>


                                <div class="col-md-5">
                                    {{Form::label('address_count', 'Number of Customers')}}
                                    {{Form::number('address_count[]', '', ['class' => 'form-control'])}}
                                </div>

                            </div>
                        
                    </div>

                    <br>

                    <div class="row">
                        <div class="col-md-12">
                            <div style="float:right">
                                    <button class="btn btn-primary btn-md" id="add_service"> ADD </button>
                            </div>
                            <h3 class="page-header">Services Offerred</h3>
                            @if ($errors->has('service.*') or $errors->has('service_count.*')) 
                                <div class="alert alert-danger">
                                    <ul>
                                        <li>The field "Service Offered" is required.</li>
                                        <li>The filed "Number of Customers" reqiures numeric input greater than 0.</li>
                                    </ul>
                                </div>
                            @endif

                            @if ($errors->has('error_service_count')) 
                                <div class="alert alert-danger">
                                    <ul>
                                        <li>{{$errors->first('error_service_count')}}</li>
                                    </ul>
                                </div>
                            @endif

                            
                        </div>
                        
                    </div>

                    <div id="services" class="row">
                            
                            <div id="service">
                                <div class="col-md-5">
                                    {{Form::label('service', 'Service Offered')}}
                                    {{Form::select('service[]', $services, null, ['class' => 'form-control', 'placeholder' => 'Click to select service'])}}
                                    
                                </div>

                              
                                <div class="col-md-5">
                                    {{Form::label('service_count', 'Number of Customers')}}
                                    {{Form::number('service_count[]', '', ['class' => 'form-control'])}}
                                </div>
                                
                            </div>
                        
                    </div>

                    <br>
                    
                    <div class="row">
                            <div class="col-md-12"> 
                                    <h3 class="page-header">Customer Rating</h3>
                            </div>
                    </div>

                    <div class="row">

                        <div class="col-md-4">
                            
                            
                            <div class="form-group{{ $errors->has('five_star') ? ' has-error' : '' }}">
                                {{Form::label('five_star', 'No. of rating of 5')}}
                                {{Form::text('five_star', '', ['class' => 'form-control'])}}
        
                                @if ($errors->has('five_star'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('five_star') }}</strong>
                                    </span>
                                @endif
                                
                            </div>
                        </div>

                        <div class="col-md-4">
                               

                                <div class="form-group{{ $errors->has('four_star') ? ' has-error' : '' }}">
                                    {{Form::label('four_star', 'No. of rating of 4')}}
                                    {{Form::text('four_star', '', ['class' => 'form-control'])}}
            
                                    @if ($errors->has('four_star'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('four_star') }}</strong>
                                        </span>
                                    @endif
                                    
                                </div>
                                
                        </div>

                        <div class="col-md-4">
                                

                                <div class="form-group{{ $errors->has('three_below') ? ' has-error' : '' }}">
                                    {{Form::label('three_below', 'No. of rating of 3 and below')}}
                                    {{Form::text('three_below', '', ['class' => 'form-control'])}}
            
                                    @if ($errors->has('three_below'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('three_below') }}</strong>
                                        </span>
                                    @endif
                                    
                                </div>
                                
                        </div>

                        </div>

                    <div class="row">
                            <div class="col-md-12"> 
                                    <h3 class="page-header">Overall Rating</h3>
                            </div>
                    </div>
    
                    <div class="row">

                        <div class="col-md-3">
                            
                            <div class="form-group{{ $errors->has('response_delivery') ? ' has-error' : '' }}">
                                {{Form::label('response_delivery', 'Response Delivery')}}
                                {{Form::text('response_delivery', '', ['class' => 'form-control'])}}
        
                                @if ($errors->has('response_delivery'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('response_delivery') }}</strong>
                                    </span>
                                @endif
                                
                            </div>
                                
                        </div>

                        <div class="col-md-3">
                                

                                <div class="form-group{{ $errors->has('work_quality') ? ' has-error' : '' }}">
                                    {{Form::label('work_quality', 'Work Quality')}}
                                    {{Form::text('work_quality', '', ['class' => 'form-control'])}}
            
                                    @if ($errors->has('work_quality'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('work_quality') }}</strong>
                                        </span>
                                    @endif
                                    
                                </div>
                                
                        </div>

                        <div class="col-md-3">
                               

                                <div class="form-group{{ $errors->has('personnels_quality') ? ' has-error' : '' }}">
                                    {{Form::label('personnels_quality', 'Personnels Quality')}}
                                    {{Form::text('personnels_quality', '', ['class' => 'form-control'])}}
            
                                    @if ($errors->has('personnels_quality'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('personnels_quality') }}</strong>
                                        </span>
                                    @endif
                                    
                                </div>
                                
                        </div>
                        <div class="col-md-3">
                                

                                <div class="form-group{{ $errors->has('overall_rating') ? ' has-error' : '' }}">
                                    {{Form::label('overall_rating', 'Overall Rating')}}
                                    {{Form::text('overall_rating', '', ['class' => 'form-control'])}}
            
                                    @if ($errors->has('overall_rating'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('overall_rating') }}</strong>
                                        </span>
                                    @endif
                                    
                                </div>
                                
                        </div>

                    </div>

                    <div class="form-group">
                        {{Form::label('comments', 'Comments and Suggestions (Optional)')}}
                        {{Form::textarea('comments', '', ['class' => 'form-control'])}}
                    </div>

                    

                    <div class="form-group {{ $errors->has('supporting_documents') ? ' has-error' : '' }}">
                        {{Form::label('supporting_documents', 'Supporting Documents')}}
                        {{Form::file('supporting_documents[]', [ 'class' => 'hidden', 'multiple' => 'multiple','id' => 'supporting_documents' ,'onChange' => 'uploadNames(this.id, \'supporting_documents_text\')'])}}
                        <div class="row">
                            <div class="col-md-10">
                                {{Form::text('supporting_documents_text', '', ['class' => 'form-control', 'id' => 'supporting_documents_text', 'disabled'])}}
                            </div>
                            <div class="col-md-2">
                                {{Form::label('supporting_documents', 'Upload File(s)', ['class' => 'file-input', 'for' => 'supporting_documents'])}}
                            </div> 
                        </div>
                        @if ($errors->has('supporting_documents'))
                            <span class="help-block">
                                <strong>{{ $errors->first('supporting_documents') }}</strong>
                            </span>
                        @endif
                    </div>
                        
                    

                    {{Form::submit('ADD', ['class'=>'btn btn-primary submit-btn'])}}

                {!! Form::close() !!}
                

        </div>
        <div class="col-md-1"> </div>
    </div>
</div>



@endsection