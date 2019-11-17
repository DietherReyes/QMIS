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


    // upload name for other files
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
                    <li><a href="/manrev">Management Review</a></li>
                    <li class="active">Edit Management Review </li>
                </ol>
                    
                <div style="float:right">
                    <a class="btn btn-primary btn-md"  href="/manrev">BACK</a>
                </div>
                    
                <h1 class="page-header"> Edit MGRV </h1>
                {!! Form::open(['action' => ['ManagementReviewsController@update', $management_review->id], 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}

                    <div class="row">
                    
                        <div class="col-md-6">
                            
                            <div class="form-group">
                                {{Form::label('meeting_name', 'Meeting name')}}
                                {{Form::text('meeting_name', $management_review->meeting_name, ['class' => 'form-control'])}}
                            </div>

                            <div class="form-group">
                                {{Form::label('date', 'Date')}}
                                {{ Form::date('date', $management_review->date, ['class' => 'form-control']) }}
                            </div>

                            <div class="form-group">
                                {{Form::label('action_plan', 'Action Plan')}}
                                {{Form::file('action_plan', [ 'class' => 'hidden', 'id' => 'action_plan' ,'onChange' => 'uploadName(this.id, \'action_plan_test\')'])}}
                                <div class="row">
                                    <div class="col-md-8">
                                        {{Form::text('action_plan_test', $management_review->action_plan, ['class' => 'form-control', 'id' => 'action_plan_test', 'disabled'])}}
                                    </div>
                                    <div class="col-md-4">
                                        {{Form::label('action_plan', 'Upload File', ['class' => 'file-input', 'for' => 'action_plan'])}}
                                    </div> 
                                </div>
                            </div>

                            <div class="form-group">
                                {{Form::label('agenda_memo', 'Agenda Memo')}}
                                {{Form::file('agenda_memo', [ 'class' => 'hidden', 'id' => 'agenda_memo' ,'onChange' => 'uploadName(this.id, \'agenda_memo_text\')'])}}
                                <div class="row">
                                    <div class="col-md-8">
                                        {{Form::text('agenda_memo_text',$management_review->agenda_memo, ['class' => 'form-control', 'id' => 'agenda_memo_text', 'disabled'])}}
                                    </div>
                                    <div class="col-md-4">
                                        {{Form::label('agenda_memo', 'Upload File', ['class' => 'file-input', 'for' => 'agenda_memo'])}}
                                    </div> 
                                </div>
                            </div>
                                   
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                {{Form::label('venue', 'Venue')}}
                                {{Form::text('venue', $management_review->venue, ['class' => 'form-control'])}}
                            </div>

                            <div class="form-group">
                                {{Form::label('attendance', 'Attendance')}}
                                {{Form::file('attendance', [ 'class' => 'hidden', 'id' => 'attendance' ,'onChange' => 'uploadName(this.id, \'attendance_text\')'])}}
                                <div class="row">
                                    <div class="col-md-8">
                                        {{Form::text('attendance_text', $management_review->attendance, ['class' => 'form-control', 'id' => 'attendance_text', 'disabled'])}}
                                    </div>
                                    <div class="col-md-4">
                                        {{Form::label('attendance', 'Upload File', ['class' => 'file-input', 'for' => 'attendance'])}}
                                    </div> 
                                </div>
                            </div>

                            <div class="form-group">
                                {{Form::label('minutes', 'Minutes')}}
                                {{Form::file('minutes', [ 'class' => 'hidden', 'id' => 'minutes' ,'onChange' => 'uploadName(this.id, \'minutes_text\')'])}}
                                <div class="row">
                                    <div class="col-md-8">
                                        {{Form::text('minutes_text',$management_review->minutes, ['class' => 'form-control', 'id' => 'minutes_text', 'disabled'])}}
                                    </div>
                                    <div class="col-md-4">
                                        {{Form::label('minutes', 'Upload File', ['class' => 'file-input', 'for' => 'minutes'])}}
                                    </div> 
                                </div>
                            </div>
                        
                            <div class="form-group">
                                {{Form::label('presentation_slide', 'Presentation Slides')}}
                                {{Form::file('presentation_slide', [ 'class' => 'hidden', 'id' => 'presentation_slide' ,'onChange' => 'uploadName(this.id, \'presentation_slides_text\')'])}}
                                <div class="row">
                                    <div class="col-md-8">
                                        {{Form::text('presentation_slides_text', $management_review->presentation_slide, ['class' => 'form-control', 'id' => 'presentation_slides_text', 'disabled'])}}
                                    </div>
                                    <div class="col-md-4">
                                        {{Form::label('presentation_slide', 'Upload File', ['class' => 'file-input', 'for' => 'presentation_slide'])}}
                                    </div> 
                                </div>
                            </div>
                
                        </div>

                    </div>

                    <div class="form-group">
                        {{Form::label('other_files', 'Other Files')}}
                        {{Form::file('other_files[]', [ 'class' => 'hidden', 'multiple' => 'multiple','id' => 'other_files' ,'onChange' => 'uploadNames(this.id, \'other_files_text\')'])}}
                        <div class="row">
                            <div class="col-md-10">
                                {{Form::text('other_files_text', $management_review->other_files, ['class' => 'form-control', 'id' => 'other_files_text', 'disabled'])}}
                            </div>
                            <div class="col-md-2">
                                {{Form::label('other_files', 'Upload File(s)', ['class' => 'file-input', 'for' => 'other_files'])}}
                            </div> 
                        </div>
                    </div>
                    
                    <div class="form-group">
                        {{Form::label('description', 'Other Files Description')}}
                        {{Form::text('description', $management_review->description, ['class' => 'form-control'])}}
                    </div>

                    {{Form::hidden('_method','PUT')}}
                    {{Form::submit('Submit', ['class'=>'btn btn-primary'])}}

                {!! Form::close() !!}
                

        </div>
        <div class="col-md-1"> </div>
    </div>
</div>



@endsection