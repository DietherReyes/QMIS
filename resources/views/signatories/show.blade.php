@extends('layouts.app')

@section('content')

     @include('include.sidebar')
     
     <div class="col-md-2" style="float:right">
        <a class="btn btn-success btn-md"  href="/sysmg/signatories/{{$signatory->id}}/edit">EDIT</a>
        <a class="btn btn-primary btn-md"  href="/sysmg/signatories">BACK</a>
        
     </div>
     
     <div class="col-md-8  main">
        
        
        <h1 class="page-header"> SIGNATORIES </h1>
        <h3>SIGNATORY INFORMATION</h3>
        
        <img style="width:150px;" src="/storage/signature_photos/{{$signatory->signature_photo}}">
        <ul class="list-group">
            <h5>Name</h5>
            <li class="list-group-item">{{$signatory->name}}</li>
            <h5>Position</h5>
            <li class="list-group-item">{{$signatory->position}}</li>
        </ul>
        
        </div>

    </div>

@endsection