@extends('layouts.app')

@section('content')

<div class="container-fluid">
    <div class="row">
        @include('include.account_sidebar')

        <div class="col-md-9 main">
            <ol class="breadcrumb">
                <li><a href="/">Home</a></li>
                <li class="active">User Account Management</li>
            </ol>

            <div style="float:right">
                <div class="btn-group">
    
                    <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    ADD <span class="caret"></span>
                    </button>
    
                    <ul class="dropdown-menu">
    
                        <li><a href="/sysmg/accounts/create/employee"> NEW EMPLOYEE</a></li>
                        <li><a href="/sysmg/accounts/create/manager"> NEW MANAGER </li>
                        <li><a href="/sysmg/accounts/create/admin"> NEW ADMINISTRATOR</a></li>
                   
                    </ul>
                </div>
            </div>

            <h1 class="page-header">User Account Management</h1>


            <div class="container">
                
                {!! Form::open(['action' => 'UsersController@search', 'method' => 'POST']) !!}
                <div class="row">
                    <div class="col-md-10">
                        <div class="input-group">
                        <input name="search_term" type="text" class="form-control" placeholder="Search">
                        <span class="input-group-btn">
                                <button type="submit" class="btn btn-primary btn-md">
                                        <span class="glyphicon glyphicon-search"> </span> SEARCH
                                </button>
                                <a href="/sysmg/accounts" class="btn btn-primary btn-md">
                                    <span class="glyphicon glyphicon-refresh"></span> REFRESH
                                </a>
                        </span>
                        </div>
                    </div>
                </div>
                
                {!! Form::close() !!}
            </div>
           
                
            

            @if(count($users) > 0)
        
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th >#</th>
                            <th >Name</th>
                            <th >Position</th>
                            <th >Functional Unit</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @for ($i = 0; $i < count($users); $i++)
                            <tr>
                                <td >{{$i + 1}}</td>
                                <td>{{$users[$i]->name}}</td>
                                <td >{{$users[$i]->position}}</td>
                                <td >{{$users[$i]->functional_unit}} </td>
                                <td> 
                                    <a class="btn btn-primary btn-sm"  href="/sysmg/accounts/{{$users[$i]->id}}">VIEW</a> 
                                    <a class="btn btn-success btn-sm"  href="/sysmg/accounts/{{$users[$i]->id}}/edit">EDIT</a> 
                                </td>
                            </tr>
                        @endfor
                        
                        </tbody>
                    </table>
                </div>
                {{$users->links()}}
        
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