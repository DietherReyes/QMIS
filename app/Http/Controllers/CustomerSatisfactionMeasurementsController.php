<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\CustomerSatisfactionMeasurement;
use App\CustomerClassification;
use App\CustomerOverallRating;
use App\CustomerRating;
use App\CustomerSatisfactionDocuments;
use App\FunctionalUnit;
use App\User;
use Illuminate\Support\Facades\Storage;
use Zipper;
use Auth;

class CustomerSatisfactionMeasurementsController extends Controller
{

    public function __construct(){
        $this->middleware('auth');
    }

    private function check_permission(&$isPermitted, $user_id, $permission){
        $user = User::find(Auth::id());
        $user->permission = explode(',', $user->permission);
        if($user->permission[$permission] === '1' || $user->role === 'admin'){
            $isPermitted = true;
        }
    }

    //gets dropdown data
    private function get_data(&$data){
        $functional_units = FunctionalUnit::all();
        foreach($functional_units as $functional_unit){
            $data[$functional_unit->name] = $functional_unit->name;
        }
    }

    private function get_year(&$year){
        $csm_years = CustomerSatisfactionMeasurement::orderBy('year', 'ASC')->pluck('year');
        foreach($csm_years as $csm_year){
            $year[$csm_year] = $csm_year;
        }
    }

    private function save_customer_classification($request, $csm_id){
        $classification = $request->customer_classification;
        $customer_classification = new CustomerClassification;
        $customer_classification->student               = $classification['student'];
        $customer_classification->government_employee   = $classification['government_employee'];
        $customer_classification->internal              = $classification['internal'];
        $customer_classification->business              = $classification['student'];
        $customer_classification->homemaker             = $classification['homemaker'];
        $customer_classification->entrepreneur          = $classification['entrepreneur'];
        $customer_classification->private_organization  = $classification['private_organization'];
        $customer_classification->others                = $classification['others'];
        $customer_classification->others_specify        = $classification['others_specify'];
        $customer_classification->csm_id                = $csm_id;
        $customer_classification->save();

    }

    private function update_customer_classification($request, $csm_id){
        $classification = $request->customer_classification;
        CustomerClassification::where('csm_id',$csm_id)->update(array(
            'student'               => $classification['student'],
            'government_employee'   => $classification['government_employee'],
            'internal'              => $classification['internal'],
            'business'              => $classification['business'],
            'homemaker'             => $classification['homemaker'],
            'entrepreneur'          => $classification['entrepreneur'],
            'private_organization'  => $classification['private_organization'],
            'others'                => $classification['others'],
            'others_specify'        => $classification['others_specify'],
            'csm_id'                => $csm_id
        )); 
    }

    private function save_customer_rating($request, $csm_id){
        $rating = $request->customer_rating;
        $customer_rating = new CustomerRating;
        $customer_rating->five_star = $rating['five_star'];
        $customer_rating->four_star = $rating['four_star'];
        $customer_rating->three_below = $rating['three_below'];
        $customer_rating->csm_id = $csm_id;
        $customer_rating->save();
    }

    private function update_customer_rating($request, $csm_id){
        $rating = $request->customer_rating;
        CustomerRating::where('csm_id',$csm_id)->update(array(
            'five_star'     => $rating['five_star'],
            'four_star'     => $rating['four_star'],
            'three_below'   => $rating['three_below'],
            'csm_id'        => $csm_id,
        )); 
    }

    private function save_overall_rating($request, $csm_id){
        $overall_rating = new CustomerOverallRating;
        $overall_rating->response_delivery = $request->response_delivery;
        $overall_rating->work_quality = $request->work_quality;
        $overall_rating->personnels_quality = $request->personnels_quality;
        $overall_rating->overall_rating = $request->response_delivery;
        $overall_rating->csm_id = $csm_id;
        $overall_rating->save();

    }

    private function update_overall_rating($request, $csm_id){
        CustomerOverallRating::where('csm_id',$csm_id)->update(array(
            'response_delivery'     => $request->response_delivery,
            'work_quality'          => $request->work_quality,
            'personnels_quality'    => $request->personnels_quality,
            'overall_rating'        => $request->response_delivery,
            'csm_id'                => $csm_id,
        )); 
    }

    private function save_customer_satisfction_documents($request, $csm_id){
        //delete previous directory for other files if it exist
        $folder_name = $request->functional_unit.'-'.$request->year.'-Quarter'.$request->quarter;
        Storage::deleteDirectory('public/customer_satisfaction_measurements/'.$folder_name);
        //delete docs in man_rev_docs
        $deletedRows = CustomerSatisfactionDocuments::where('csm_id', $csm_id)->delete();

        foreach($request->file('other_files') as $file){
            
            // Get filename with the extension
            $filenameWithExt = $file->getClientOriginalName();
            // Get just filename
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            // Get just ext
            $extension = $file->getClientOriginalExtension();
            // Filename to store
            $fileNameToStore= $filename.'.'.$extension;
            // Upload file
            $path = $file->storeAs('public/customer_satisfaction_measurements/'.$folder_name, $fileNameToStore);

            $customer_satisfaction_documents = new CustomerSatisfactionDocuments;
            $customer_satisfaction_documents->file_name = $fileNameToStore;
            $customer_satisfaction_documents->csm_id = $csm_id;
            $customer_satisfaction_documents->save();

        }
    }

    

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        
        $data = [
            'all' => 'ALL'
        ];
        $year = [
            'all' => 'ALL'
        ];
        $quarter = [
            'all' => 'ALL' ,
            '1' => '1',
            '2' => '2',
            '3' => '3',
            '4' => '4'
        ];
        
        $this->get_data($data);
        $this->get_year($year);
        $customer_satisfaction_measurements = CustomerSatisfactionMeasurement::orderBy('id')->paginate(10);
        return view('customer_satisfaction_measurements.index')->with([
            'csm'       => $customer_satisfaction_measurements,
            'data'      => $data,
            'quarter'   => $quarter,
            'year'      => $year
        ]);
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $isPermitted = false;
        $this->check_permission($isPermitted, Auth::id(), 1);
        if(!$isPermitted){
            return view('pages.unauthorized');
        }

        $quarter = [
            '1' => '1',
            '2' => '2',
            '3' => '3',
            '4' => '4'
        ];
        $data = [];
        $this->get_data($data);
        return view('customer_satisfaction_measurements.create')->with(['data' =>  $data, 'quarter' => $quarter]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // return $request;
        $this->validate($request, [
            'functional_unit' => 'required',
            'year' => 'required',
            'quarter' => 'required',
            'total_customer' => 'required',
            'total_male' => 'required',
            'total_female' => 'required',
            'customer_classification' => 'required',
            'customer_rating' => 'required',
            'response_delivery' => 'required',
            'work_quality' => 'required',
            'personnels_quality' => 'required',
            'overall_rating' => 'required',
            'other_files[]' => 'nullable|mimes:doc,pdf,docx,zip',
            'comments' => 'nullable'
        ]);

        

        $csm_id = CustomerSatisfactionMeasurement::insertGetId([
            'functional_unit'   => $request->functional_unit,
            'year'              => $request->year,
            'quarter'           => $request->quarter,
            'total_customer'    => $request->total_customer,
            'total_female'      => $request->total_female,
            'total_male'        => $request->total_male,
            'comments'          => $request->comments
        ]); 

        $this->save_customer_classification($request, $csm_id);
        $this->save_customer_rating($request, $csm_id);
        $this->save_overall_rating($request, $csm_id);
        if($request->hasFile('other_files')){
            $this->save_customer_satisfction_documents($request, $csm_id);
        }
        
        return redirect('/csm');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $isPermitted = false;
        $this->check_permission($isPermitted, Auth::id(), 0);
        if(!$isPermitted){
            return view('pages.unauthorized');
        }
        $customer_satisfaction_measurement = CustomerSatisfactionMeasurement::find($id);
        $customer_satisfaction_documents = CustomerSatisfactionDocuments::all()->where('csm_id',$id);
        $customer_rating = CustomerRating::all()->where('csm_id',$id);
        $customer_classification = CustomerClassification::all()->where('csm_id',$id);
        $customer_overall_rating = CustomerOverallRating::all()->where('csm_id',$id);
        $other_files = '';
        foreach($customer_satisfaction_documents as $file){
            $other_files = $other_files.$file->file_name.', ';
        }
        $customer_satisfaction_measurement->other_files = $other_files;
        return view('customer_satisfaction_measurements.show')->with([
                'csm' => $customer_satisfaction_measurement,
                'customer_classification' => $customer_classification[0],
                'customer_overall_rating' => $customer_overall_rating[0],
                'customer_rating'         => $customer_rating[0]
            ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $isPermitted = false;
        $this->check_permission($isPermitted, Auth::id(), 2);
        if(!$isPermitted){
            return view('pages.unauthorized');
        }
        $customer_satisfaction_measurement = CustomerSatisfactionMeasurement::find($id);
        $customer_satisfaction_documents = CustomerSatisfactionDocuments::all()->where('csm_id',$id);
        $customer_rating = CustomerRating::all()->where('csm_id',$id);
        $customer_classification = CustomerClassification::all()->where('csm_id',$id);
        $customer_overall_rating = CustomerOverallRating::all()->where('csm_id',$id);
        $quarter = [
            '1' => '1',
            '2' => '2',
            '3' => '3',
            '4' => '4'
        ];
        $data = [];
        $this->get_data($data);
        $other_files = '';
        foreach($customer_satisfaction_documents as $file){
            $other_files = $other_files.$file->file_name.', ';
        }
        
    
        $customer_satisfaction_measurement->other_files = $other_files;
        return view('customer_satisfaction_measurements.edit')->with([
                'csm' => $customer_satisfaction_measurement,
                'customer_classification' => $customer_classification[0],
                'customer_overall_rating' => $customer_overall_rating[0],
                'customer_rating'         => $customer_rating[0],
                'data'                    => $data,
                'quarter'                 => $quarter
            ]);
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
            'functional_unit' => 'required',
            'year' => 'required',
            'quarter' => 'required',
            'total_customer' => 'required',
            'total_male' => 'required',
            'total_female' => 'required',
            'customer_classification' => 'required',
            'customer_rating' => 'required',
            'response_delivery' => 'required',
            'work_quality' => 'required',
            'personnels_quality' => 'required',
            'overall_rating' => 'required',
            'other_files[]' => 'nullable|mimes:doc,pdf,docx,zip',
            'comments' => 'nullable'
        ]);
        
        
        $old_csm = CustomerSatisfactionMeasurement::find($id);
        //rename directory if folder was changed
        $old_dir = $old_csm->functional_unit.'-'.$old_csm->year.'-Quarter'.$old_csm->quarter;
        $new_dir = $request->functional_unit.'-'.$request->year.'-Quarter'.$request->quarter;
        if($old_dir !== $new_dir){
            Storage::move('public/customer_satisfaction_measurements/'.$old_dir , 'public/customer_satisfaction_measurements/'.$new_dir);
        }
            
        
        
        CustomerSatisfactionMeasurement::where('id',$id)->update(array(
            'functional_unit'   => $request->functional_unit,
            'year'              => $request->year,
            'quarter'           => $request->quarter,
            'total_customer'    => $request->total_customer,
            'total_female'      => $request->total_female,
            'total_male'        => $request->total_male,
            'comments'          => $request->comments
        )); 
        

        $this->update_customer_classification($request, $id);
        
        $this->update_customer_rating($request, $id);
        
        $this->update_overall_rating($request, $id);
        if($request->hasFile('other_files')){
            $this->save_customer_satisfction_documents($request, $id);
        }

        
        
        return redirect('/csm');
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

    public function download_supporting_documents($id){
        $isPermitted = false;
        $this->check_permission($isPermitted, Auth::id(), 3);
        if(!$isPermitted){
            return view('pages.unauthorized');
        }
        //the directory should be writable
        $customer_satisfaction_measurement = CustomerSatisfactionMeasurement::find($id);
        $folder_name = $customer_satisfaction_measurement->functional_unit.'-'.$customer_satisfaction_measurement->year.'-Quarter'.$customer_satisfaction_measurement->quarter;
        $directory = 'storage/customer_satisfaction_measurements/'.$folder_name.'/*';

        $files = glob(public_path($directory));
        $storage_path = public_path('storage/downloads/');
        $zip_name = $folder_name.'-'.time().'.zip';
        Zipper::make($storage_path.$zip_name)->add($files)->close();
        return Storage::download('public/downloads/'.$zip_name);
    }

    public function filter(Request $request){
        
        $data = ['all' => 'ALL'];
        $year = ['all' => 'ALL'];
        $quarter = [
            'all' => 'ALL' ,
            '1' => '1',
            '2' => '2',
            '3' => '3',
            '4' => '4'
        ];
        $this->get_data($data);
        $this->get_year($year);

        
        if($request->functional_unit === 'all' && $request->year === 'all' && $request->quarter === 'all'){
            $customer_satisfaction_measurements = CustomerSatisfactionMeasurement::orderBy('id')->paginate(10);
        }
        if($request->functional_unit !== 'all' && $request->year === 'all' && $request->quarter === 'all'){
            $customer_satisfaction_measurements = CustomerSatisfactionMeasurement::where('functional_unit', $request->functional_unit)->paginate(10);
        }
        if($request->functional_unit !== 'all' && $request->year !== 'all' && $request->quarter === 'all'){
            $customer_satisfaction_measurements = CustomerSatisfactionMeasurement::where([
                    ['functional_unit', $request->functional_unit],
                    ['year', $request->year]
                ])->paginate(10);
        }
        if($request->functional_unit !== 'all' && $request->year !== 'all' && $request->quarter !== 'all'){
            $customer_satisfaction_measurements = CustomerSatisfactionMeasurement::where([
                    ['functional_unit', $request->functional_unit],
                    ['year', $request->year],
                    ['quarter', $request->quarter]
                ])->paginate(10);
        }
        if($request->functional_unit !== 'all' && $request->year === 'all' && $request->quarter !== 'all'){
            $customer_satisfaction_measurements = CustomerSatisfactionMeasurement::where([
                    ['functional_unit', $request->functional_unit],
                    ['quarter', $request->quarter]
                ])->paginate(10);
        }
        if($request->functional_unit === 'all' && $request->year !== 'all' && $request->quarter !== 'all'){
            $customer_satisfaction_measurements = CustomerSatisfactionMeasurement::where([
                    ['quarter', $request->quarter],
                    ['year', $request->year]
                ])->paginate(10);
        }
        if($request->functional_unit === 'all' && $request->year !== 'all' && $request->quarter === 'all'){
            $customer_satisfaction_measurements = CustomerSatisfactionMeasurement::where('year', $request->year)->paginate(10);
        }
        if($request->functional_unit === 'all' && $request->year === 'all' && $request->quarter !== 'all'){
            $customer_satisfaction_measurements = CustomerSatisfactionMeasurement::where('quarter', $request->quarter)->paginate(10);
        }

        return view('customer_satisfaction_measurements.index')->with([
            'csm'       => $customer_satisfaction_measurements,
            'data'      => $data,
            'quarter'   => $quarter,
            'year'      => $year
        ]);
    }

}
