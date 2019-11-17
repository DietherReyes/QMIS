<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;

use App\ManagementReview;
use App\ManRevDoc;
use Illuminate\Support\Facades\Storage;
use Zipper;
use Auth;
use App\User;


class ManagementReviewsController extends Controller
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

    // save file 
    private function save_other_files(Request $request, $manrev_id){

        //delete previous directory for other files if it exist
        $folder_name = $request->meeting_name.'-'.$request->date;
        Storage::deleteDirectory('public/management_reviews/'.$folder_name.'/other_files');
        //delete docs in man_rev_docs
        $deletedRows = ManRevDoc::where('manrev_id', $manrev_id)->delete();

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

            $man_rev_doc = new ManRevDoc;
            $man_rev_doc->file_name = $fileNameToStore;
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
        $isPermitted = false;
        $this->check_permission($isPermitted, Auth::id(), 9);
        if(!$isPermitted){
            return view('pages.unauthorized');
        }
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
        $this->validate($request, [
            'meeting_name' => 'required',
            'venue' => 'required',
            'date' => 'required',
            'minutes' => 'required|mimes:doc,pdf,docx,zip',
            'action_plan' => 'required|mimes:doc,pdf,docx,zip',
            'agenda_memo' => 'required|mimes:doc,pdf,docx,zip',
            'presentation_slide' => 'required|mimes:doc,pdf,docx,zip',
            'attendance' => 'required|mimes:doc,pdf,docx,zip',
            'other_files[]' => 'nullable|mimes:doc,pdf,docx,zip',
            'description' => 'nullable'
        ]);



        $minutes = '';
        $action_plan = '';
        $agenda_memo = '';
        $presentation_slide = '';
        $attendance = '';
        $this->save_file($request, $minutes, 'minutes');
        $this->save_file($request, $action_plan, 'action_plan');
        $this->save_file($request, $agenda_memo, 'agenda_memo');
        $this->save_file($request, $presentation_slide, 'presentation_slide');
        $this->save_file($request, $attendance, 'attendance');

        $manrev_id = ManagementReview::insertGetId([
            'meeting_name'          => $request->meeting_name,
            'venue'                 => $request->venue,
            'date'                  => $request->date,
            'minutes'               => $minutes,
            'action_plan'           => $action_plan,
            'agenda_memo'           => $agenda_memo,
            'presentation_slide'    => $presentation_slide,
            'attendance'            => $attendance,
            'description'           => $request->description
        ]);


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
        $isPermitted = false;
        $this->check_permission($isPermitted, Auth::id(), 8);
        if(!$isPermitted){
            return view('pages.unauthorized');
        }
        $management_review = ManagementReview::find($id);
        $man_rev_docs = ManRevDoc::all()->where('manrev_id',$id);
        
        $other_files = '';
        foreach($man_rev_docs as $file){
            $other_files = $other_files.$file->file_name.', ';
        }
    
        $management_review->other_files = $other_files;
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
        $isPermitted = false;
        $this->check_permission($isPermitted, Auth::id(), 10);
        if(!$isPermitted){
            return view('pages.unauthorized');
        }
        $management_review = ManagementReview::find($id);
        $man_rev_docs = ManRevDoc::all()->where('manrev_id',$id);
        
        $other_files = '';
        foreach($man_rev_docs as $file){
            $other_files = $other_files.$file->file_name.', ';
        }
    
        $management_review->other_files = $other_files;
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
            'meeting_name' => 'required',
            'venue' => 'required',
            'date' => 'required',
            'minutes' => 'nullable|mimes:doc,pdf,docx,zip',
            'action_plan' => 'nullable|mimes:doc,pdf,docx,zip',
            'agenda_memo' => 'nullable|mimes:doc,pdf,docx,zip',
            'presentation_slide' => 'nullable|mimes:doc,pdf,docx,zip',
            'attendance' => 'nullable|mimes:doc,pdf,docx,zip',
            'other_files[]' => 'nullable|mimes:doc,pdf,docx,zip',
            'description' => 'nullable'
        ]);

        $old_manrev = ManagementReview::find($id);

        $minutes = $old_manrev->minutes;
        $action_plan = $old_manrev->action_plan;
        $agenda_memo = $old_manrev->agenda_memo;
        $presentation_slide = $old_manrev->presentation_slide;
        $attendance = $old_manrev->attendance;
        $this->update_file($request, $minutes, 'minutes', $old_manrev);
        $this->update_file($request, $action_plan, 'action_plan', $old_manrev);
        $this->update_file($request, $agenda_memo, 'agenda_memo', $old_manrev);
        $this->update_file($request, $presentation_slide, 'presentation_slide', $old_manrev);
        $this->update_file($request, $attendance, 'attendance', $old_manrev);
        
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
            'presentation_slide'    => $presentation_slide,
            'attendance'            => $attendance,
            'description'           => $request->description
        ));

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
        $isPermitted = false;
        $this->check_permission($isPermitted, Auth::id(), 11);
        if(!$isPermitted){
            return view('pages.unauthorized');
        }
        $management_review = ManagementReview::find($id);
        $path = 'public/management_reviews/'.$management_review->meeting_name.'-'.$management_review->date.'/'.$management_review->action_plan;
        return Storage::download($path);
    }

    public function download_attendance($id){
        $isPermitted = false;
        $this->check_permission($isPermitted, Auth::id(), 11);
        if(!$isPermitted){
            return view('pages.unauthorized');
        }
        $management_review = ManagementReview::find($id);
        $path = 'public/management_reviews/'.$management_review->meeting_name.'-'.$management_review->date.'/'.$management_review->attendance;
        return Storage::download($path);
    }

    public function download_minutes($id){
        $isPermitted = false;
        $this->check_permission($isPermitted, Auth::id(), 11);
        if(!$isPermitted){
            return view('pages.unauthorized');
        }
        $management_review = ManagementReview::find($id);
        $path = 'public/management_reviews/'.$management_review->meeting_name.'-'.$management_review->date.'/'.$management_review->minutes;
        return Storage::download($path);
    }

    public function download_presentation_slide($id){
        $isPermitted = false;
        $this->check_permission($isPermitted, Auth::id(), 11);
        if(!$isPermitted){
            return view('pages.unauthorized');
        }
        $management_review = ManagementReview::find($id);
        $path = 'public/management_reviews/'.$management_review->meeting_name.'-'.$management_review->date.'/'.$management_review->presentation_slide;
        return Storage::download($path);
    }

    public function download_agenda_memo($id){
        $isPermitted = false;
        $this->check_permission($isPermitted, Auth::id(), 11);
        if(!$isPermitted){
            return view('pages.unauthorized');
        }
        $management_review = ManagementReview::find($id);
        $path = 'public/management_reviews/'.$management_review->meeting_name.'-'.$management_review->date.'/'.$management_review->agenda_memo;
        return Storage::download($path);
    }

    public function download_all_files($id){
        $isPermitted = false;
        $this->check_permission($isPermitted, Auth::id(), 11);
        if(!$isPermitted){
            return view('pages.unauthorized');
        }
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
