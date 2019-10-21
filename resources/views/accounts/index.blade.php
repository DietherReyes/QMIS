@extends('layouts.app')

@section('content')

<div class="container-fluid">
    <div class="row">
        @include('include.sidebar')


        <div class="col-md-2" style="float:right">
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
        
        <div class="col-md-8  main">


            <h1 class="page-header">User Accounts</h1>

            <div class="container">
                    {!! Form::open(['action' => 'UsersController@search', 'method' => 'POST', 'class' => 'form-inline']) !!}
                    <div class="form-group">
                        {{Form::text('search_term', '', ['class' => 'form-control', 'placeholder' => 'Search', 'style' => 'width:720px'])}}
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
                <p>No Users</p>
            @endif
        </div>
    </div>    
</div>

@endsection