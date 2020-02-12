<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\FunctionalUnit;
use App\Log;
use App\CustomerSatisfactionMeasurementSummary;
use App\CustomerSatisfactionMeasurement;
use App\User;
use Auth;

class FunctionalUnitsController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
        $this->middleware('admin');
        
        $this->custom_messages = [
            'required'          => 'This field is required.',
            'abbreviation.max'  => 'The input must not be greater than 255 characters.',
            'name.max'          => 'The input must not be greater than 255 characters.',
        ];
    }

    private function get_year(&$years){
        $csm_years = CustomerSatisfactionMeasurementSummary::distinct('year')->orderBy('year', 'DESC')->pluck('year');
        foreach($csm_years as $csm_year){
            array_push($years, $csm_year);
        }
    }

    private function create_summary($name){
        $years = [];
        $this->get_year($years);

        foreach($years as $year){
            $new_summary = new CustomerSatisfactionMeasurementSummary;
            $new_summary->functional_unit = $name;
            $new_summary->year = $year;
            $new_summary->save();
        } 

    }

    private function update_functional_unit_name($old_name, $new_name){

        $ids = CustomerSatisfactionMeasurementSummary::where('functional_unit', $old_name)->pluck('id');
        foreach($ids as $id){
            CustomerSatisfactionMeasurementSummary::where('id', $id)->update(array(
                'functional_unit' => $new_name
            ));
        }

        $ids = CustomerSatisfactionMeasurement::where('functional_unit', $old_name)->pluck('id');
        foreach($ids as $id){
            CustomerSatisfactionMeasurement::where('id', $id)->update(array(
                'functional_unit' => $new_name
            ));
        }


        $ids = User::where('functional_unit', $old_name)->pluck('id');
        foreach($ids as $id){
            User::where('id', $id)->update(array(
                'functional_unit' => $new_name
            ));
        }
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
       
        
        $this->validate($request, [
            'abbreviation'      => 'required|max:255',
            'name'              => 'required:max:255'
        ],$this->custom_messages);

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
        $this->create_summary($request->name);

        $log = new Log;
        $log->name = Auth::user()->name;
        $log->action = 'ADD';
        $log->module = 'FUNCTIONAL-UNIT';
        $log->description = 'Added new unit: ' . $request->name;
        $log->save();

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
       
        $this->validate($request, [
            'abbreviation'      => 'required|max:255',
            'name'              => 'required:max:255'
        ], $this->custom_messages);

        $temp_permission = ["0", "0", "0", "0", "0", "0", "0", "0", "0", "0", "0", "0"];
        
        if($request->permission !== null){
            $checkbox_values = array_map('intval', $request->permission);
            foreach($checkbox_values as $value){
                $temp_permission[$value] = "1";
    
            }
        }
        $temp_permission = implode(',',$temp_permission);

        $old_unit = FunctionalUnit::find($id);
        $log = new Log;
        $log->name = Auth::user()->name;
        $log->action = 'EDIT';
        $log->module = 'FUNCTIONAL-UNIT';
        $log->description = 'Updated  unit: ' . $old_unit->name;
        $log->save();

        $functional_unit = FunctionalUnit::find($id);
        $functional_unit->abbreviation = $request->abbreviation;
        $functional_unit->name = $request->name;
        $functional_unit->permission = $temp_permission;
        $functional_unit->save();


        if($old_unit->name !== $request->name){
            $this->update_functional_unit_name($old_unit->name, $request->name);
        }        

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
