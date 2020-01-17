<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\FunctionalUnit;

class FunctionalUnitsController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
        $this->middleware('admin');
        
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $functional_units = FunctionalUnit::orderBy('id')->paginate(10);
        return view('functional_units.index')->with('functional_units', $functional_units);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('functional_units.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //validation
        $this->validate($request, [
            'abbreviation' => 'required',
            'name' => 'required'
        ]);

        $temp_permission = ["0", "0", "0", "0", "0", "0", "0", "0", "0", "0", "0", "0"];
        if($request->permission !== null){
            $checkbox_values = array_map('intval', $request->permission);
            foreach($checkbox_values as $value){

                $temp_permission[$value] = "1";
    
            }
        }
        $temp_permission = implode(',',$temp_permission);

        $functional_unit = new FunctionalUnit;
        $functional_unit->abbreviation = $request->abbreviation;
        $functional_unit->name = $request->name;
        $functional_unit->permission = $temp_permission;
        $functional_unit->save();

        return redirect('sysmg/units');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $functional_unit = FunctionalUnit::find($id);
        $functional_unit->permission = explode(',', $functional_unit->permission); //splitting the permission array
        return view('functional_units.show')->with('functional_unit', $functional_unit);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $functional_unit = FunctionalUnit::find($id);
        $functional_unit->permission = explode(',', $functional_unit->permission);
        return view('functional_units.edit')->with('functional_unit', $functional_unit);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //validation
        $this->validate($request, [
            'abbreviation' => 'required',
            'name' => 'required'
        ]);

        $temp_permission = ["0", "0", "0", "0", "0", "0", "0", "0", "0", "0", "0", "0"];
        
        if($request->permission !== null){
            $checkbox_values = array_map('intval', $request->permission);
            foreach($checkbox_values as $value){
                $temp_permission[$value] = "1";
    
            }
        }
        
        $temp_permission = implode(',',$temp_permission);

        $functional_unit = FunctionalUnit::find($id);
        $functional_unit->abbreviation = $request->abbreviation;
        $functional_unit->name = $request->name;
        $functional_unit->permission = $temp_permission;
        $functional_unit->save();

        return redirect('sysmg/units');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Search functional unit by name
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     *  
     */
    public function search(Request $request){
        $functional_units = FunctionalUnit::where('name', 'like', '%'.$request->search_term.'%')->paginate(10);
        return view('functional_units.index')->with('functional_units', $functional_units);
    }
}
