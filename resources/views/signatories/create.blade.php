@extends('layouts.app')

@section('content')

<div class="container-fluid">
    <div class="row">

        @include('include.sidebar')

        <div class="col-md-2" style="float:right">
            <a class="btn btn-primary btn-md"  href="/sysmg/signatories">BACK</a>
        </div>

        <div class="col-md-8  main">
                <h1 class="page-header">SIGNATORIES</h1>
                <h3>ADD SIGNATORY</h3>

                {!! Form::open(['action' => 'SignatoriesController@store', 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}

                    <div class="form-group">
                        {{Form::label('signature_photo', 'Signaure (.png)')}}
                        {{Form::file('signature_photo')}}
                    </div>

                    <div class="form-group">
                        {{Form::label('name', 'Name')}}
                        {{Form::text('name', '', ['class' => 'form-control'])}}
                    </div>

                    <div class="form-group">
                        {{Form::label('position', 'Position')}}
                        {{Form::text('position', '', ['class' => 'form-control'])}}
                    </div>


                    {{Form::submit('Submit', ['class'=>'btn btn-primary'])}}

                {!! Form::close() !!}
        </div>
    
    </div>
</div>

@endsection