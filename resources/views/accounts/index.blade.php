@extends('layouts.app')

@section('content')

<div class="container-fluid">
    <div class="row">
        @include('include.account_sidebar')

        <div class="col-md-9  main">
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
                    {!! Form::open(['action' => 'UsersController@search', 'method' => 'POST', 'class' => 'form-inline']) !!}
                    <div class="form-group">
                        {{Form::text('search_term', '', ['class' => 'form-control', 'placeholder' => 'Search', 'style' => 'width:745px'])}}
                    </div>
                    {{Form::submit('SEARCH', ['class'=>'btn btn-primary'])}}
                    <a href="/sysmg/accounts" class="btn btn-primary btn-md">
                        <span class="glyphicon glyphicon-refresh"></span> Refresh
                    </a>
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