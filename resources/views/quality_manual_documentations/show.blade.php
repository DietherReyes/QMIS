
@extends('layouts.app')

@section('content')

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
                
            <h1 class="page-header"> View QMSD</h1>
            

            <div class="row">
            
                <div class="col-md-6">
                    
                    <ul class="list-group">

                        <h5>Document Code</h5>
                        <li class="list-group-item">{{$manual_doc->document_code}}</li>

                        <h5>Subject</h5>
                        <li class="list-group-item">{{$manual_doc->subject}}</li>

                        <h5>Effectivity Date</h5>
                        <li class="list-group-item">{{$manual_doc->effectivity_date}}</li>

                    </ul>
             
                </div>

                <div class="col-md-6">

                    <ul class="list-group">

                        <h5>Section</h5>
                        <li class="list-group-item">{{$manual_doc->section}}</li>

                        <h5>Revision Number</h5>
                        <li class="list-group-item">{{$manual_doc->revision_number}}</li>

                        <h5>Page Number</h5>
                        <li class="list-group-item">{{$manual_doc->page_number}}</li>
                    </ul>
                </div>

                <div class="col-md-12">
                        <h5>Quality Mandual Document</h5>
                        <div>
                            <div class="row">
                                <div class="col-md-10">
                                    <li class="list-group-item">{{$manual_doc->quality_manual_doc}}</li>
                                </div>
                                <div class="col-md-2">
                                    <a class="btn btn-primary btn-md" href="/qmsd/manual_doc/{{$manual_doc->id}}">Download File</a>
                                </div> 
                            </div>
                        </div>
                </div>
    

               
            </div>

        </div>
        <div class="col-md-1"> </div>
    </div>
</div>



@endsection