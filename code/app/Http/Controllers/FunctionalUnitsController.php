<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\FunctionalUnit;
use App\Log;
use App\CustomerSatisfactionMeasurementSummary;
use App\CustomerSatisfactionMeasurement;
use App\User;
use Auth;
use Validator;

class FunctionalUnitsController extends Controller
{
    public function __construct(){
        //Checks if user is authentocated and an administrator
        $this->middleware('auth');
        $this->middleware('admin');
        $this->middleware('account_status');
        
        //Validation messages
        $this->custom_messages = [
            'required'          => 'This field is required.',
            'abbreviation.max'  => 'The input must not be greater than 255 characters.',
            'name.max'          => 'The input must not be greater than 255 characters.',
        ];
    }

    //get csm years
    private function get_year(&$years){
        $csm_years = CustomerSatisfactionMeasurementSummary::distinct('year')->orderBy('year', 'DESC')->pluck('year');
        foreach($csm_years as $csm_year){
            array_push($years, $csm_year);
        }
    }

    //Create summary for newly created functional units when there are already recorded csm
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

    //Update functional unit name
    private function update_functional_unit_name($old_name, $new_name){

        //rename folders in storage
        $years = CustomerSatisfactionMeasurement::where('functional_unit', $old_name)->distinct('year')->pluck('year');
        foreach($years as $year){
            $quarters = CustomerSatisfactionMeasurement::where([
                ['functional_unit', $old_name],
                ['year', $year]
            ])->pluck('quarter');

            foreach($quarters as $quarter){
                $old_dir = $old_name.'-'.$year.'-Quarter'.$quarter;
                $new_dir = $new_name.'-'.$year.'-Quarter'.$quarter;
                Storage::move('public/customer_satisfaction_measurements/'.$old_dir , 'public/customer_satisfaction_measurements/'.$new_dir);
            }
        }

        //Update all functional unit in the csm summary
        $ids = CustomerSatisfactionMeasurementSummary::where('functional_unit', $old_name)->pluck('id');
        foreach($ids as $id){
            CustomerSatisfactionMeasurementSummary::where('id', $id)->update(array(
                'functional_unit' => $new_name
            ));
        }

        //Update all functional unit in the csm
        $ids = CustomerSatisfactionMeasurement::where('functional_unit', $old_name)->pluck('id');
        foreach($ids as $id){
            CustomerSatisfactionMeasurement::where('id', $id)->update(array(
                'functional_unit' => $new_name
            ));
        }

        //Update functional unit of the users
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
        $functional_units = FunctionalUnit::orderBy('name')->paginate(10);
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
            'abbreviation'      => 'required|max:255|unique:functional_units',
            'name'              => 'required|max:255|unique:functional_units'
        ],$this->custom_messages);

        //Permission array
        //Change value from 0 to 1 if user is authorized
        //All indeces has corresponding permission
        $temp_permission = ["0", "0", "0", "0", "0", "0", "0", "0", "0", "0", "0", "0"];
        if($request->permission !== null){
            $checkbox_values = array_map('intval', $request->permission);
            foreach($checkbox_values as $value){

                $temp_permission[$value] = "1";
    
            }
        }
        $temp_permission = implode(',',$temp_permission);

        $functional_unit                = new FunctionalUnit;
        $functional_unit->abbreviation  = $request->abbreviation;
        $functional_unit->name          = $request->name;
        $functional_unit->permission    = $temp_permission;
        $functional_unit->save();
        $this->create_summary($request->name);

        $log                = new Log;
        $log->name          = Auth::user()->name;
        $log->action        = 'ADD';
        $log->module        = 'FUNCTIONAL-UNIT';
        $log->description   = 'Added new unit: ' . $request->name;
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
       
        $validator = Validator::make($request->all(), [
            'abbreviation'      => 'required|max:255',
            'name'              => 'required|:max:255'
        ], $this->custom_messages);

        //Checks if no changes are made with the name or abbreviation
        $old_unit = FunctionalUnit::find($id);
        if($request->abbreviation === $old_unit->abbreviation && $request->name === $old_unit->name){
            $validator->validate();
        }else{
            
            //check if abbreviation is changed
            $abbreviation = FunctionalUnit::where('abbreviation', $request->abbreviation)->pluck('abbreviation');
            if($old_unit->abbreviation !== $request->abbreviation && count($abbreviation) !== 0){
                $validator->after(function ($validator) {
                    $validator->errors()->add('abbreviation', 'The abbreviation has already been taken.');
                });
                
            }

            //check if name is changed
            $name = FunctionalUnit::where('name', $request->name)->pluck('name');
            if($old_unit->name !== $request->name && count($name) !== 0){
                $validator->after(function ($validator) {
                    $validator->errors()->add('name', 'The name has already been taken.');
                });
                
            }
            $validator->validate();
        }




        //Permission array
        //Change value from 0 to 1 if user is authorized
        //All indeces has corresponding permission
        $temp_permission = ["0", "0", "0", "0", "0", "0", "0", "0", "0", "0", "0", "0"];
        if($request->permission !== null){
            $checkbox_values = array_map('intval', $request->permission);
            foreach($checkbox_values as $value){
                $temp_permission[$value] = "1";
    
            }
        }
        $temp_permission = implode(',',$temp_permission);

        $old_unit = FunctionalUnit::find($id);
        $log                = new Log;
        $log->name          = Auth::user()->name;
        $log->action        = 'EDIT';
        $log->module        = 'FUNCTIONAL-UNIT';
        $log->description   = 'Updated  unit: ' . $old_unit->name;
        $log->save();

        $functional_unit                = FunctionalUnit::find($id);
        $functional_unit->abbreviation  = $request->abbreviation;
        $functional_unit->name          = $request->name;
        $functional_unit->permission    = $temp_permission;
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
