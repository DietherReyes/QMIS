<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;

use App\ManagementReview;
use App\ManagementReviewDocument;
use Illuminate\Support\Facades\Storage;
use Zipper;
use Auth;
use App\User;


class ManagementReviewsController extends Controller
{


    public function __construct(){
        $this->middleware('auth');

        $this->custom_messages = [
            'required'                  => 'This field is required.',
            'meeting_name.max'          => 'The input must not be greater than 255 characters.',
            'venue.max'                 => 'The pdf file size must not be greater than 5MB.',
            '*.mimes'                   => 'The file input must be a file type:pdf,doc,xls,ppt.'
        ];
    }

    private function check_permission(&$isPermitted, $user_id, $permission){
        $user = User::find(Auth::id());
        $user->permission = explode(',', $user->permission);
        if($user->permission[$permission] === '1' || $user->role === 'admin'){
            $isPermitted = true;
        }
    }

    // save file 
    private function save_file(Request $request, &$fileNameToStore, $name){

        // Handle File Upload
        if($request->hasFile($name)){
            // Get filename with the extension
            $filenameWithExt = $request->file($name)->getClientOriginalName();
            // Get just filename
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            // Get just ext
            $extension = $request->file($name)->getClientOriginalExtension();
            // Filename to store
            $fileNameToStore= '['.$name.'] '.$filename.'.'.$extension;
            // Upload file
            $folder_name = $request->meeting_name.'-'.$request->date;
            $path = $request->file($name)->storeAs('public/management_reviews/'.$folder_name, $fileNameToStore);
        } 

    }

    // update file 
    private function update_file(Request $request, &$fileNameToStore, $name, $old_manrev){

        // Handle File Upload
        if($request->hasFile($name)){
            //folder_name
            $folder_name = $old_manrev->meeting_name.'-'.$old_manrev->date;
            //delete file
            Storage::delete('public/management_reviews/'.$folder_name.'/'.$fileNameToStore);
            // Get filename with the extension
            $filenameWithExt = $request->file($name)->getClientOriginalName();
            // Get just filename
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            // Get just ext
            $extension = $request->file($name)->getClientOriginalExtension();
            // Filename to store
            $fileNameToStore= '['.$name.'] '.$filename.'.'.$extension;
            // Upload file

            $path = $request->file($name)->storeAs('public/management_reviews/'.$folder_name, $fileNameToStore);
        } 

    }

    // save other files 
    private function save_other_files(Request $request, $manrev_id){

        //delete previous directory for other files if it exist
        $folder_name = $request->meeting_name.'-'.$request->date;
        Storage::deleteDirectory('public/management_reviews/'.$folder_name.'/other_files');
        //delete docs in man_rev_docs
        $deletedRows = ManagementReviewDocument::where('manrev_id', $manrev_id)->where('type', 'others')->delete();

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
            $path = $file->storeAs('public/management_reviews/'.$folder_name.'/other_files', $fileNameToStore);

            $man_rev_doc = new ManagementReviewDocument;
            $man_rev_doc->file_name = $fileNameToStore;
            $man_rev_doc->type = 'others';
            $man_rev_doc->manrev_id = $manrev_id;
            $man_rev_doc->save();

        }
    }

    // save presentation slides 
    private function save_presentation_slides(Request $request, $manrev_id){

        //delete previous directory for other files if it exist
        $folder_name = $request->meeting_name.'-'.$request->date;
        Storage::deleteDirectory('public/management_reviews/'.$folder_name.'/slides');
        //delete docs in man_rev_docs
        $deletedRows = ManagementReviewDocument::where('manrev_id', $manrev_id)->where('type', 'slides')->delete();

        foreach($request->file('presentation_slides.*') as $file){
            
            // Get filename with the extension
            $filenameWithExt = $file->getClientOriginalName();
            // Get just filename
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            // Get just ext
            $extension = $file->getClientOriginalExtension();
            // Filename to store
            $fileNameToStore= $filename.'.'.$extension;
            // Upload file
            $path = $file->storeAs('public/management_reviews/'.$folder_name.'/slides', $fileNameToStore);

            $man_rev_doc = new ManagementReviewDocument;
            $man_rev_doc->file_name = $fileNameToStore;
            $man_rev_doc->type = 'slides';
            $man_rev_doc->manrev_id = $manrev_id;
            $man_rev_doc->save();

        }
    }

    


    


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $management_reviews = ManagementReview::orderBy('id')->paginate(10);
        return view('management_reviews.index')->with('management_reviews', $management_reviews);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // $isPermitted = false;
        // $this->check_permission($isPermitted, Auth::id(), 9);
        // if(!$isPermitted){
        //     return view('pages.unauthorized');
        // }
        return view('management_reviews.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        // $request->validate([
        //     "presentation_slides"    => "required|array",
        //     "presentation_slides.*"  => "required|file|mimes:pdf,doc,xls,ppt",
        // ]);
        
        $this->validate($request, [
            'meeting_name'              => 'required|max:255',
            'venue'                     => 'required|max:255',
            'date'                      => 'required',
            'minutes'                   => 'required|mimes:pdf,doc,xls,ppt',
            'action_plan'               => 'required|mimes:pdf,doc,xls,ppt',
            'agenda_memo'               => 'required|mimes:pdf,doc,xls,ppt',
            "presentation_slides"       => "required",
            "presentation_slides.*"     => "required|file|mimes:pdf,doc,xls,ppt",
            'attendance_sheet'          => 'required|mimes:pdf,doc,xls,ppt',
            'other_files[]'             => 'nullable|mimes:pdf,doc,xls,ppt',
            'description'               => 'nullable'
        ], $this->custom_messages);

        

        $minutes = '';
        $action_plan = '';
        $agenda_memo = '';
        $attendance_sheet = '';
        $this->save_file($request, $minutes, 'minutes');
        $this->save_file($request, $action_plan, 'action_plan');
        $this->save_file($request, $agenda_memo, 'agenda_memo');
        $this->save_file($request, $attendance_sheet, 'attendance_sheet');

        $manrev_id = ManagementReview::insertGetId([
            'meeting_name'          => $request->meeting_name,
            'venue'                 => $request->venue,
            'date'                  => $request->date,
            'minutes'               => $minutes,
            'action_plan'           => $action_plan,
            'agenda_memo'           => $agenda_memo,
            'attendance_sheet'      => $attendance_sheet,
            'description'           => $request->description
        ]);

        $this->save_presentation_slides($request, $manrev_id);
        if($request->hasFile('other_files')){
            $this->save_other_files($request, $manrev_id);
        }

        return redirect('/manrev');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // $isPermitted = false;
        // $this->check_permission($isPermitted, Auth::id(), 8);
        // if(!$isPermitted){
        //     return view('pages.unauthorized');
        // }
        $management_review = ManagementReview::find($id);
        $man_rev_docs = ManagementReviewDocument::all()->where('manrev_id',$id);
        
        $slides = '';
        $other_files = '';
        foreach($man_rev_docs as $file){
            if($file->type === 'others'){
                $other_files = $other_files.$file->file_name.', ';
            }else{
                $slides = $slides.$file->file_name.', ';
            }
            
        }
    
        $management_review->other_files = $other_files;
        $management_review->slides = $slides;
        return view('management_reviews.show')->with('management_review', $management_review);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // $isPermitted = false;
        // $this->check_permission($isPermitted, Auth::id(), 10);
        // if(!$isPermitted){
        //     return view('pages.unauthorized');
        // }
        $management_review = ManagementReview::find($id);
        $man_rev_docs = ManagementReviewDocument::all()->where('manrev_id',$id);
        
        $slides = '';
        $other_files = '';
        foreach($man_rev_docs as $file){
            if($file->type === 'others'){
                $other_files = $other_files.$file->file_name.', ';
            }else{
                $slides = $slides.$file->file_name.', ';
            }
            
        }
    
        $management_review->other_files = $other_files;
        $management_review->slides = $slides;
        return view('management_reviews.edit')->with('management_review', $management_review);
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
            'meeting_name'          => 'required|max:255',
            'venue'                 => 'required|max:255',
            'date'                  => 'required',
            'minutes'               => 'nullable|mimes:pdf,doc,xls,ppt',
            'action_plan'           => 'nullable|mimes:pdf,doc,xls,ppt',
            'agenda_memo'           => 'nullable|mimes:pdf,doc,xls,ppt',
            "presentation_slides"   => "nullable",
            "presentation_slides.*" => "nullable|file|mimes:pdf,doc,xls,ppt",
            'attendance_sheet'      => 'nullable|mimes:pdf,doc,xls,ppt',
            'other_files[]'         => 'nullable|mimes:pdf,doc,xls,ppt',
            'description'           => 'nullable'
        ], $this->custom_messages);

        $old_manrev = ManagementReview::find($id);

        $minutes = $old_manrev->minutes;
        $action_plan = $old_manrev->action_plan;
        $agenda_memo = $old_manrev->agenda_memo;
        $attendance_sheet = $old_manrev->attendance_sheet;
        $this->update_file($request, $minutes, 'minutes', $old_manrev);
        $this->update_file($request, $action_plan, 'action_plan', $old_manrev);
        $this->update_file($request, $agenda_memo, 'agenda_memo', $old_manrev);
        $this->update_file($request, $attendance_sheet, 'attendance_sheet', $old_manrev);
        
        //rename directory if meeting name was changed
        if($old_manrev->meeting_name !== $request->meeting_name || $old_manrev->date !== $request->date){
            $old_dir = $old_manrev->meeting_name.'-'.$old_manrev->date;
            $new_dir = $request->meeting_name.'-'.$request->date;
            Storage::move('public/management_reviews/'.$old_dir , 'public/management_reviews/'.$new_dir);
        }
        

        ManagementReview::where('id',$id)->update(array(
            'meeting_name'          => $request->meeting_name,
            'venue'                 => $request->venue,
            'date'                  => $request->date,
            'minutes'               => $minutes,
            'action_plan'           => $action_plan,
            'agenda_memo'           => $agenda_memo,
            'attendance_sheet'      => $attendance_sheet,
            'description'           => $request->description
        ));

        if($request->hasFile('presentation_slides')){
            $this->save_presentation_slides($request, $id);
        }

        if($request->hasFile('other_files')){
            $this->save_other_files($request, $id);
        }
        
        return redirect('/manrev');
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

    public function download_action_plan($id){
       
        $management_review = ManagementReview::find($id);
        $path = 'public/management_reviews/'.$management_review->meeting_name.'-'.$management_review->date.'/'.$management_review->action_plan;
        return Storage::download($path);
    }

    public function download_attendance($id){
        
        $management_review = ManagementReview::find($id);
        $path = 'public/management_reviews/'.$management_review->meeting_name.'-'.$management_review->date.'/'.$management_review->attendance_sheet;
        return Storage::download($path);
    }

    public function download_minutes($id){
        
        $management_review = ManagementReview::find($id);
        $path = 'public/management_reviews/'.$management_review->meeting_name.'-'.$management_review->date.'/'.$management_review->minutes;
        return Storage::download($path);
    }

    public function download_presentation_slide($id){
        
        $management_review = ManagementReview::find($id);
        $directory = 'storage/management_reviews/'.$management_review->meeting_name.'-'.$management_review->date.'/slides/*';

        $files = glob(public_path($directory));
        $storage_path = public_path('storage/downloads/');
        $zip_name = $management_review->meeting_name.'-slides-'.time().'.zip';
        Zipper::make($storage_path.$zip_name)->add($files)->close();
        return Storage::download('public/downloads/'.$zip_name);
    }

    public function download_agenda_memo($id){
        
        $management_review = ManagementReview::find($id);
        $path = 'public/management_reviews/'.$management_review->meeting_name.'-'.$management_review->date.'/'.$management_review->agenda_memo;
        return Storage::download($path);
        
    }

    public function download_all_files($id){
        
        //the directory should be writable
        $management_review = ManagementReview::find($id);
        $directory = 'storage/management_reviews/'.$management_review->meeting_name.'-'.$management_review->date.'/*';

        $files = glob(public_path($directory));
        $storage_path = public_path('storage/downloads/');
        $zip_name = $management_review->meeting_name.'-'.time().'.zip';
        Zipper::make($storage_path.$zip_name)->add($files)->close();
        return Storage::download('public/downloads/'.$zip_name);
        
    }

    //search user
    public function search(Request $request){
        $management_reviews = ManagementReview::where('meeting_name', 'like', '%'.$request->search_term.'%')->paginate(10);
        return view('management_reviews.index')->with('management_reviews', $management_reviews);
    }


}
