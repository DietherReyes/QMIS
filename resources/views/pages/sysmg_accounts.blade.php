@extends('layouts.app')

@section('content')

<div class="container-fluid">
    <div class="row">
        @include('include.sidebar')
        @include('accounts.index')
    </div>
</div>



@endsection