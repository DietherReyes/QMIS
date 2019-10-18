@extends('layouts.app')

@section('content')

<div class="container-fluid">
    <div class="row">
        @include('SYSMG.sidebar')
        @include('SYSMG.signatories')
    </div>
</div>

@endsection