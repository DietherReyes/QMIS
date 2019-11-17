
@extends('layouts.app')

@section('content')
<script type="application/javascript">
   
    
    // function download(download){
    //     $(document).ready(function(){
    //         var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    //         $(".postbutton").click(function(){
    //             $.ajax({
    //                 /* the route pointing to the post function */
    //                 url: '/postajax',
    //                 type: 'POST',
    //                 /* send the csrf-token and the input to the controller */
    //                 data: {_token: CSRF_TOKEN, message:$(".getinfo").val()},
    //                 dataType: 'JSON',
    //                 /* remind that 'data' is the response of the AjaxController */
    //                 success: function (data) { 
    //                     $(".writeinfo").append(data.msg); 
    //                 }
    //             }); 
    //         });
    //    }); 
    // }
    
     
</script>

<div class="container-fluid">
    <div class="row">
        
        <div class="col-md-1"></div>

        <div class="col-md-10 main">

            <ol class="breadcrumb">
                <li><a href="/">Home</a></li>
                <li><a href="/qmsd">Quality Management System Documentation</a></li>
                <li class="active">View QMSD </li>
            </ol>
                
            <div style="float:right">
                <a class="btn btn-success btn-md"  href="/qmsd/{{$manual_doc->id}}/edit">EDIT</a> 
                <a class="btn btn-primary btn-md"  href="/qmsd">BACK</a>
            </div>
                
            <h1 class="page-header"> View Management Review </h1>
            

            <div class="row">
            
                <div class="col-md-6">
                    
                    <ul class="list-group">

                        <h5>Document Code</h5>
                        <li class="list-group-item">{{$manual_doc->document_code}}</li>

                        <h5>Subject</h5>
                        <li class="list-group-item">{{$manual_doc->subject}}</li>

                        <h5>Date</h5>
                        <li class="list-group-item">{{$manual_doc->date}}</li>

                    </ul>
             
                </div>

                <div class="col-md-6">

                    <ul class="list-group">

                        <h5>Division</h5>
                        <li class="list-group-item">{{$manual_doc->division}}</li>

                        <h5>Revision Number</h5>
                        <li class="list-group-item">{{$manual_doc->revision_no}}</li>

                        <h5>Quality Mandual Document</h5>
                        <div>
                            <div class="row">
                                <div class="col-md-8">
                                    <li class="list-group-item">{{$manual_doc->quality_manual_doc}}</li>
                                </div>
                                <div class="col-md-4">
                                    <a class="btn btn-primary btn-md" href="/qmsd/manual_doc/{{$manual_doc->id}}">Download File</a>
                                    {{-- <label class="file-input" onclick="download('{{ route('dl_action_plan', $management_review->id)}}')">Download File</label> --}}
                                </div> 
                            </div>
                        </div>

                    </ul>

                    

                   

                </div>
            </div>

        </div>
        <div class="col-md-1"> </div>
    </div>
</div>



@endsection