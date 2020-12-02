
@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        
        <div class="col-md-1"></div>

        <div class="col-md-10 main">

            <ol class="breadcrumb">
                <li><a href="/">Home</a></li>
                <li><a href="/manrev">Management Review</a></li>
                <li class="active">View MR </li>
            </ol>
                
            <div style="float:right">
                <a class="btn btn-primary btn-md" href="/manrev/all_files/{{$management_review->id}}">Download All</a>
                <a class="btn btn-success btn-md"  href="/manrev/{{$management_review->id}}/edit">EDIT</a> 
                <a class="btn btn-primary btn-md"  href="/manrev">BACK</a>
            </div>
                
            <h1 class="page-header"> View MR </h1>
            

            <div class="row">
            
                <div class="col-md-6">
                    
                    <ul class="list-group">

                        <h5> <strong>Meeting Name</strong> </h5>
                        <li class="list-group-item">{{$management_review->meeting_name}}</li>

                        <h5> <strong>Date</strong> </h5>
                        <li class="list-group-item">{{$management_review->date}}</li>


                        <h5> <strong>Action Plan</strong> </h5>
                        <div>
                            <div class="row">
                                <div class="col-md-8">
                                    <li class="list-group-item">{{$management_review->action_plan}}</li>
                                </div>
                                <div class="col-md-4">
                                    <a class="btn btn-primary btn-md" href="/manrev/action_plan/{{$management_review->id}}">Download File</a>
                                   
                                </div> 
                            </div>
                        </div>

                        <h5> <strong>Agenda Memo</strong> </h5>
                        <div>
                            <div class="row">
                                <div class="col-md-8">
                                    <li class="list-group-item">{{$management_review->agenda_memo}}</li>
                                </div>
                                <div class="col-md-4">
                                    <a class="btn btn-primary btn-md" href="/manrev/agenda_memo/{{$management_review->id}}">Download File</a>
                                    
                                </div> 
                            </div>
                        </div>
                    
                        
                    </ul>

                            
                </div>

                <div class="col-md-6">

                    <ul class="list-group">
                        <h5> <strong>Venue</strong> </h5>
                        <li class="list-group-item">{{$management_review->venue}}</li>
                    </ul>

                    <h5> <strong>Attendance Sheet</strong> </h5>
                    <div>
                        <div class="row">
                            <div class="col-md-8">
                                <li class="list-group-item">{{$management_review->attendance_sheet}}</li>
                            </div>
                            <div class="col-md-4">
                                <a class="btn btn-primary btn-md" href="/manrev/attendance/{{$management_review->id}}">Download File</a>
                                
                            </div> 
                        </div>
                    </div>

                    <h5> <strong>Minutes</strong> </h5>
                    <div>
                        <div class="row">
                            <div class="col-md-8">
                                <li class="list-group-item">{{$management_review->minutes}}</li>
                            </div>
                            <div class="col-md-4">
                                <a class="btn btn-primary btn-md" href="/manrev/minutes/{{$management_review->id}}">Download File</a>
                                
                            </div> 
                        </div>
                    </div>

                    <h5> <strong>Presentation Slide</strong> </h5>
                    <div>
                        <div class="row">
                            <div class="col-md-8">
                                <li class="list-group-item">{{$management_review->slides}}</li>
                            </div>
                            <div class="col-md-4">
                                <a class="btn btn-primary btn-md" href="/manrev/presentation_slide/{{$management_review->id}}">Download File(s)</a>
                                
                            </div> 
                        </div>
                    </div>

                </div>
            </div>

            <ul class="list-group">
                <h5> <strong>Other Files</strong> </h5>
                <li class="list-group-item">{{$management_review->other_files}}</li>
                        
                @if ($management_review->description !== null)
                    <h5> <strong>Other Files Description</strong> </h5>
                    <li class="list-group-item">{{$management_review->description}}</li>
                @endif
                
            </ul>

                    
                

        </div>
        <div class="col-md-1"> </div>
    </div>
</div>



@endsection