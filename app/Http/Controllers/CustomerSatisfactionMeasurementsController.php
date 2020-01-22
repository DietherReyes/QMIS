<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\CustomerSatisfactionMeasurement;
use App\CustomerSatisfactionService;
use App\CustomerSatisfactionAddress;
use App\CustomerClassification;
use App\CustomerOtherClassification;
use App\CustomerOverallRating;
use App\CustomerRating;
use App\CustomerSatisfactionDocuments;
use App\CustomerAddress;
use App\CustomerServicesOffered;
use App\FunctionalUnit;
use App\User;
use App\Charts\CustomerSatisfactionChart;
use Illuminate\Support\Facades\Storage;
use Zipper;
use Auth;


class CustomerSatisfactionMeasurementsController extends Controller
{

    public function __construct(){
        $this->middleware('auth');

        $this->custom_messages = [
            'required' => 'This field is required',
            'numeric'  => 'This field requires numeric input'
        ];
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
        $functional_units = FunctionalUnit::orderBy('name', 'ASC')->get();
        foreach($functional_units as $functional_unit){
            $data[$functional_unit->name] = $functional_unit->name;
        }
    }

    private function get_year(&$year){
        $csm_years = CustomerSatisfactionMeasurement::orderBy('year', 'DESC')->pluck('year');
        foreach($csm_years as $csm_year){
            $year[$csm_year] = $csm_year;
        }
    }

    private function save_customer_classification($request, $csm_id){
        
        $classification = [
            'student'               => 0,
            'government_employee'   => 0,
            'internal'              => 0,
            'business'              => 0,
            'homemaker'             => 0,
            'private_organization'  => 0,
            'entrepreneur'          => 0
        ];

        $input_classification = $request->classification;
        $input_classification_count = $request->classification_count;
        $count = count($input_classification);

        for($i = 0; $i < $count; $i++){
            $classification[$input_classification[$i]] += $input_classification_count[$i];
        }

        $customer_classification = new CustomerClassification;
        $customer_classification->student               = $classification['student'];
        $customer_classification->government_employee   = $classification['government_employee'];
        $customer_classification->internal              = $classification['internal'];
        $customer_classification->business              = $classification['business'];
        $customer_classification->homemaker             = $classification['homemaker'];
        $customer_classification->entrepreneur          = $classification['entrepreneur'];
        $customer_classification->private_organization  = $classification['private_organization'];
        $customer_classification->csm_id                = $csm_id;
        $customer_classification->save();

    }

    private function update_customer_classification($request, $csm_id){
        $classification = [
            'student'               => 0,
            'government_employee'   => 0,
            'internal'              => 0,
            'business'              => 0,
            'homemaker'             => 0,
            'private_organization'  => 0,
            'entrepreneur'          => 0
        ];

        $input_classification = $request->classification;
        $input_classification_count = $request->classification_count;
        $count = count($input_classification);

        for($i = 0; $i < $count; $i++){
            $classification[$input_classification[$i]] += $input_classification_count[$i];
        }
        CustomerClassification::where('csm_id',$csm_id)->update(array(
            'student'               => $classification['student'],
            'government_employee'   => $classification['government_employee'],
            'internal'              => $classification['internal'],
            'business'              => $classification['business'],
            'homemaker'             => $classification['homemaker'],
            'entrepreneur'          => $classification['entrepreneur'],
            'private_organization'  => $classification['private_organization'],
            'csm_id'                => $csm_id
        )); 
    }

    private function save_customer_other_classification($request, $csm_id){
        
        $deletedRows = CustomerOtherClassification::where('csm_id', $csm_id)->delete();

        $input_classification = $request->other_classification;
        $input_classification_count = $request->other_classification_count;
        $count = count($input_classification);

        for($i = 0; $i < $count; $i++){
            $customer_classification = new CustomerOtherClassification;
            $customer_classification->name      = $input_classification[$i];
            $customer_classification->count     = $input_classification_count[$i];
            $customer_classification->csm_id    = $csm_id;
            $customer_classification->save();
        }
    }

    private function save_addresses($request, $csm_id){
        $deletedRows = CustomerAddress::where('csm_id', $csm_id)->delete();

        $addresses = $request->address;
        $count = $request->address_count;
        for($i = 0; $i < count($addresses); $i++){
            $address = new CustomerAddress;
            $address->address = $addresses[$i];
            $address->count = $count[$i];
            $address->csm_id = $csm_id;
            $address->save();
        }


    }

    private function save_services($request, $csm_id){
        $deletedRows = CustomerServicesOffered::where('csm_id', $csm_id)->delete();
        $services = $request->service;
        $count = $request->service_count;
        
        for($i = 0; $i < count($services); $i++){
            $service = new CustomerServicesOffered;
            $service->service_name = $services[$i];
            $service->count = $count[$i];
            $service->csm_id = $csm_id;
            $service->save();
        }


    }

    private function save_customer_rating($request, $csm_id){
        $customer_rating = new CustomerRating;
        $customer_rating->five_star = $request->five_star;
        $customer_rating->four_star = $request->four_star;
        $customer_rating->three_below = $request->three_below;
        $customer_rating->csm_id = $csm_id;
        $customer_rating->save();
    }

    private function update_customer_rating($request, $csm_id){
        CustomerRating::where('csm_id',$csm_id)->update(array(
            'five_star'     => $request->five_star,
            'four_star'     => $request->four_star,
            'three_below'   => $request->three_below,
            'csm_id'        => $csm_id,
        )); 
    }

    private function save_overall_rating($request, $csm_id){
        $overall_rating = new CustomerOverallRating;
        $overall_rating->response_delivery = $request->response_delivery;
        $overall_rating->work_quality = $request->work_quality;
        $overall_rating->personnels_quality = $request->personnels_quality;
        $overall_rating->overall_rating = $request->overall_rating;
        $overall_rating->csm_id = $csm_id;
        $overall_rating->save();

    }

    private function update_overall_rating($request, $csm_id){
        CustomerOverallRating::where('csm_id',$csm_id)->update(array(
            'response_delivery'     => $request->response_delivery,
            'work_quality'          => $request->work_quality,
            'personnels_quality'    => $request->personnels_quality,
            'overall_rating'        => $request->overall_rating,
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
        $customer_satisfaction_measurements = CustomerSatisfactionMeasurement::orderBy('functional_unit')->orderBy('year', 'DESC')->orderBy('quarter')->paginate(10);
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

        $classifications = [
            'student'               => 'Student',
            'government_employee'   => 'Government Employee',
            'internal'              => 'Internal',
            'business'              => 'Business',
            'homemaker'             => 'Homemaker',
            'private_organization'  => 'Private Organization',
            'entrepreneur'          => 'Entrepreneur'
        ];

        $addresses = CustomerSatisfactionAddress::orderBy('name')->pluck('name');
        $services = CustomerSatisfactionService::orderBy('name')->pluck('name');

        $data = [];
        $this->get_data($data);
        return view('customer_satisfaction_measurements.create')->with([
            'data'              =>  $data, 
            'quarter'           =>  $quarter,
            'classifications'   =>  $classifications,
            'addresses'         =>  $addresses,
            'services'          =>  $services
        ]);
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
            'functional_unit'               => 'required',
            'year'                          => 'required|numeric',
            'quarter'                       => 'required',
            'total_customer'                => 'required|numeric|min:1',
            'total_male'                    => 'required|numeric|min:0',
            'total_female'                  => 'required|numeric|min:0',
            'classification'                => 'required',
            'classification_count'          => 'required',
            'other_classification'          => 'required',
            'other_classification_count'    => 'required',
            'address'                       => 'required',
            'address_count'                 => 'required',
            'service'                       => 'required',
            'service_count'                 => 'required',
            'five_star'                     => 'required|numeric',
            'four_star'                     => 'required|numeric',
            'three_below'                   => 'required|numeric',
            'response_delivery'             => 'required|numeric|min:1|max:5',
            'work_quality'                  => 'required|numeric|min:1|max:5',
            'personnels_quality'            => 'required|numeric|min:1|max:5',
            'overall_rating'                => 'required|numeric|min:1|max:5',
            "supporting_documents"          => "required",
            "supporting_documents.*"        => "required|file|mimes:pdf,doc,xls,ppt",
            'comments'                      => 'nullable'
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
        $this->save_addresses($request, $csm_id);
        $this->save_services($request, $csm_id);
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
        $customer_satisfaction_documents = CustomerSatisfactionDocuments::where('csm_id',$id)->get();
        $customer_rating = CustomerRating::where('csm_id',$id)->get()[0];
        $customer_classification = CustomerClassification::where('csm_id',$id)->get()[0];
        $customer_overall_rating = CustomerOverallRating::where('csm_id',$id)->get()[0];
        $customer_addresses = CustomerAddress::where('csm_id', $id)->get();
        $customer_services_offered = CustomerServicesOffered::where('csm_id', $id)->get();
        
        $this->get_data($data);
        $other_files = '';
        foreach($customer_satisfaction_documents as $file){
            $other_files = $other_files.$file->file_name.', ';
        }
        
        $customer_satisfaction_measurement->other_files = $other_files;
        return view('customer_satisfaction_measurements.show')->with([
                'csm' => $customer_satisfaction_measurement,
                'customer_classification'   => $customer_classification,
                'customer_overall_rating'   => $customer_overall_rating,
                'customer_rating'           => $customer_rating,
                'customer_addresses'        => $customer_addresses,
                'customer_services_offered' => $customer_services_offered, 
                
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
        $customer_satisfaction_documents = CustomerSatisfactionDocuments::where('csm_id',$id)->get();
        $customer_rating = CustomerRating::where('csm_id',$id)->get()[0];
        $customer_classification = CustomerClassification::where('csm_id',$id)->get()[0];
        $customer_overall_rating = CustomerOverallRating::where('csm_id',$id)->get()[0];
        $customer_addresses = CustomerAddress::where('csm_id', $id)->get();
        $customer_services_offered = CustomerServicesOffered::where('csm_id', $id)->get();
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
                'customer_classification'   => $customer_classification,
                'customer_overall_rating'   => $customer_overall_rating,
                'customer_rating'           => $customer_rating,
                'customer_addresses'        => $customer_addresses,
                'customer_services_offered' => $customer_services_offered, 
                'data'                      => $data,
                'quarter'                   => $quarter
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
            'address' => 'required',
            'address_count' => 'required',
            'service' => 'required',
            'service_count' => 'required',
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
        $this->save_addresses($request, $id);
        $this->save_services($request, $id);

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
            $customer_satisfaction_measurements = CustomerSatisfactionMeasurement::orderBy('functional_unit')->orderBy('year', 'DESC')->orderBy('quarter')->paginate(10);
        }
        if($request->functional_unit !== 'all' && $request->year === 'all' && $request->quarter === 'all'){
            $customer_satisfaction_measurements = CustomerSatisfactionMeasurement::where('functional_unit', $request->functional_unit)->orderBy('functional_unit')->orderBy('year', 'DESC')->orderBy('quarter')->paginate(10);
        }
        if($request->functional_unit !== 'all' && $request->year !== 'all' && $request->quarter === 'all'){
            $customer_satisfaction_measurements = CustomerSatisfactionMeasurement::where([
                    ['functional_unit', $request->functional_unit],
                    ['year', $request->year]
                ])->orderBy('functional_unit')->orderBy('year', 'DESC')->orderBy('quarter')->paginate(10);
        }
        if($request->functional_unit !== 'all' && $request->year !== 'all' && $request->quarter !== 'all'){
            $customer_satisfaction_measurements = CustomerSatisfactionMeasurement::where([
                    ['functional_unit', $request->functional_unit],
                    ['year', $request->year],
                    ['quarter', $request->quarter]
                ])->orderBy('functional_unit')->orderBy('year', 'DESC')->orderBy('quarter')->paginate(10);
        }
        if($request->functional_unit !== 'all' && $request->year === 'all' && $request->quarter !== 'all'){
            $customer_satisfaction_measurements = CustomerSatisfactionMeasurement::where([
                    ['functional_unit', $request->functional_unit],
                    ['quarter', $request->quarter]
                ])->orderBy('functional_unit')->orderBy('year', 'DESC')->orderBy('quarter')->paginate(10);
        }
        if($request->functional_unit === 'all' && $request->year !== 'all' && $request->quarter !== 'all'){
            $customer_satisfaction_measurements = CustomerSatisfactionMeasurement::where([
                    ['quarter', $request->quarter],
                    ['year', $request->year]
                ])->orderBy('functional_unit')->orderBy('year', 'DESC')->orderBy('quarter')->paginate(10);
        }
        if($request->functional_unit === 'all' && $request->year !== 'all' && $request->quarter === 'all'){
            $customer_satisfaction_measurements = CustomerSatisfactionMeasurement::where('year', $request->year)->orderBy('functional_unit')->orderBy('year', 'DESC')->orderBy('quarter')->paginate(10);
        }
        if($request->functional_unit === 'all' && $request->year === 'all' && $request->quarter !== 'all'){
            $customer_satisfaction_measurements = CustomerSatisfactionMeasurement::where('quarter', $request->quarter)->orderBy('functional_unit')->orderBy('year', 'DESC')->orderBy('quarter')->paginate(10);
        }

        return view('customer_satisfaction_measurements.index')->with([
            'csm'       => $customer_satisfaction_measurements,
            'data'      => $data,
            'quarter'   => $quarter,
            'year'      => $year
        ]);
    }

    //graphs
    private function get_quarters(&$labels, $unit, $unit_year){
        $csm_quarters = CustomerSatisfactionMeasurement::where([
            ['functional_unit', $unit->name],
            ['year', $unit_year]
        ])->pluck('quarter');
        foreach($csm_quarters as $quarter){
            array_push($labels, 'Q'.$quarter); 
        }
    }

    private function get_overall_ratings(&$overall_ratings, $csm_ids){
        foreach($csm_ids as $id){
            $csm = CustomerOverallRating::find($id);
            array_push($overall_ratings, $csm->overall_rating);
        }
    }

    private function get_customer_ratings(&$customer_ratings, $csm_ids){
        foreach($csm_ids as $id){
            $csm = CustomerRating::find($id);
            $customer_ratings['five_star'] += $csm->five_star;
            $customer_ratings['four_star'] += $csm->four_star;
            $customer_ratings['three_below'] += $csm->three_below;
        }
    }


    private function get_customer_addresses(&$customer_address_names, &$customer_address_count, $csm_ids){
        $temp = [];
        foreach($csm_ids as $id){
            $customer_addresses = CustomerAddress::where('csm_id', $id)->get();
            foreach($customer_addresses as $address){
                if(!array_key_exists($address->address, $temp)){
                    array_push($customer_address_names, $address->address);
                    $temp[$address->address] = 0;
                }
                
            }

           
        }

        foreach($csm_ids as $id){
            $customer_addresses = CustomerAddress::where('csm_id', $id)->get();
            foreach($customer_addresses as $address){
                $temp[$address->address] += $address->count;    
            }
        }
        
        foreach($customer_address_names as $service_name){
            array_push($customer_address_count, $temp[$service_name]);
        }
        
    }

    private function get_customer_services(&$customer_services_names, &$customer_services_count, $csm_ids){
        $temp = [];
        foreach($csm_ids as $id){
            $customer_services_offered = CustomerServicesOffered::where('csm_id', $id)->get();
            foreach($customer_services_offered as $service){
                if(!array_key_exists($service->service_name, $temp)){
                    array_push($customer_services_names, $service->service_name);
                    $temp[$service->service_name] = 0;
                }
                
            }

           
        }

        foreach($csm_ids as $id){
            $customer_services_offered = CustomerServicesOffered::where('csm_id', $id)->get();
            foreach($customer_services_offered as $service){
                $temp[$service->service_name] += $service->count;    
            }
        }
        
        foreach($customer_services_names as $service_name){
            array_push($customer_services_count, $temp[$service_name]);
        }
        
    }



    private function create_overall_ratings_chart(&$overall_ratings_chart, $csm_ids, $unit, $unit_year){
        $labels = [];
        $this->get_quarters($labels, $unit, $unit_year);
        $overall_ratings = [];
        $this->get_overall_ratings($overall_ratings, $csm_ids);

        $title = $unit->name.' Overall Ratings Year '.$unit_year;
        $overall_ratings_chart->title($title, 14,'#666',true, "'Helvetica Neue', 'Helvetica', 'Arial', sans-serif");
        $overall_ratings_chart->labels($labels);
        $overall_ratings_chart->dataset(' Overall Ratings Year '.$unit_year, 'line', $overall_ratings)->options([
            'borderColor' => '#3281c7',
            'backgroundColor' => '#3281c7',
            'fill' => 'false',
            'lineTension' => '.001',
            'title' => 'allaallaall'
        ]);

    }

    private function create_customer_ratings_chart(&$customer_ratings_chart, $csm_ids, $unit, $unit_year){
        
        $customer_ratings = [
            'five_star' => 0,
            'four_star' => 0,
            'three_below' => 0
        ];

        $colors = [
            '#3281c7',
            '#38a2d9',
            '#6ed2ec'
        ];
        $this->get_customer_ratings($customer_ratings, $csm_ids);
        $title = $unit->name.' Total Customer Ratings Year '.$unit_year;
        $customer_ratings_chart->title($title, 14,'#666',true, "'Helvetica Neue', 'Helvetica', 'Arial', sans-serif");
        $customer_ratings_chart->labels(['5 Stars', '4 Stars', '3 Stars or Below']);
        $dataset = $customer_ratings_chart->dataset(' Total Customer Ratings Year '.$unit_year, 'bar', [$customer_ratings['five_star'], $customer_ratings['four_star'], $customer_ratings['three_below']]);
        $dataset->backgroundColor(collect($colors));
        $dataset->color(collect($colors));
    }

    private function create_customer_services_chart(&$customer_services_chart, $csm_ids, $unit, $unit_year){
        $customer_services_names = [];
        $customer_services_count = [];
        $this->get_customer_services($customer_services_names, $customer_services_count, $csm_ids);

        $colors = [
            '#3281c7',
            '#3192d1',
            '#38a2d9',
            '#46b2e0',
            '#59c2e6',
            '#6ed2ec',
            '#84e1f2',
            '#9cf0f8',
            '#b4ffff',
            '#c3ffff',
            '#d1ffff',
            '#dfffff',
            '#ecffff'
        ];

        $title = $unit->name.' Services Offered Year '.$unit_year;
        $customer_services_chart->title($title, 14,'#666',true, "'Helvetica Neue', 'Helvetica', 'Arial', sans-serif");
        $customer_services_chart->labels($customer_services_names);
        $customer_services_chart->displayAxes(false);
        $dataset = $customer_services_chart->dataset(' Services Offered Year '.$unit_year, 'pie', $customer_services_count);
        $dataset->backgroundColor(collect($colors));
        $dataset->color(collect($colors));
        
        
        
    }

    private function create_customer_addresses_chart(&$customer_addresses_chart, $csm_ids, $unit, $unit_year){
        $customer_adress_names = [];
        $customer_adress_count = [];
        $this->get_customer_addresses($customer_adress_names, $customer_adress_count, $csm_ids);
        
        $colors = [
            '#3281c7',
            '#3192d1',
            '#38a2d9',
            '#46b2e0',
            '#59c2e6',
            '#6ed2ec',
            '#84e1f2',
            '#9cf0f8',
            '#b4ffff',
            '#c3ffff',
            '#d1ffff',
            '#dfffff',
            '#ecffff'
        ];
        $title = $unit->name.' Customer Addresses Year '.$unit_year;
        $customer_addresses_chart->title($title, 14,'#666',true, "'Helvetica Neue', 'Helvetica', 'Arial', sans-serif");
        $customer_addresses_chart->labels($customer_adress_names);
        $customer_addresses_chart->displayAxes(false);
        $dataset =  $customer_addresses_chart->dataset(' Customer Addresses Year '.$unit_year, 'pie', $customer_adress_count);
        $dataset->backgroundColor(collect($colors));
        $dataset->color(collect($colors));
    }


    public function graphs(){

        $data = [];
        $years = [];
        $this->get_data($data);
        $this->get_year($years);

        $unit = FunctionalUnit::orderBy('name', 'ASC')->get()[0];
        $unit_year = CustomerSatisfactionMeasurement::orderBy('year', 'DESC')->pluck('year');
        if(count($unit_year) == 0){
            return 'Insufficent Records';
        }else{
            $unit_year = $unit_year[0];
        }

        $csm_ids = CustomerSatisfactionMeasurement::where([
            ['functional_unit', $unit->name],
            ['year', $unit_year]
        ])->orderBy('quarter' , 'ASC')->pluck('id');

        

        $overall_ratings_chart = new CustomerSatisfactionChart;
        $this->create_overall_ratings_chart($overall_ratings_chart, $csm_ids, $unit, $unit_year);

        $customer_ratings_chart = new CustomerSatisfactionChart;
        $this->create_customer_ratings_chart($customer_ratings_chart, $csm_ids, $unit, $unit_year);

        $customer_services_chart = new CustomerSatisfactionChart;
        $this->create_customer_services_chart($customer_services_chart, $csm_ids, $unit, $unit_year);

        $customer_addresses_chart = new CustomerSatisfactionChart;
        $this->create_customer_addresses_chart($customer_addresses_chart, $csm_ids, $unit, $unit_year);


        return view('customer_satisfaction_measurements.graphs')->with([
            'data' => $data,
            'years' => $years,
            'unit_name' => $unit->name,
            'unit_year' => $unit_year,
            'overall_ratings_chart' => $overall_ratings_chart,
            'customer_ratings_chart' => $customer_ratings_chart,
            'customer_services_chart' => $customer_services_chart,
            'customer_addresses_chart' => $customer_addresses_chart
            
        ]);
    }


    public function search_graphs(Request $request){

        $data = [];
        $years = [];
        $this->get_data($data);
        $this->get_year($years);

        $unit = FunctionalUnit::where('name', $request->functional_unit)->get()[0];
        $unit_year = $request->year;
        $csm_ids = CustomerSatisfactionMeasurement::where([
            ['functional_unit', $unit->name],
            ['year', $unit_year]
        ])->orderBy('quarter' , 'ASC')->pluck('id');

        

        $overall_ratings_chart = new CustomerSatisfactionChart;
        $this->create_overall_ratings_chart($overall_ratings_chart, $csm_ids, $unit, $unit_year);

        $customer_ratings_chart = new CustomerSatisfactionChart;
        $this->create_customer_ratings_chart($customer_ratings_chart, $csm_ids, $unit, $unit_year);

        $customer_services_chart = new CustomerSatisfactionChart;
        $this->create_customer_services_chart($customer_services_chart, $csm_ids, $unit, $unit_year);

        $customer_addresses_chart = new CustomerSatisfactionChart;
        $this->create_customer_addresses_chart($customer_addresses_chart, $csm_ids, $unit, $unit_year);


        return view('customer_satisfaction_measurements.graphs')->with([
            'data' => $data,
            'years' => $years,
            'unit_name' => $unit->name,
            'unit_year' => $unit_year,
            'overall_ratings_chart' => $overall_ratings_chart,
            'customer_ratings_chart' => $customer_ratings_chart,
            'customer_services_chart' => $customer_services_chart,
            'customer_addresses_chart' => $customer_addresses_chart
            
        ]);
    }

    //
    //  Services Functions
    //

    public function get_services(){

        $services = CustomerSatisfactionService::orderBy('name')->paginate(10);
        return view('customer_satisfaction_measurements.services_index')->with('services', $services);
    }

    public function search_service(Request $request){
        $services = CustomerSatisfactionService::where('name', 'like', '%'.$request->search_term.'%')->orderBy('name')->paginate(10);
        return view('customer_satisfaction_measurements.services_index')->with('services', $services);
    }

    public function add_service()
    {
        return view('customer_satisfaction_measurements.services_add');
    }

    public function store_service(Request $request){
        $this->validate($request, [
            'name' => 'required'
        ], $this->custom_messages);

        $service = new CustomerSatisfactionService;
        $service->name = $request->name;
        $service->save();

        return redirect('/csm/services/idx');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit_service($id)
    {
        $service = CustomerSatisfactionService::find($id);
        return view('customer_satisfaction_measurements.services_edit')->with('service', $service);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update_service(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
        ], $this->custom_messages);
        
       

        CustomerSatisfactionService::where('id',$id)->update(array(
            'name'      => $request->name,
        ));

        return redirect('/csm/services/idx');
    }


    //
    //  Addresses Functions
    //

    public function get_addresses(){

        $addresses = CustomerSatisfactionAddress::orderBy('name')->paginate(10);
        return view('customer_satisfaction_measurements.addresses_index')->with('addresses', $addresses);
    }

    public function search_address(Request $request){
        $addresses = CustomerSatisfactionAddress::where('name', 'like', '%'.$request->search_term.'%')->orderBy('name')->paginate(10);
        return view('customer_satisfaction_measurements.addresses_index')->with('addresses', $addresses);
    }

    public function add_address()
    {
        return view('customer_satisfaction_measurements.addresses_add');
    }

    public function store_address(Request $request){
        $this->validate($request, [
            'name' => 'required'
        ], $this->custom_messages);

        $address = new CustomerSatisfactionAddress;
        $address->name = $request->name;
        $address->save();

        return redirect('/csm/addresses/idx');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit_address($id)
    {
        $address = CustomerSatisfactionAddress::find($id);
        return view('customer_satisfaction_measurements.addresses_edit')->with('address', $address);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update_address(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
        ], $this->custom_messages);
        
       

        CustomerSatisfactionAddress::where('id',$id)->update(array(
            'name'      => $request->name,
        ));

        return redirect('/csm/addresses/idx');
    }

    

}

