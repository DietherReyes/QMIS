@extends('layouts.app')

@section('content')

<script type="application/javascript"> 
   
    // upload name for single file
    function uploadName(input_id , textarea_id){
        var input = document.getElementById(input_id);
        var textarea = document.getElementById(textarea_id);
        var file_name = input.files[0].name;
        textarea.value = file_name;
    }

</script>

<div class="container-fluid">
        <div class="row">
    
            @include('include.add_qmsd_sidebar')
            <div class="col-md-10  main">

                <ol class="breadcrumb">
                    <li><a href="/">Home</a></li>
                    <li><a href="/qmsd">Quality Management System Documentation</a></li>
                    <li class="active">Add QMSD </li>
                </ol>
                    
                <div style="float:right">
                    <a class="btn btn-primary btn-md"  href="/qmsd">BACK</a>
                </div>
                    
                <h1 class="page-header"> Add QMSD </h1>
                {!! Form::open(['action' => 'QualityManualDocumentationsController@store', 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}

                    <div class="row">
                    
                        <div class="col-md-6">
                            

                            <div class="form-group{{ $errors->has('document_code') ? ' has-error' : '' }}">
                                {{Form::label('document_code', 'Document Code')}}
                                {{Form::text('document_code', '', ['class' => 'form-control'])}}
        
                                @if ($errors->has('document_code'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('document_code') }}</strong>
                                    </span>
                                @endif
                                
                            </div>

                        

                            <div class="form-group{{ $errors->has('subject') ? ' has-error' : '' }}">
                                {{Form::label('subject', 'Subject')}}
                                {{Form::text('subject', '', ['class' => 'form-control'])}}
        
                                @if ($errors->has('subject'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('subject') }}</strong>
                                    </span>
                                @endif
                                
                            </div>

                            
                            
                            <div class="form-group">
                                {{Form::label('effectivity_date', 'Effectivity Date')}}
                                {{ Form::date('effectivity_date', new \DateTime(), ['class' => 'form-control']) }}
                            </div>

                            

                        </div>

                        <div class="col-md-6">


                            <div class="form-group{{ $errors->has('section') ? ' has-error' : '' }}">
                                    {{Form::label('section', 'Section Name')}}
                                    {{Form::select('section', $data, null, ['class' => 'form-control', 'placeholder' => 'Click to select section name'])}}
        
                                @if ($errors->has('section'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('section') }}</strong>
                                    </span>
                                @endif
                                
                            </div>


                            <div class="form-group{{ $errors->has('revision_number') ? ' has-error' : '' }}">
                                    {{Form::label('revision_number', 'Revision Number')}}
                                {{Form::text('revision_number', '', ['class' => 'form-control'])}}
        
                                @if ($errors->has('revision_number'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('revision_number') }}</strong>
                                    </span>
                                @endif
                                
                            </div>


                            <div class="form-group{{ $errors->has('page_number') ? ' has-error' : '' }}">
                                {{Form::label('page_number', 'Page Number')}}
                                {{Form::text('page_number', '', ['class' => 'form-control'])}}
        
                                @if ($errors->has('page_number'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('page_number') }}</strong>
                                    </span>
                                @endif
                                
                            </div>

                            

                            
                
                        </div>

                    </div>
                    <div class="form-group{{ $errors->has('quality_manual_doc') ? ' has-error' : '' }}">
                        {{Form::label('quality_manual_doc', 'Quality Manual Document')}}
                        {{Form::file('quality_manual_doc', [ 'class' => 'hidden', 'id' => 'quality_manual_doc' ,'onChange' => 'uploadName(this.id, \'quality_manual_doc_text\')'])}}
                        <div class="row">
                            <div class="col-md-10">
                                {{Form::text('quality_manual_doc_text', '', ['class' => 'form-control', 'id' => 'quality_manual_doc_text', 'disabled'])}}
                            </div>
                            <div class="col-md-2">
                                {{Form::label('quality_manual_doc', 'Upload File', ['class' => 'file-input', 'for' => 'quality_manual_doc'])}}
                            </div> 
                        </div>
                        @if ($errors->has('quality_manual_doc'))
                            <span class="help-block">
                                <strong>{{ $errors->first('quality_manual_doc') }}</strong>
                            </span>
                        @endif
                    </div>

                    {{Form::submit('ADD', ['class'=>'btn btn-primary submit-btn'])}}

                {!! Form::close() !!}
                

        </div>
       
    </div>
</div>



@endsection