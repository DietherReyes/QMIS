@extends('layouts.app')

@section('content')

<div class="container-fluid">
    <div class="row">
        @include('include.signatories_sidebar')

        

        <div class="col-md-9  main">
            <ol class="breadcrumb">
                <li><a href="/">Home</a></li>
                <li class="active"> Signatories</li>
            </ol>
            <div style="float:right">
                <a class="btn btn-primary btn-md"  href="/sysmg/signatories/create">ADD</a>
            </div>
            <h1 class="page-header">Signatories</h1>

            <div class="container">
                    {!! Form::open(['action' => 'SignatoriesController@search', 'method' => 'POST', 'class' => 'form-inline']) !!}
                    <div class="form-group">
                        {{Form::text('search_term', '', ['class' => 'form-control', 'placeholder' => 'Search', 'style' => 'width:745px'])}}
                    </div>
                    {{Form::submit('SEARCH', ['class'=>'btn btn-primary'])}}
                    <a href="/sysmg/signatories" class="btn btn-primary btn-md">
                        <span class="glyphicon glyphicon-refresh"></span> Refresh
                    </a>
                {!! Form::close() !!}
            </div>
           
                
            

            @if(count($signatories) > 0)
                <div class="row placeholders">
                    @foreach($signatories as $signatory)

                    <div class="col-xs-6 col-sm-3 placeholder">
                        <img src="/storage/signature_photos/{{$signatory->signature_photo}}" width="200" height="200">
                        <h4>{{$signatory->name}}</h4>
                        <h5>{{$signatory->position}}</h5>
                        <a class="btn btn-primary btn-sm"  href="/sysmg/signatories/{{$signatory->id}}">VIEW</a> 
                        <a class="btn btn-success btn-sm"  href="/sysmg/signatories/{{$signatory->id}}/edit">EDIT</a> 
                    </div>

                    @endforeach
                    
                </div>
                {{$signatories->links()}}
            @else
                <img class="center" src="/storage/assets/nothing_found.png">
                <div id="notfound" >
                    <h1  >No Results Found</h1>
                </div>
            @endif
        </div>
    </div>    
</div>

@endsection