@extends('layouts.app')

@section('content')

     @include('include.sidebar')
     <div class="col-md-2" style="float:right">
        <a class="btn btn-success btn-md"  href="/sysmg/units/{{$functional_unit->id}}/edit">EDIT</a>
        <a class="btn btn-primary btn-md"  href="/sysmg/units">BACK</a>
        
     </div>
     
     <div class="col-md-8  main">
        
        
        <h1 class="page-header"> FUNCTIONAL UNITS </h1>
        <h3>FUNCTIONAL UNIT INFORMATION</h3>
        
        <ul class="list-group">
            <h5>Abbreviation</h5>
            <li class="list-group-item">{{$functional_unit->abbreviation}}</li>
            <h5>Name</h5>
            <li class="list-group-item">{{$functional_unit->name}}</li>
        </ul>
        
        <div class="table-responsive">
            <table class="table table-striped">
                
                <thead>
                        <h3>PRIVELEGES ON MODULE</h3>
                    <tr>
                        <th style="text-align:center"></th>
                        <th style="text-align:center">View</th>
                        <th style="text-align:center">Add</th>
                        <th style="text-align:center">Edit</th>
                        <th style="text-align:center">Generate</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="text-align:center">CSM</td>
                        @for ($i = 0; $i <= 3; $i++)
                            @if ($functional_unit->permission[$i] === '1')
                                <td style="text-align:center"><span class="glyphicon glyphicon-ok" style="color:green"> </td>
                            @else
                                <td style="text-align:center"> <span class="glyphicon glyphicon-remove" style="color:red" ></span> </td>
                            @endif
                        @endfor
                    </tr>
                    <tr>
                        <td style="text-align:center">QOA</td>
                        @for ($i = 4; $i <= 7; $i++)
                            @if ($functional_unit->permission[$i] === '1')
                                <td style="text-align:center"><span class="glyphicon glyphicon-ok" style="color:green"> </td>
                            @else
                                <td style="text-align:center"> <span class="glyphicon glyphicon-remove" style="color:red"></span> </td>
                            @endif
                        @endfor
                    </tr>
                    <tr>
                        <td style="text-align:center">MR</td>
                        @for ($i = 8; $i <= 11; $i++)
                            @if ($functional_unit->permission[$i] === '1')
                                <td style="text-align:center"><span class="glyphicon glyphicon-ok" style="color:green"> </td>
                            @else
                                <td style="text-align:center"> <span class="glyphicon glyphicon-remove" style="color:red" ></span> </td>
                            @endif
                        @endfor
                    </tr>
                    <tr>
                        <td style="text-align:center">QMSD</td>
                        @for ($i = 12; $i <= 15; $i++)
                            @if ($functional_unit->permission[$i] === '1')
                                <td style="text-align:center"><span class="glyphicon glyphicon-ok" style="color:green"> </td>
                            @else
                                <td style="text-align:center"> <span class="glyphicon glyphicon-remove" style="color:red"></span> </td>
                            @endif
                        @endfor
                    </tr>
                   
                </tbody>
            </table>
        </div>

    </div>

@endsection