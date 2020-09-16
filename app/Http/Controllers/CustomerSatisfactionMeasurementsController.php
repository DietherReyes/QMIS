<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\CustomerSatisfactionMeasurementSummary;
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
use App\Log;
use App\Charts\CustomerSatisfactionChart;
use Illuminate\Support\Facades\Storage;
use Zipper;
use Auth;
use Validator;



class CustomerSatisfactionMeasurementsController extends Controller
{

    public function __construct(){

        //Check if the user is authenticated
        $this->middleware('auth');
        $this->middleware('account_status');

        //Custom error messages for form validation
        $this->custom_messages = [
            'required'      => 'This field is required.',
            'numeric'       => 'This field requires numeric input.',
            'year.digits'   => 'The input must be a 4-digit number.',  
            'min'           => 'This field requires numeric input greater than or equal to :min.',
            'max'           => 'This field requires numeric input less than or equal to :max.',
            'file'          => 'This field requires fiele input.'
        ];

        //Permission indeces
        $this->view_csm = 0;
        $this->add_csm  = 1;
        $this->edit_csm = 2;

        //Colors for pie graph
        $this->colors = [
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
    }

    //Checks if the user is authorized to 
    private function check_permission(&$isPermitted, $user_id, $permission){
        $user = User::find(Auth::id());
        $user->permission = explode(',', $user->permission);
        if($user->permission[$permission] === '1' || $user->role === 'admin'){
            $isPermitted = true;
        }
    }

    //Gets all functional unit name for dropdown data
    private function get_data(&$data){
        $functional_units = FunctionalUnit::orderBy('name', 'ASC')->get();
        foreach($functional_units as $functional_unit){
            $data[$functional_unit->name] = $functional_unit->name;
        }
    }

    //Get all csm report years for dropdown data
    private function get_year(&$year){
        $csm_years = CustomerSatisfactionMeasurement::orderBy('year', 'DESC')->pluck('year');
        foreach($csm_years as $csm_year){
            $year[$csm_year] = $csm_year;
        }
    }

    //Save customer classification
    private function save_customer_classification($request, $csm_id){
        
        $classification = [
            'Student'               => 0,
            'Government Employee'   => 0,
            'Internal'              => 0,
            'Business'              => 0,
            'Homemaker'             => 0,
            'Private Organization'  => 0,
            'Entrepreneur'          => 0
        ];

        $input_classification = $request->classification;
        $input_classification_count = $request->classification_count;
        $count = count($input_classification);

        //Change the value of the classification array
        for($i = 0; $i < $count; $i++){
            $classification[$input_classification[$i]] += $input_classification_count[$i];
        }

        $customer_classification = new CustomerClassification;
        $customer_classification->student               = $classification['Student'];
        $customer_classification->government_employee   = $classification['Government Employee'];
        $customer_classification->internal              = $classification['Internal'];
        $customer_classification->business              = $classification['Business'];
        $customer_classification->homemaker             = $classification['Homemaker'];
        $customer_classification->entrepreneur          = $classification['Entrepreneur'];
        $customer_classification->private_organization  = $classification['Private Organization'];
        $customer_classification->csm_id                = $csm_id;
        $customer_classification->save();

    }

    //Update Customer Classification
    private function update_customer_classification($request, $csm_id){

        //If other classification is null the delete all other classification
        if($request->other_classification === null){
            $deletedRows = CustomerOtherClassification::where('csm_id', $csm_id)->delete();
        }

        $classification = [
            'Student'               => 0,
            'Government Employee'   => 0,
            'Internal'              => 0,
            'Business'              => 0,
            'Homemaker'             => 0,
            'Private Organization'  => 0,
            'Entrepreneur'          => 0
        ];

        $input_classification        = $request->classification;
        $input_classification_count  = $request->classification_count;
        $count                       = count($input_classification);

        for($i = 0; $i < $count; $i++){
            $classification[$input_classification[$i]] += $input_classification_count[$i];
        }
        CustomerClassification::where('csm_id',$csm_id)->update(array(
            'student'               => $classification['Student'],
            'government_employee'   => $classification['Government Employee'],
            'internal'              => $classification['Internal'],
            'business'              => $classification['Business'],
            'homemaker'             => $classification['Homemaker'],
            'entrepreneur'          => $classification['Entrepreneur'],
            'private_organization'  => $classification['Private Organization'],
            'csm_id'                => $csm_id
        )); 
    }

    //Save other classification
    private function save_customer_other_classification($request, $csm_id){
        
        //Delete current other classification
        $deletedRows = CustomerOtherClassification::where('csm_id', $csm_id)->delete();

        $input_classification        = $request->other_classification;
        $input_classification_count  = $request->other_classification_count;
        $count                       = count($input_classification);

        for($i = 0; $i < $count; $i++){
            $customer_classification = new CustomerOtherClassification;
            $customer_classification->name      = $input_classification[$i];
            $customer_classification->count     = $input_classification_count[$i];
            $customer_classification->csm_id    = $csm_id;
            $customer_classification->save();
        }
    }

    //Save Customer Addresses
    private function save_addresses($request, $csm_id){

        //Delete current addresses
        $deletedRows = CustomerAddress::where('csm_id', $csm_id)->delete();

        $addresses  = $request->address;
        $count      = $request->address_count;
        
        for($i = 0; $i < count($addresses); $i++){
            $address = new CustomerAddress;
            $address->address  = $addresses[$i];
            $address->count    = $count[$i];
            $address->csm_id   = $csm_id;
            $address->save();
        }


    }

    //Save Services Offered
    private function save_services($request, $csm_id){

        //Delete current services
        $deletedRows = CustomerServicesOffered::where('csm_id', $csm_id)->delete();
        
        $services  = $request->service;
        $count     = $request->service_count;
        
        for($i = 0; $i < count($services); $i++){
            $service = new CustomerServicesOffered;
            $service->service_name  = $services[$i];
            $service->count         = $count[$i];
            $service->csm_id        = $csm_id;
            $service->save();
        }


    }


    //Save Customer Ratig
    private function save_customer_rating($request, $csm_id){
        $customer_rating = new CustomerRating;
        $customer_rating->five_star     = $request->five_star;
        $customer_rating->four_star     = $request->four_star;
        $customer_rating->three_below   = $request->three_below;
        $customer_rating->csm_id        = $csm_id;
        $customer_rating->save();
    }

    //Update Customer rating
    private function update_customer_rating($request, $csm_id){
        CustomerRating::where('csm_id',$csm_id)->update(array(
            'five_star'     => $request->five_star,
            'four_star'     => $request->four_star,
            'three_below'   => $request->three_below,
            'csm_id'        => $csm_id,
        )); 
    }

    //Save Overall Rating
    private function save_overall_rating($request, $csm_id){
        $overall_rating                      = new CustomerOverallRating;
        $overall_rating->response_delivery   = $request->response_delivery;
        $overall_rating->work_quality        = $request->work_quality;
        $overall_rating->personnels_quality  = $request->personnels_quality;
        $overall_rating->overall_rating      = $request->overall_rating;
        $overall_rating->csm_id              = $csm_id;
        $overall_rating->save();

    }

    //Update overll rating
    private function update_overall_rating($request, $csm_id){
        CustomerOverallRating::where('csm_id',$csm_id)->update(array(
            'response_delivery'     => $request->response_delivery,
            'work_quality'          => $request->work_quality,
            'personnels_quality'    => $request->personnels_quality,
            'overall_rating'        => $request->overall_rating,
            'csm_id'                => $csm_id,
        )); 
    }


    //Save supporting CSM Documents
    private function save_customer_satisfction_documents($request, $csm_id){

        //delete previous directory for other files if it exist
        $folder_name = $request->functional_unit.'-'.$request->year.'-Quarter'.$request->quarter;
        Storage::deleteDirectory('public/customer_satisfaction_measurements/'.$folder_name);

        //delete documents
        $deletedRows = CustomerSatisfactionDocuments::where('csm_id', $csm_id)->delete();
        foreach($request->file('supporting_documents') as $file){
            
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

    
    //Create dropdown data out of an array
    private function array_to_dropdown($input, &$output){
       foreach($input as $name){
           $output[$name] = $name;
       }

    }

    //Get Customer Classifications
    private function get_customer_classifications($csm_id, &$customer_classifications, &$customer_classifications_count){
        $csm_classification = CustomerClassification::where('csm_id',$csm_id)->get()[0];

        $keys = [
            'student',               
            'government_employee',   
            'internal',              
            'business',              
            'homemaker',             
            'private_organization',  
            'entrepreneur' 
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

        

        foreach($keys as $key){
            if($csm_classification->$key > 0){
                array_push($customer_classifications, $classifications[$key]);
                array_push($customer_classifications_count ,$csm_classification->$key);
            }
        }
    }

    //Update a CSM summary if the data from all quarters is complete 
    private function update_summary($functional_unit, $year){

        $summary = CustomerSatisfactionMeasurementSummary::where([
            ['functional_unit', $functional_unit],
            ['year', $year]
        ])->get()[0];

        //get all csm of a functional unit with a specified year
        $csms = CustomerSatisfactionMeasurement::where([
            ['functional_unit', $functional_unit],
            ['year', $year]
        ])->orderBy('quarter', 'ASC')->get();
        
        $count = count($csms);
        
        //If Q1 to Q4 data is complete then update values
        if($count === 4){


            $q1_ratings = CustomerOverallRating::where('csm_id', $csms[0]->id)->get()[0];
            $q2_ratings = CustomerOverallRating::where('csm_id', $csms[1]->id)->get()[0];
            $q3_ratings = CustomerOverallRating::where('csm_id', $csms[2]->id)->get()[0];
            $q4_ratings = CustomerOverallRating::where('csm_id', $csms[3]->id)->get()[0];

            $total_customer     = $csms[0]->total_customer + $csms[1]->total_customer + $csms[2]->total_customer + $csms[3]->total_customer;
            $q1_overall_rating  = $q1_ratings->overall_rating;
            $q2_overall_rating  = $q2_ratings->overall_rating;
            $q3_overall_rating  = $q3_ratings->overall_rating;
            $q4_overall_rating  = $q4_ratings->overall_rating;
            $response_delivery  =  ($q1_ratings->response_delivery + $q2_ratings->response_delivery + $q3_ratings->response_delivery + $q4_ratings->response_delivery) / $count;
            $work_quality       =  ($q1_ratings->work_quality + $q2_ratings->work_quality + $q3_ratings->work_quality + $q4_ratings->work_quality) / $count;
            $personnels_quality =  ($q1_ratings->personnels_quality + $q2_ratings->personnels_quality + $q3_ratings->personnels_quality + $q4_ratings->personnels_quality) / $count;
            $overall_rating     =  ($q1_ratings->overall_rating + $q2_ratings->overall_rating + $q3_ratings->overall_rating + $q4_ratings->overall_rating) / $count;
            
            //code for determining adjectoval rating

            $adjectival_rating = '';
            if($overall_rating >= 4.51 && $overall_rating <= 5){
                $adjectival_rating = 'Outstanding';
            }

            if($overall_rating >= 3.51 && $overall_rating <= 4.5){
                $adjectival_rating = 'Very Satisfactory';
            }

            if($overall_rating >= 2.51 && $overall_rating <= 3.5){
                $adjectival_rating = 'Satisfactory';
            }

            if($overall_rating >= 1.51 && $overall_rating <= 2.5){
                $adjectival_rating = 'Fair';
            }

            if($overall_rating <= 1.5){
                $adjectival_rating = 'Poor';
            }

            CustomerSatisfactionMeasurementSummary::where('id',$summary->id)->update(array(
                'total_customer'        => $total_customer,
                'q1_overall_rating'     => $q1_overall_rating,
                'q2_overall_rating'     => $q2_overall_rating,
                'q3_overall_rating'     => $q3_overall_rating,
                'q4_overall_rating'     => $q4_overall_rating,
                'response_delivery'     => $response_delivery,
                'work_quality'          => $work_quality,
                'personnels_quality'    => $personnels_quality,
                'overall_rating'        => $overall_rating,
                'adjectival_rating'     => $adjectival_rating
            ));

        }
        

    }

    //Nullify values if the functional unit/year of a CSm is changed
    private function nullify_summary($functional_unit, $year, $quarter){

        $summary = CustomerSatisfactionMeasurementSummary::where([
            ['functional_unit', $functional_unit],
            ['year', $year]
        ])->get()[0];

        
        CustomerSatisfactionMeasurementSummary::where('id',$summary->id)->update(array(
            'total_customer'        => null,
            'response_delivery'     => null,
            'work_quality'          => null,
            'personnels_quality'    => null,
            'overall_rating'        => null
        ));

        switch ($quarter) {
            case '1':
                CustomerSatisfactionMeasurementSummary::where('id',$summary->id)->update(array(
                    'q1_overall_rating'     => null
                ));
                break;

            case '2':
                CustomerSatisfactionMeasurementSummary::where('id',$summary->id)->update(array(
                    'q2_overall_rating'     => null
                ));
                break;

            case '3':
                CustomerSatisfactionMeasurementSummary::where('id',$summary->id)->update(array(
                    'q3_overall_rating'     => null
                ));
                break;

            case '4':
                CustomerSatisfactionMeasurementSummary::where('id',$summary->id)->update(array(
                    'q4_overall_rating'     => null
                ));
                break;
        }
    }

    //Checks if the functional unit and year exist in the database of summaries
    private function check_summary($request){

        $summary = CustomerSatisfactionMeasurementSummary::where([
            ['functional_unit', $request->functional_unit],
            ['year', $request->year]
        ])->get();

        //if summary does not exist then create summaries for each functional unit with the given year
        if(count($summary) === 0){

            $functional_units = FunctionalUnit::all();
            foreach($functional_units as $functional_unit){
                $new_summary                    = new CustomerSatisfactionMeasurementSummary;
                $new_summary->functional_unit   = $functional_unit->name;
                $new_summary->year              = $request->year;
                $new_summary->save();
            }

        }

        //add data to the summary
        $summary = CustomerSatisfactionMeasurementSummary::where([
            ['functional_unit', $request->functional_unit],
            ['year', $request->year]
        ])->get()[0];
        switch ($request->quarter) {
            case '1':
                $summary->q1_overall_rating = $request->overall_rating;
                break;

            case '2':
                $summary->q2_overall_rating = $request->overall_rating;
                break;

            case '3':
                $summary->q3_overall_rating = $request->overall_rating;
                break;

            case '4':
                $summary->q4_overall_rating = $request->overall_rating;
                break;
        }
        $summary->save();
             
        

        $this->update_summary($request->functional_unit, $request->year);
    }

    

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        
        //dropdown data
        $data = [
            'all' => 'ALL'
        ];
        $year = [
            'all' => 'ALL'
        ];
        $quarter = [
            'all' => 'ALL' ,
            '1'   => '1',
            '2'   => '2',
            '3'   => '3',
            '4'   => '4'
        ];  
        $generate_year = [];
        
        //get dropdown data
        $this->get_data($data);
        $this->get_year($year);
        $this->get_year($generate_year);
        
        $customer_satisfaction_measurements = CustomerSatisfactionMeasurement::orderBy('year', 'DESC')->orderBy('functional_unit')->orderBy('quarter')->paginate(10);
        return view('customer_satisfaction_measurements.index')->with([
            'csm'           => $customer_satisfaction_measurements,
            'data'          => $data,
            'quarter'       => $quarter,
            'year'          => $year,
            'generate_year' => $generate_year
        ]);
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //check permission
        $isPermitted = false;
        $this->check_permission($isPermitted, Auth::id(), $this->add_csm);
        if(!$isPermitted){
            return view('pages.unauthorized');
        }

        //Dropdown data
        $quarter = [
            '1' => '1',
            '2' => '2',
            '3' => '3',
            '4' => '4'
        ];

        $classifications = [
            'Student'               => 'Student',
            'Government Employee'   => 'Government Employee',
            'Internal'              => 'Internal',
            'Business'              => 'Business',
            'Homemaker'             => 'Homemaker',
            'Private Organization'  => 'Private Organization',
            'Entrepreneur'          => 'Entrepreneur'
        ];

        $addresses_input = CustomerSatisfactionAddress::orderBy('name')->pluck('name');
        $services_input  = CustomerSatisfactionService::orderBy('name')->pluck('name');
        $addresses       = [];
        $services        = [];
        $data            = [];
        $this->array_to_dropdown($addresses_input, $addresses);
        $this->array_to_dropdown($services_input, $services);
        
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
        
        //Form validation
        $validator = Validator::make($request->all(), [
            'functional_unit'               => 'required',
            'year'                          => 'required|digits:4',
            'quarter'                       => 'required',
            'total_customer'                => 'required|numeric|min:1',
            'total_male'                    => 'required|numeric|min:0',
            'total_female'                  => 'required|numeric|min:0',
            'classification'                => 'required',
            'classification.*'              => 'required',
            'classification_count'          => 'required',
            'classification_count.*'        => 'required|numeric|min:1',
            'other_classification'          => 'required_with:other_classification_count',
            'other_classification.*'        => 'required_with:other_classification_count',
            'other_classification_count'    => 'required_with:other_classification',
            'other_classification_count.*'  => 'required_with:other_classification|numeric|min:1',
            'address'                       => 'required',
            'address.*'                     => 'required',
            'address_count'                 => 'required',
            'address_count.*'               => 'required|numeric|min:1',
            'service'                       => 'required',
            'service.*'                     => 'required',
            'service_count'                 => 'required',
            'service_count.*'               => 'required|numeric|min:1',
            'five_star'                     => 'required|numeric|min:1',
            'four_star'                     => 'required|numeric|min:1',
            'three_below'                   => 'required|numeric|min:1',
            'response_delivery'             => 'required|numeric|min:1|max:5',
            'work_quality'                  => 'required|numeric|min:1|max:5',
            'personnels_quality'            => 'required|numeric|min:1|max:5',
            'overall_rating'                => 'required|numeric|min:1|max:5',
            "supporting_documents"          => "required",
            "supporting_documents.*"        => "required|file|mimes:pdf,doc,xls,ppt",
            'comments'                      => 'nullable'
        ], $this->custom_messages);



        //Checks if the count tallies  to the total gender, total address and total classification
        $count_other_classification  = ($request->other_classification_count === null) ? 0 : array_sum($request->other_classification_count);
        $count_classification        = array_sum($request->classification_count) + $count_other_classification;
        $count_address               = array_sum($request->address_count);
        $total_gender                = $request->total_male + $request->total_female;
        $total_count                 = $request->total_customer;

        if($total_count != $count_classification){
            
            $validator->after(function ($validator) {
                $validator->errors()->add('error_classification_count', 'Total count of classification should be equal to the total number of customers.');
            });
        }

        if($total_count != $total_gender){
            
            $validator->after(function ($validator) {
                $validator->errors()->add('error_gender_count', 'Sum of Total male and female should be equal to the total number of customers.');
            });
        }

        if($total_count != $count_address){
            $validator->after(function ($validator) {
                $validator->errors()->add('error_address_count', 'Total count of address should be equal to the total number of customers.');
            });
        }
        

        //check if the csm already exist
        if($request->functional_unit !== null && $request->year !== null && $request->quarter !== null){
            $csm = CustomerSatisfactionMeasurement::where([
                ['functional_unit', $request->functional_unit],
                ['year', $request->year],
                ['quarter', $request->quarter]
            ])->get();
            
            //if it exist duplicate error
            if(count($csm) !== 0){
                $validator->after(function ($validator) {
                    $validator->errors()->add('error_duplicate', 'Duplicate error');
                });
            }
        }   

        $validator->validate();
        

        


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
        $this->save_customer_satisfction_documents($request, $csm_id);

        if($request->other_classification !== null){
            $this->save_customer_other_classification($request, $csm_id);
        }

        $this->check_summary($request);

        $log                = new Log;
        $log->name          = Auth::user()->name;
        $log->action        = 'ADD';
        $log->module        = 'CSM';
        $log->description   = 'Added CSM for functional unit: ' . $request->functional_unit . ' year: ' . $request->year . ' Quarter: ' . $request->quarter;
        $log->save();


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

        //Check permisssion
        $isPermitted = false;
        $this->check_permission($isPermitted, Auth::id(), $this->view_csm);
        if(!$isPermitted){
            return view('pages.unauthorized');
        }

        $customer_satisfaction_measurement  = CustomerSatisfactionMeasurement::find($id);
        $customer_satisfaction_documents    = CustomerSatisfactionDocuments::where('csm_id',$id)->get();
        $customer_rating                    = CustomerRating::where('csm_id',$id)->get()[0];
        $customer_overall_rating            = CustomerOverallRating::where('csm_id',$id)->get()[0];
        $customer_addresses                 = CustomerAddress::where('csm_id', $id)->get();
        $customer_services_offered          = CustomerServicesOffered::where('csm_id', $id)->get();
        $customer_other_classifications     = CustomerOtherClassification::where('csm_id', $id)->get();
        $customer_classifications           = [];
        $customer_classifications_count     = [];
        $this->get_customer_classifications($id, $customer_classifications, $customer_classifications_count);

        //Concatenate all file names uploaded documents
        $supporting_documents = '';
        foreach($customer_satisfaction_documents as $file){
            $supporting_documents = $supporting_documents.$file->file_name.', ';
        }
        
        $customer_satisfaction_measurement->supporting_documents = $supporting_documents;
        return view('customer_satisfaction_measurements.show')->with([
                'csm'                               => $customer_satisfaction_measurement,
                'customer_classifications'          => $customer_classifications,
                'customer_classifications_count'    => $customer_classifications_count,
                'customer_other_classifications'    => $customer_other_classifications,
                'customer_overall_rating'           => $customer_overall_rating,
                'customer_rating'                   => $customer_rating,
                'customer_addresses'                => $customer_addresses,
                'customer_services_offered'         => $customer_services_offered, 
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

        //Check permission
        $isPermitted = false;
        $this->check_permission($isPermitted, Auth::id(), $this->edit_csm);
        if(!$isPermitted){
            return view('pages.unauthorized');
        }


        //Dropdown data
        $quarter = [
            '1' => '1',
            '2' => '2',
            '3' => '3',
            '4' => '4'
        ];

        $classifications = [
            'Student'               => 'Student',
            'Government Employee'   => 'Government Employee',
            'Internal'              => 'Internal',
            'Business'              => 'Business',
            'Homemaker'             => 'Homemaker',
            'Private Organization'  => 'Private Organization',
            'Entrepreneur'          => 'Entrepreneur'
        ];

        $addresses_input    = CustomerSatisfactionAddress::orderBy('name')->pluck('name');
        $services_input     = CustomerSatisfactionService::orderBy('name')->pluck('name');
        $addresses          = [];
        $services           = [];
        $data               = [];

        $this->array_to_dropdown($addresses_input, $addresses);
        $this->array_to_dropdown($services_input, $services);
        $this->get_data($data);;



        $customer_satisfaction_measurement  = CustomerSatisfactionMeasurement::find($id);
        $customer_satisfaction_documents    = CustomerSatisfactionDocuments::where('csm_id',$id)->get();
        $customer_rating                    = CustomerRating::where('csm_id',$id)->get()[0];
        $customer_overall_rating            = CustomerOverallRating::where('csm_id',$id)->get()[0];
        $customer_addresses                 = CustomerAddress::where('csm_id', $id)->get();
        $customer_services_offered          = CustomerServicesOffered::where('csm_id', $id)->get();
        $customer_other_classifications     = CustomerOtherClassification::where('csm_id', $id)->get();
        $customer_classifications           = [];
        $customer_classifications_count     = [];
        $this->get_customer_classifications($id, $customer_classifications, $customer_classifications_count);

        //Concatenate file name
        $supporting_documents = '';
        foreach($customer_satisfaction_documents as $file){
            $supporting_documents = $supporting_documents.$file->file_name.', ';
        }
        
        $customer_satisfaction_measurement->supporting_documents = $supporting_documents;

        return view('customer_satisfaction_measurements.edit')->with([
                'csm'                               => $customer_satisfaction_measurement,
                'customer_classifications'          => $customer_classifications,
                'customer_classifications_count'    => $customer_classifications_count,
                'customer_other_classifications'    => $customer_other_classifications,
                'customer_overall_rating'           => $customer_overall_rating,
                'customer_rating'                   => $customer_rating,
                'customer_addresses'                => $customer_addresses,
                'customer_services_offered'         => $customer_services_offered, 
                'data'                              => $data,
                'quarter'                           => $quarter,
                'classifications'                   => $classifications,
                'addresses'                         => $addresses,
                'services'                          => $services
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
        //Form validation
        $validator = Validator::make($request->all(), [
            'functional_unit'               => 'required',
            'year'                          => 'required|digits:4',
            'quarter'                       => 'required',
            'total_customer'                => 'required|numeric|min:1',
            'total_male'                    => 'required|numeric|min:0',
            'total_female'                  => 'required|numeric|min:0',
            'classification'                => 'required',
            'classification.*'              => 'required',
            'classification_count'          => 'required',
            'classification_count.*'        => 'required|numeric|min:1',
            'other_classification'          => 'required_with:other_classification_count',
            'other_classification.*'        => 'required_with:other_classification_count',
            'other_classification_count'    => 'required_with:other_classification',
            'other_classification_count.*'  => 'required_with:other_classification|numeric|min:1',
            'address'                       => 'required',
            'address.*'                     => 'required',
            'address_count'                 => 'required',
            'address_count.*'               => 'required|numeric|min:1',
            'service'                       => 'required',
            'service.*'                     => 'required',
            'service_count'                 => 'required',
            'service_count.*'               => 'required|numeric|min:1',
            'five_star'                     => 'required|numeric|min:1',
            'four_star'                     => 'required|numeric|min:1',
            'three_below'                   => 'required|numeric|min:1',
            'response_delivery'             => 'required|numeric|min:1|max:5',
            'work_quality'                  => 'required|numeric|min:1|max:5',
            'personnels_quality'            => 'required|numeric|min:1|max:5',
            'overall_rating'                => 'required|numeric|min:1|max:5',
            "supporting_documents"          => "nullable",
            "supporting_documents.*"        => "nullable|file|mimes:pdf,doc,xls,ppt",
            'comments'                      => 'nullable'
        ], $this->custom_messages);



        
        $count_other_classification = ($request->other_classification_count === null) ? 0 : array_sum($request->other_classification_count);
        $count_classification       = ($request->classification_count === null) ? 0 : array_sum($request->classification_count) + $count_other_classification;
        $count_address              = array_sum($request->address_count);
        $total_gender               = $request->total_male + $request->total_female;
        $total_count                = $request->total_customer;
    
        if($total_count != $count_classification){
            
            $validator->after(function ($validator) {
                $validator->errors()->add('error_classification_count', 'Total count of classification should be equal to the total number of customers.');
            });
        }

        if($total_count != $total_gender){
            
            $validator->after(function ($validator) {
                $validator->errors()->add('error_gender_count', 'Sum of Total male and female should be equal to the total number of customers.');
            });
        }

        if($total_count != $count_address){
            $validator->after(function ($validator) {
                $validator->errors()->add('error_address_count', 'Total count of address should be equal to the total number of customers.');
            });
        }

        //check if the csm already exist
        $old_csm = CustomerSatisfactionMeasurement::find($id);
        if(    $request->functional_unit        !== null // 
            && $request->year                   !== null //
            && $request->quarter                !== null //
            && $request->functional_unit        !== $old_csm->functional_unit 
            && $request->year                   !== $old_csm->year 
            && $request->quarter                !== $old_csm->quarter){

            $csm = CustomerSatisfactionMeasurement::where([
                ['functional_unit', $request->functional_unit],
                ['year', $request->year],
                ['quarter', $request->quarter]
            ])->get();
    
            if(count($csm) !== 0){
                $validator->after(function ($validator) {
                    $validator->errors()->add('error_duplicate', 'Duplicate error');
                });
            }
        } 
        
        
        $validator->validate();
        
        
        $old_csm = CustomerSatisfactionMeasurement::find($id);
        //rename directory if fxnal unit/year/quarter was changed 
        $old_dir = $old_csm->functional_unit.'-'.$old_csm->year.'-Quarter'.$old_csm->quarter;
        $new_dir = $request->functional_unit.'-'.$request->year.'-Quarter'.$request->quarter;
        if($old_dir !== $new_dir){
            Storage::move('public/customer_satisfaction_measurements/'.$old_dir , 'public/customer_satisfaction_measurements/'.$new_dir);
        }
         
        
        $log                = new Log;
        $log->name          = Auth::user()->name;
        $log->action        = 'EDIT';
        $log->module        = 'CSM';
        $log->description   = 'Updated CSM for functional unit: ' . $old_csm->functional_unit . ' year: ' . $old_csm->year . ' Quarter: ' . $old_csm->quarter;
        $log->save();
        
        
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

        if($request->hasFile('supporting_documents')){
            $this->save_customer_satisfction_documents($request, $id);
        }

        if($request->other_classification !== null){
            $this->save_customer_other_classification($request, $id);
        }

        $this->nullify_summary($old_csm->functional_unit, $old_csm->year, $old_csm->quarter);
        $this->check_summary($request);

        return redirect('/csm');
    }

   
    //Downlaod all supporting documents
    public function download_supporting_documents($id){
       
        //the directory should be writable
        $customer_satisfaction_measurement = CustomerSatisfactionMeasurement::find($id);

        $folder_name    = $customer_satisfaction_measurement->functional_unit.'-'.$customer_satisfaction_measurement->year.'-Quarter'.$customer_satisfaction_measurement->quarter;
        $directory      = 'storage/customer_satisfaction_measurements/'.$folder_name.'/*';

        $files          = glob(public_path($directory));
        $storage_path   = public_path('storage/downloads/');
        $zip_name       = $folder_name.'-'.time().'.zip';

        Zipper::make($storage_path.$zip_name)->add($files)->close();

        $log                = new Log;
        $log->name          = Auth::user()->name;
        $log->action        = 'DOWNLOAD';
        $log->module        = 'CSM';
        $log->description   = 'Download Supporting documents';
        $log->save();

        return Storage::download('public/downloads/'.$zip_name);
    }


    //Filter data
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
        $generate_year = [];
        $this->get_data($data);
        $this->get_year($year);
        $this->get_year($generate_year);
        
        //Combinations of different filters
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
            'year'      => $year,
            'generate_year' => $generate_year
        ]);
    }


    //
    //graphs
    //


    //Get quarters of a csm as labels
    private function get_quarters(&$labels, $unit, $unit_year){
        $csm_quarters = CustomerSatisfactionMeasurement::where([
            ['functional_unit', $unit->name],
            ['year', $unit_year]
        ])->pluck('quarter');
        foreach($csm_quarters as $quarter){
            array_push($labels, 'Q'.$quarter); 
        }
    }

    //get overall ratings
    private function get_overall_ratings(&$overall_ratings, $csm_ids){
        foreach($csm_ids as $id){
            $csm = CustomerOverallRating::where('csm_id', $id)->get()[0];
            array_push($overall_ratings, $csm->overall_rating);
        }
    }

    //get customer ratings
    private function get_customer_ratings(&$customer_ratings, $csm_ids){
        foreach($csm_ids as $id){
            $csm = CustomerRating::where('csm_id', $id)->get()[0];
            $customer_ratings['five_star'] += $csm->five_star;
            $customer_ratings['four_star'] += $csm->four_star;
            $customer_ratings['three_below'] += $csm->three_below;
        }
    }

    //get all customer addresses for a year
    private function get_customer_addresses(&$customer_address_names, &$customer_address_count, $csm_ids){
        $temp = [];

        //setup temp array  to have key value pairs of adresses
        foreach($csm_ids as $id){
            $customer_addresses = CustomerAddress::where('csm_id', $id)->get();
            foreach($customer_addresses as $address){
                if(!array_key_exists($address->address, $temp)){
                    array_push($customer_address_names, $address->address);
                    $temp[$address->address] = 0;
                }
                
            }

           
        }

        //add value to the array
        foreach($csm_ids as $id){
            $customer_addresses = CustomerAddress::where('csm_id', $id)->get();
            foreach($customer_addresses as $address){
                $temp[$address->address] += $address->count;    
            }
        }
        
        //Put all values in an array
        foreach($customer_address_names as $service_name){
            array_push($customer_address_count, $temp[$service_name]);
        }
        
    }

    //Get all customer services for a year
    private function get_customer_services(&$customer_services_names, &$customer_services_count, $csm_ids){
        $temp = [];

        //setup temp array  to have key value pairs of adresses
        foreach($csm_ids as $id){
            $customer_services_offered = CustomerServicesOffered::where('csm_id', $id)->get();
            foreach($customer_services_offered as $service){
                if(!array_key_exists($service->service_name, $temp)){
                    array_push($customer_services_names, $service->service_name);
                    $temp[$service->service_name] = 0;
                }
                
            }

           
        }

        //add value to the array
        foreach($csm_ids as $id){
            $customer_services_offered = CustomerServicesOffered::where('csm_id', $id)->get();
            foreach($customer_services_offered as $service){
                $temp[$service->service_name] += $service->count;    
            }
        }
        
        //Put all values in an array
        foreach($customer_services_names as $service_name){
            array_push($customer_services_count, $temp[$service_name]);
        }
        
    }



    //Create overall Ratings chart
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
            'lineTension' => '.001'
        ]);

    }

    //Create Customer ratings chart
    private function create_customer_ratings_chart(&$customer_ratings_chart, $csm_ids, $unit, $unit_year){
        
        $customer_ratings = [
            'five_star'     => 0,
            'four_star'     => 0,
            'three_below'   => 0
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
        $dataset->backgroundColor(collect($this->colors));
        $dataset->color(collect($this->colors));
    }

    //Create customer services pie chart
    private function create_customer_services_chart(&$customer_services_chart, $csm_ids, $unit, $unit_year){
        $customer_services_names = [];
        $customer_services_count = [];
        $this->get_customer_services($customer_services_names, $customer_services_count, $csm_ids);

        $title = $unit->name.' Services Offered Year '.$unit_year;
        $customer_services_chart->title($title, 14,'#666',true, "'Helvetica Neue', 'Helvetica', 'Arial', sans-serif");
        $customer_services_chart->labels($customer_services_names);
        $customer_services_chart->displayAxes(false);
        $dataset = $customer_services_chart->dataset(' Services Offered Year '.$unit_year, 'pie', $customer_services_count);
        $dataset->backgroundColor(collect($this->colors));
        $dataset->color(collect($this->colors));
    }

    //Create customer addresses pie chart
    private function create_customer_addresses_chart(&$customer_addresses_chart, $csm_ids, $unit, $unit_year){
        $customer_adress_names = [];
        $customer_adress_count = [];
        $this->get_customer_addresses($customer_adress_names, $customer_adress_count, $csm_ids);
        
        $title = $unit->name.' Customer Addresses Year '.$unit_year;
        $customer_addresses_chart->title($title, 14,'#666',true, "'Helvetica Neue', 'Helvetica', 'Arial', sans-serif");
        $customer_addresses_chart->labels($customer_adress_names);
        $customer_addresses_chart->displayAxes(false);
        $dataset =  $customer_addresses_chart->dataset(' Customer Addresses Year '.$unit_year, 'pie', $customer_adress_count);
        $dataset->backgroundColor(collect($this->colors));
        $dataset->color(collect($this->colors));
    }


    public function graphs(){

        $data = [];
        $years = [];
        $this->get_data($data);
        $this->get_year($years);

        
        $unit_year = CustomerSatisfactionMeasurement::orderBy('year', 'DESC')->pluck('year');

        //Check if at least 1 record exist
        if(count($unit_year) == 0){
            //Return insufficient records if no records in the database
            return redirect('/csm/statistics/insufficient_records');
        }else{
            $unit_year = $unit_year[0];
        }

        $unit = FunctionalUnit::orderBy('name', 'ASC')->get()[0];

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
            'data'                      => $data,
            'years'                     => $years,
            'unit_name'                 => $unit->name,
            'unit_year'                 => $unit_year,
            'overall_ratings_chart'     => $overall_ratings_chart,
            'customer_ratings_chart'    => $customer_ratings_chart,
            'customer_services_chart'   => $customer_services_chart,
            'customer_addresses_chart'  => $customer_addresses_chart
            
        ]);
    }


    //Search  graphs for different functional unit and year
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
            'data'                      => $data,
            'years'                     => $years,
            'unit_name'                 => $unit->name,
            'unit_year'                 => $unit_year,
            'overall_ratings_chart'     => $overall_ratings_chart,
            'customer_ratings_chart'    => $customer_ratings_chart,
            'customer_services_chart'   => $customer_services_chart,
            'customer_addresses_chart'  => $customer_addresses_chart
            
        ]);
    }

    //
    //  Services Functions
    //

    //Index services
    public function get_services(){

        $services = CustomerSatisfactionService::orderBy('name')->paginate(10);
        return view('customer_satisfaction_measurements.services_index')->with('services', $services);
    }

    //Seach services
    public function search_service(Request $request){
        $services = CustomerSatisfactionService::where('name', 'like', '%'.$request->search_term.'%')->orderBy('name')->paginate(10);
        return view('customer_satisfaction_measurements.services_index')->with('services', $services);
    }

    //Add a sservice
    public function add_service()
    {
        return view('customer_satisfaction_measurements.services_add');
    }

    //Create new service
    public function store_service(Request $request){
        $this->validate($request, [
            'name' => 'required'
        ], $this->custom_messages);

        $service        = new CustomerSatisfactionService;
        $service->name  = $request->name;
        $service->save();



        $log                = new Log;
        $log->name          = Auth::user()->name;
        $log->action        = 'ADD';
        $log->module        = 'CSM-SERVICES';
        $log->description   = 'Added new service: ' . $request->name;
        $log->save();

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


        $old_service = CustomerSatisfactionService::find($id);
        
        $log                = new Log;
        $log->name          = Auth::user()->name;
        $log->action        = 'EDIT';
        $log->module        = 'CSM-SERVICES';
        $log->description   = 'Updated service from ' . $old_service->name . 'to ' .$request->name;
        $log->save();

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

        $address        = new CustomerSatisfactionAddress;
        $address->name  = $request->name;
        $address->save();

        $log                = new Log;
        $log->name          = Auth::user()->name;
        $log->action        = 'ADD';
        $log->module        = 'CSM-ADDRESSES';
        $log->description   = 'Added new address: ' . $request->name;
        $log->save();

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
        
        $old_address = CustomerSatisfactionAddress::find($id);
        
        $log                = new Log;
        $log->name          = Auth::user()->name;
        $log->action        = 'EDIT';
        $log->module        = 'CSM-ADDRESSES';
        $log->description   = 'Updated adress from ' . $old_address->name . 'to ' .$request->name;
        $log->save();

        CustomerSatisfactionAddress::where('id',$id)->update(array(
            'name'      => $request->name,
        ));

        return redirect('/csm/addresses/idx');
    }

    

}

