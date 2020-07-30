@extends('layouts.app')

@section('content')

<div class="row">
    <div class="col-md-1"></div>
    <div class="col-md-10">
                
        @if( Auth::user()->isActivated  !== 1 )
                <br>
                <div class="row">
                        <div class="col-md-3"></div>
                        <div class="col-md-6 jumbotron text-center ">
                                <h1>Account Deactivated</h1>
                        </div>
                        <div class="col-md-3"></div>
                </div>
                
        @else




                @guest
                        <div class="jumbotron text-center">
                                
                        <h1>Welcome to Quality Management Information System</h1>
                
                        
                        <p> <a class="btn btn-primary btn-lg" href="{{route('login')}}">LOGIN</a> </p>
                                
                        </div>
                @else 
                        <div class="row placeholders">
                                <div class="jumbotron text-center">

                                        <h1>Welcome to Quality Management Information System</h1>
                                
                                                
                                        </div>

                                <div class="col-md-4 ">
                                        <img class="center" src="/storage/assets/csm.png">
                                        
                                        <div class="placeholder">
                                                <h4> <strong> Customer Satisfaction Measurement </strong> </h4>
                                        </div>
                                        <br>

                                        <p>This module consist of list of Customer Satisfaction Measurements for each functional unit of the DOST-CALABARZON added by an authorized users which can be viewed and edited by an authorized user.</p>

                                        <div class="placeholder">
                                                <a class="btn btn-primary btn-md center"  href="/csm">GO</a> 
                                        </div>
                                        
                                
                                        
                                </div>

                                <div class="col-md-4 ">
                                        <img class="center" src="/storage/assets/mr.png">
                                        <div class="placeholder">
                                                <h4> <strong> Management Review </strong> </h4>
                                        </div>
                                        <br>

                                        <p>This module consist of list of meetings held within DOST-CLABARZON office added by an authorized users which can be viewed or edited by authorized users.</p>

                                        <div class="placeholder">
                                                <a class="btn btn-primary btn-md center"  href="/manrev">GO</a> 
                                        </div>
                                </div>


                                <div class="col-md-4 ">
                                        <img class="center" src="/storage/assets/qmsd.png">
                                        <div class="placeholder">
                                                <h4> <strong> Quality Management System Documentation </strong> </h4>
                                        </div>

                                        <p>This module consist of list of pages of the Quality Managament System Manual of DOST  added by an authorized users which can be viewed or edited by authorized users..</p>

                                        <div class="placeholder">
                                                <a class="btn btn-primary btn-md center"  href="/qmsd">GO</a> 
                                        </div>
                                </div>

                                
                                
                        </div>   
                @endguest
        @endif

    </div>
    <div class="col-md-1"></div>
</div>
    
@endsection
