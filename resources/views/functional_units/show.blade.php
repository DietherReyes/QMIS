@extends('layouts.app')

@section('content')

<div class="container-fluid">
        <div class="row">
                @include('include.units_sidebar')
        
                <div class="col-md-9 main">
        
        
                    <ol class="breadcrumb">
                        <li><a href="/">Home</a></li>
                        <li> <a href="/sysmg/units"> Functional Units</a></li>
                        <li class="active"> View Functional Unit</li>
                    </ol>
                    <div style="float:right">
                            <a class="btn btn-success btn-md"  href="/sysmg/units/{{$functional_unit->id}}/edit">EDIT</a>
                            <a class="btn btn-primary btn-md"  href="/sysmg/units">BACK</a>
                    </div>

                    <h1 class="page-header">{{$functional_unit->name}}</h1>
                    
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
                                    {{-- <th style="text-align:center">Generate</th> --}}
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td style="text-align:center">CSM</td>
                                    @for ($i = 0; $i <= 2; $i++)
                                        @if ($functional_unit->permission[$i] === '1')
                                            <td style="text-align:center"><span class="glyphicon glyphicon-ok" style="color:green"> </td>
                                        @else
                                            <td style="text-align:center"> <span class="glyphicon glyphicon-remove" style="color:red" ></span> </td>
                                        @endif
                                    @endfor
                                </tr>
                                <tr>
                                    <td style="text-align:center">QOA</td>
                                    @for ($i = 3; $i <= 5; $i++)
                                        @if ($functional_unit->permission[$i] === '1')
                                            <td style="text-align:center"><span class="glyphicon glyphicon-ok" style="color:green"> </td>
                                        @else
                                            <td style="text-align:center"> <span class="glyphicon glyphicon-remove" style="color:red"></span> </td>
                                        @endif
                                    @endfor
                                </tr>
                                <tr>
                                    <td style="text-align:center">MR</td>
                                    @for ($i = 6; $i <= 8; $i++)
                                        @if ($functional_unit->permission[$i] === '1')
                                            <td style="text-align:center"><span class="glyphicon glyphicon-ok" style="color:green"> </td>
                                        @else
                                            <td style="text-align:center"> <span class="glyphicon glyphicon-remove" style="color:red" ></span> </td>
                                        @endif
                                    @endfor
                                </tr>
                                <tr>
                                    <td style="text-align:center">QMSD</td>
                                    @for ($i = 9; $i <= 11; $i++)
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
    </div>
</div>
@endsection