@extends('layouts.app')

@section('content')

<div class="container-fluid">
    <div class="row">
        @include('SYSMG.sidebar')
        @include('SYSMG.functional_units')
    </div>
</div>

@endsection