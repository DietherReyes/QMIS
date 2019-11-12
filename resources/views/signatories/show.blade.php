@extends('layouts.app')

@section('content')

@include('include.signatories_sidebar')
     
     
     <div class="col-md-9  main">
        
        <ol class="breadcrumb">
            <li><a href="/">Home</a></li>
            <li><a href="/sysmg/signatories">Signatories</a></li>
            <li class="active"> View Signatory </li>
        </ol>
        
        <div style="float:right">
                <a class="btn btn-success btn-md"  href="/sysmg/signatories/{{$signatory->id}}/edit">EDIT</a>
                <a class="btn btn-primary btn-md"  href="/sysmg/signatories">BACK</a>
        </div>
        
        <h1 class="page-header"> {{$signatory->name}} </h1>
        
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