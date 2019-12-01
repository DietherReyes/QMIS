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

        @include('include.signatories_sidebar')

        
        <div class="col-md-9  main">
                <ol class="breadcrumb">
                    <li><a href="/">Home</a></li>
                    <li><a href="/sysmg/signatories">Signatories</a></li>
                    <li class="active"> Add Signatory </li>
                </ol>
                
                <div style="float:right">
                        
                        <a class="btn btn-primary btn-md"  href="/sysmg/signatories">BACK</a>
                </div>
                
                <h1 class="page-header"> Add Signatory</h1>
                {!! Form::open(['action' => 'SignatoriesController@store', 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}


                    <div class="form-group">
                        {{Form::label('name', 'Name')}}
                        {{Form::text('name', '', ['class' => 'form-control'])}}
                    </div>

                    <div class="form-group">
                        {{Form::label('position', 'Position')}}
                        {{Form::text('position', '', ['class' => 'form-control'])}}
                    </div>

                    <div class="form-group">
                        {{Form::label('signature_photo', 'Signature(.png)')}}
                        {{Form::file('signature_photo', [ 'class' => 'hidden', 'id' => 'signature_photo' ,'onChange' => 'uploadName(this.id, \'signature_photo_text\')'])}}
                        <div class="row">
                            <div class="col-md-10">
                                {{Form::text('signature_photo_text', '', ['class' => 'form-control', 'id' => 'signature_photo_text', 'disabled'])}}
                            </div>
                            <div class="col-md-2">
                                {{Form::label('signature_photo', 'Upload File', ['class' => 'file-input', 'for' => 'signature_photo'])}}
                            </div> 
                        </div>
                    </div>


                    {{Form::submit('ADD', ['class'=>'btn btn-primary submit-btn'])}}

                {!! Form::close() !!}
        </div>
    
    </div>
</div>

@endsection