@extends('layouts.app')

@section('content')

<div class="container-fluid">
    <div class="row">
        
        <div class="col-md-1"></div>

        <div class="col-md-10 main">

                <ol class="breadcrumb">
                    <li><a href="/">Home</a></li>
                    <li><a href="/csm">Customer Satisfaction Measurement</a></li>
                    <li class="active">CSM Statistics</li>
                </ol>
                    
                <div style="float:right">
                    <a class="btn btn-primary btn-md"  href="/csm">BACK</a>
                </div>

                <h1 class="page-header">CSM Statistics</h1>
            
                <br>
                
                <div class="row">

                    <img class="center" src="/storage/assets/nothing_found.png">
                    <div id="notfound" >
                        <h1>Insufficient Records</h1>
                    </div>
                    

                </div>

                

                
                    

        </div>
        <div class="col-md-1"> </div>
    </div>
</div>



@endsection