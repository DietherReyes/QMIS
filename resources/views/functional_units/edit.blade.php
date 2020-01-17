@extends('layouts.app')

@section('content')

<div class="container-fluid">
    <div class="row">

        @include('include.units_sidebar')

       

        <div class="col-md-9  main">
            <ol class="breadcrumb">
                <li><a href="/">Home</a></li>
                <li> <a href="/sysmg/units"> Functional Units</a></li>
                <li class="active"> Edit Functional Unit</li>
            </ol>
            <div style="float:right">
                <a class="btn btn-primary btn-md"  href="/sysmg/units">BACK</a>
            </div>
            <h1 class="page-header">Edit {{$functional_unit->name}}</h1>
                

                {!! Form::open(['action' => ['FunctionalUnitsController@update', $functional_unit->id], 'method' => 'POST']) !!}

                    <div class="form-group">
                        {{Form::label('abbreviation', 'Abbreviation')}}
                        {{Form::text('abbreviation', $functional_unit->abbreviation, ['class' => 'form-control', 'placeholder' => 'Ex. MIS'])}}
                    </div>

                    <div class="form-group">
                        {{Form::label('name', 'Name')}}
                        {{Form::text('name', $functional_unit->name, ['class' => 'form-control', 'placeholder' => 'Management Information System'])}}
                    </div>

                    <div class="container-fluid">
                        
                        <div class="row">
                            <h3>PRIVELEGES ON MODULE </h3>
                            <div class="table-responsive">
                                    <table class="table table-striped">

                                            <thead>
                                                <tr>
                                                    <th style="text-align:center"></th>
                                                    <th style="text-align:center">View</th>
                                                    <th style="text-align:center">Add</th>
                                                    <th style="text-align:center">Edit</th>
                                                    {{-- <th style="text-align:center">Generate</th> --}}
                                                </tr>
                                            </thead>
                                            
                                            <tbody>

                                                {{-- 
                                                    Check column of checkboxes
                                                --}}
                                                <script language="JavaScript">
                                                        function selectAll(val){                      
                                                            checkboxes = document.getElementsByClassName(val);
                                                            checkboxes[0].checked = true;
                                                            checkboxes[1].checked = true;
                                                            checkboxes[2].checked = true;
                                                            checkboxes[3].checked = true;
                                                            
                                                        }
                                                </script>
                                    
                                                <tr>
                                                    <td style="text-align:center" > Check All</td>
                                                    <td style="text-align:center" > <button type="button" class="btn btn-sm btn-success" onclick="selectAll('view')"> <span class="glyphicon glyphicon-ok">  </button></td>
                                                    <td style="text-align:center" > <button type="button" class="btn btn-sm btn-success" onclick="selectAll('add')"> <span class="glyphicon glyphicon-ok"> </button></td>
                                                    <td style="text-align:center" > <button type="button" class="btn btn-sm btn-success" onclick="selectAll('edit')"> <span class="glyphicon glyphicon-ok"> </button></td>
                                                    {{-- <td style="text-align:center" > <button type="button" class="btn btn-sm btn-success" onclick="selectAll('generate')"> <span class="glyphicon glyphicon-ok"> </button></td> --}}
                                    
                                                </tr>
                                                <tr>
                                                    <td style="text-align:center">CSM</td>
                                                    <td style="text-align:center">{{Form::checkbox('permission[]', 0, ($functional_unit->permission[0] === '1') ? true : false ,['class' => 'view'])}}</td>
                                                    <td style="text-align:center">{{Form::checkbox('permission[]', 1, ($functional_unit->permission[1] === '1') ? true : false ,['class' => 'add'])}}</td>
                                                    <td style="text-align:center">{{Form::checkbox('permission[]', 2, ($functional_unit->permission[2] === '1') ? true : false ,['class' => 'edit'])}}</td>
                                                    {{-- <td style="text-align:center">{{Form::checkbox('permission[]', 3, ($functional_unit->permission[3] === '1') ? true : false ,['class' => 'generate'])}}</td> --}}
                                                    
                                                </tr>
                                                <tr>
                                                    <td style="text-align:center">QOA</td>
                                                    <td style="text-align:center">{{Form::checkbox('permission[]', 3, ($functional_unit->permission[3] === '1') ? true : false ,['class' => 'view'])}}</td>
                                                    <td style="text-align:center">{{Form::checkbox('permission[]', 4, ($functional_unit->permission[4] === '1') ? true : false ,['class' => 'add'])}}</td>
                                                    <td style="text-align:center">{{Form::checkbox('permission[]', 5, ($functional_unit->permission[5] === '1') ? true : false ,['class' => 'edit'])}}</td>
                                                    {{-- <td style="text-align:center">{{Form::checkbox('permission[]', 7, ($functional_unit->permission[7] === '1') ? true : false ,['class' => 'generate'])}}</td> --}}
                                                </tr>
                                                <tr>
                                                    <td style="text-align:center">MR</td>
                                                    <td style="text-align:center">{{Form::checkbox('permission[]', 6, ($functional_unit->permission[6] === '1')   ? true : false ,['class' => 'view'])}}</td>
                                                    <td style="text-align:center">{{Form::checkbox('permission[]', 7, ($functional_unit->permission[7] === '1')   ? true : false ,['class' => 'add'])}}</td>
                                                    <td style="text-align:center">{{Form::checkbox('permission[]', 8, ($functional_unit->permission[8] === '1') ? true : false ,['class' => 'edit'])}}</td>
                                                    {{-- <td style="text-align:center">{{Form::checkbox('permission[]', 11, ($functional_unit->permission[11] === '1') ? true : false ,['class' => 'generate'])}}</td> --}}
                                                </tr>
                                                <tr>
                                                    <td style="text-align:center">QMSD</td>
                                                    <td style="text-align:center">{{Form::checkbox('permission[]', 9, ($functional_unit->permission[9] === '1') ? true : false ,['class' => 'view'])}}</td>
                                                    <td style="text-align:center">{{Form::checkbox('permission[]', 10, ($functional_unit->permission[10] === '1') ? true : false ,['class' => 'add'])}}</td>
                                                    <td style="text-align:center">{{Form::checkbox('permission[]', 11, ($functional_unit->permission[11] === '1') ? true : false ,['class' => 'edit'])}}</td>
                                                    {{-- <td style="text-align:center">{{Form::checkbox('permission[]', 15, ($functional_unit->permission[15] === '1') ? true : false ,['class' => 'generate'])}}</td> --}}
                                            
                                                </tr>
                                            </tbody>
                                        </table>
                            </div>

                        </div>
                        
                    </div>
                    {{Form::hidden('_method','PUT')}}
                    {{Form::submit('EDIT', ['class'=>'btn btn-primary submit-btn'])}}

                {!! Form::close() !!}
        </div>
    
    </div>
</div>

@endsection