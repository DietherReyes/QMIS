
@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        
        <div class="col-md-1"></div>

        <div class="col-md-10 main">

            <ol class="breadcrumb">
                <li><a href="/">Home</a></li>
                <li><a href="/manrev">Management Review</a></li>
                <li class="active">View Management Review </li>
            </ol>
                
            <div style="float:right">
                <a class="btn btn-primary btn-md" href="/manrev/all_files/{{$management_review->id}}">Download All</a>
                <a class="btn btn-success btn-md"  href="/manrev/{{$management_review->id}}/edit">EDIT</a> 
                <a class="btn btn-primary btn-md"  href="/manrev">BACK</a>
            </div>
                
            <h1 class="page-header"> View MGRV </h1>
            

            <div class="row">
            
                <div class="col-md-6">
                    
                    <ul class="list-group">

                        <h5>Meeting Name</h5>
                        <li class="list-group-item">{{$management_review->meeting_name}}</li>

                        <h5>Date</h5>
                        <li class="list-group-item">{{$management_review->date}}</li>


                        <h5>Action Plan</h5>
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

                        <h5>Agenda Memo</h5>
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
                        <h5>Venue</h5>
                        <li class="list-group-item">{{$management_review->venue}}</li>
                    </ul>

                    <h5>Attendance Sheet</h5>
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

                    <h5>Minutes</h5>
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

                    <h5>Presentation Slide</h5>
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
                <h5 >Other Files</h5>
                <li class="list-group-item">{{$management_review->other_files}}</li>
                        
                            
                            
                       
            
                <h5>Other Files Description</h5>
                <li class="list-group-item">{{$management_review->description}}</li>
            </ul>

                    
                

        </div>
        <div class="col-md-1"> </div>
    </div>
</div>



@endsection