@extends('layouts.app')

@section('content')

<div class="container-fluid">
    <div class="row">
        @include('include.sidebar')
        @include('signatories.index')
    </div>
</div>

@endsection