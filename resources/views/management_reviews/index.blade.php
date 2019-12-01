@extends('layouts.app')

@section('content')

<div class="container-fluid">
    <div class="row">
        
        <div class="col-md-1"></div>

        <div class="col-md-10 main">

                <ol class="breadcrumb">
                    <li><a href="/">Home</a></li>
                    <li class="active">Management Review </li>
                </ol>
                    
                <div style="float:right">
                    <a class="btn btn-primary btn-md"  href="/manrev/create">ADD</a>
                </div>
                    
                <h1 class="page-header"> Management Review </h1>
            

                <div class="container">
                
                    {!! Form::open(['action' => 'ManagementReviewsController@search', 'method' => 'POST']) !!}
                    <div class="row">
                        <div class="col-md-11">
                            <div class="input-group">
                            <input name="search_term" type="text" class="form-control" placeholder="Search">
                            <span class="input-group-btn">
                                    <button type="submit" class="btn btn-primary btn-md">
                                            <span class="glyphicon glyphicon-search"> </span> SEARCH
                                    </button>
                                    <a href="/manrev" class="btn btn-primary btn-md">
                                        <span class="glyphicon glyphicon-refresh"></span> REFRESH
                                    </a>
                            </span>
                            </div>
                        </div>
                    </div>
                    
                    {!! Form::close() !!}
                </div>
            
                
            
            
                @if(count($management_reviews) > 0)
            
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Meeting Name</th>
                                <th>Venue</th>
                                <th>Date</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @for ($i = 0; $i < count($management_reviews); $i++)
                                <tr>
                                    <td>{{$i + 1}}</td>
                                    <td>{{$management_reviews[$i]->meeting_name}}</td>
                                    <td>{{$management_reviews[$i]->venue}}</td>
                                    <td>{{$management_reviews[$i]->date}}</td>
                                    <td> 
                                        <a class="btn btn-primary btn-sm"  href="/manrev/{{$management_reviews[$i]->id}}">VIEW</a> 
                                        <a class="btn btn-success btn-sm"  href="/manrev/{{$management_reviews[$i]->id}}/edit">EDIT</a> 
                                    </td>
                                </tr>
                            @endfor
                            
                            </tbody>
                        </table>
                    </div>
                    {{$management_reviews->links()}}
            
                @else
                    <img class="center" src="/storage/assets/nothing_found.png">
                    <div id="notfound" >
                        <h1  >No Results Found</h1>
                    </div>
                @endif

        </div>
        <div class="col-md-1"> </div>
    </div>
</div>



@endsection