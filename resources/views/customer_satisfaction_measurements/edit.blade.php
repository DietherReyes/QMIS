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

<div class="container-fluid">
    <div class="row">
        
        <div class="col-md-1"></div>

        <div class="col-md-10 main">

                <ol class="breadcrumb">
                    <li><a href="/">Home</a></li>
                    <li><a href="/csm">Customer Satisfaction Measurement</a></li>
                    <li class="active">Edit CSM </li>
                </ol>
                    
                <div style="float:right">
                    <a class="btn btn-primary btn-md"  href="/csm">BACK</a>
                </div>
                    
                <h1 class="page-header"> Edit CSM </h1>
                {!! Form::open(['action' => ['CustomerSatisfactionMeasurementsController@update', $csm->id], 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}

                    <div class="row">
                    
                        <div class="col-md-6">
                            
                            <div class="form-group">
                                {{Form::label('functional_unit', 'Functional Unit')}}
                                {{Form::select('functional_unit', $data, $csm->functional_unit, ['class' => 'form-control'])}}
                            </div>

                            <div class="form-group">
                                {{Form::label('total_customer', 'Total Customer')}}
                                {{Form::text('total_customer', $csm->total_customer, ['class' => 'form-control'])}}
                            </div>                            

                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                {{Form::label('year', 'CSM Year')}}
                                {{Form::text('year', $csm->year, ['class' => 'form-control'])}}
                            </div>

                            <div class="form-group">
                                {{Form::label('total_male', 'Total Male')}}
                                {{Form::text('total_male', $csm->total_male, ['class' => 'form-control'])}}
                            </div>
                
                        </div>

                        <div class="col-md-3">
                                <div class="form-group">
                                    {{Form::label('quarter', 'Quarter')}}
                                    {{Form::select('quarter', $quarter, $csm->quarter, ['class' => 'form-control', 'placeholder' => 'Click to select Quarter'])}}
                                </div>
    
                                <div class="form-group">
                                    {{Form::label('total_female', 'Total Female')}}
                                    {{Form::text('total_female', $csm->total_female, ['class' => 'form-control'])}}
                                </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6"> 
                                <h3>Customer Classification</h3>
                        </div>
                        <div class="col-md-6"> 
                                
                        </div>
                    </div>

                    <div class="row">

                        <div class="col-md-4">
                            <div class="form-group">
                                {{Form::label('student', 'Student')}}
                                {{Form::text('customer_classification[student]', $customer_classification->student, ['class' => 'form-control'])}}
                            </div>
                            <div class="form-group">
                                {{Form::label('business', 'Business')}}
                                {{Form::text('customer_classification[business]', $customer_classification->business, ['class' => 'form-control'])}}
                            </div>
                            <div class="form-group">
                                {{Form::label('entrepreneur', 'Entrepreneur')}}
                                {{Form::text('customer_classification[entrepreneur]', $customer_classification->entrepreneur, ['class' => 'form-control'])}}
                            </div>      
                        </div>

                        <div class="col-md-4">
                                <div class="form-group">
                                    {{Form::label('government_employee', 'Government Employee')}}
                                    {{Form::text('customer_classification[government_employee]', $customer_classification->government_employee, ['class' => 'form-control'])}}
                                </div>
                                <div class="form-group">
                                    {{Form::label('homemaker', 'Homemaker')}}
                                    {{Form::text('customer_classification[homemaker]', $customer_classification->homemaker, ['class' => 'form-control'])}}
                                </div>
                                <div class="form-group">
                                    {{Form::label('others', 'Others')}}
                                    {{Form::text('customer_classification[others]', $customer_classification->others, ['class' => 'form-control'])}}
                                </div>  
                        </div>

                        <div class="col-md-4">
                                <div class="form-group">
                                    {{Form::label('internal', 'Internal')}}
                                    {{Form::text('customer_classification[internal]', $customer_classification->internal, ['class' => 'form-control'])}}
                                </div>
                                <div class="form-group">
                                    {{Form::label('private_organization', 'Private Organization')}}
                                    {{Form::text('customer_classification[private_organization]', $customer_classification->private_organization, ['class' => 'form-control'])}}
                                </div>
                                <div class="form-group">
                                    {{Form::label('others_specify', 'Please Specify')}}
                                    {{Form::text('customer_classification[others_specify]', $customer_classification->others_specify, ['class' => 'form-control'])}}
                                </div>  
                        </div>

                    </div>


                    <div class="row">
                        <div class="col-md-6"> 
                                <h3>Customer Rating</h3>
                        </div>
                        <div class="col-md-6"> 
                                
                        </div>
                    </div>

                    <div class="row">

                        <div class="col-md-4">
                            <div class="form-group">
                                {{Form::label('five_star', 'No. of rating of 5')}}
                                {{Form::text('customer_rating[five_star]', $customer_rating->five_star, ['class' => 'form-control'])}}
                            </div>
                                
                        </div>

                        <div class="col-md-4">
                                <div class="form-group">
                                    {{Form::label('four_star', 'No. of rating of 4')}}
                                    {{Form::text('customer_rating[four_star]', $customer_rating->four_star, ['class' => 'form-control'])}}
                                </div>
                                
                        </div>

                        <div class="col-md-4">
                                <div class="form-group">
                                    {{Form::label('three_below', 'No. of rating of 3 and below')}}
                                    {{Form::text('customer_rating[three_below]', $customer_rating->three_below, ['class' => 'form-control'])}}
                                </div>
                                
                        </div>

                        </div>

                    <div class="row">
                        <div class="col-md-6"> 
                                <h3>Overall Rating</h3>
                        </div>
                        <div class="col-md-6"> 
                                
                        </div>
                    </div>
    
                    <div class="row">

                        <div class="col-md-3">
                            <div class="form-group">
                                {{Form::label('response_delivery', 'Response Delivery')}}
                                {{Form::text('response_delivery', $customer_overall_rating->response_delivery, ['class' => 'form-control'])}}
                            </div>
                                
                        </div>

                        <div class="col-md-3">
                                <div class="form-group">
                                    {{Form::label('work_quality', 'Work Quality')}}
                                    {{Form::text('work_quality', $customer_overall_rating->work_quality, ['class' => 'form-control'])}}
                                </div>
                                
                        </div>

                        <div class="col-md-3">
                                <div class="form-group">
                                    {{Form::label('personnels_quality', 'Personnels Quality')}}
                                    {{Form::text('personnels_quality', $customer_overall_rating->personnels_quality, ['class' => 'form-control'])}}
                                </div>
                                
                        </div>
                        <div class="col-md-3">
                                <div class="form-group">
                                    {{Form::label('overall_rating', 'Overall Rating')}}
                                    {{Form::text('overall_rating', $customer_overall_rating->overall_rating, ['class' => 'form-control'])}}
                                </div>
                                
                        </div>

                    </div>

                    <div class="form-group">
                        {{Form::label('comments', 'Comments and Suggestions')}}
                        {{Form::text('comments', $csm->comments, ['class' => 'form-control'])}}
                    </div>

                    <div class="form-group">
                        {{Form::label('other_files', 'Supporting Documents')}}
                        {{Form::file('other_files[]', [ 'class' => 'hidden', 'multiple' => 'multiple','id' => 'other_files' ,'onChange' => 'uploadNames(this.id, \'other_files_text\')'])}}
                        <div class="row">
                            <div class="col-md-10">
                                {{Form::text('other_files_text', $csm->other_files, ['class' => 'form-control', 'id' => 'other_files_text', 'disabled'])}}
                            </div>
                            <div class="col-md-2">
                                {{Form::label('other_files', 'Upload File(s)', ['class' => 'file-input', 'for' => 'other_files'])}}
                            </div> 
                        </div>
                    </div>
                        
                    
                    {{Form::hidden('_method','PUT')}}
                    {{Form::submit('Submit', ['class'=>'btn btn-primary'])}}

                {!! Form::close() !!}
                

        </div>
        <div class="col-md-1"> </div>
    </div>
</div>



@endsection