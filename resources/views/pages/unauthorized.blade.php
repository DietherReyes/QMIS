@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-3"></div>
        <div class="col-md-6 jumbotron text-center">
            <h1>Unauthorized access</h1>
            <a href="{{url()->previous()}}" class="btn  btn-md btn-primary">BACK</a>
        </div>
        <div class="col-md-3"></div>
    </div>
    
@endsection
