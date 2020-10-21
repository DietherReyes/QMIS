<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\QualityManualDocumentation;
use Auth;
use App\User;
use App\QualityDocumentSection;
use App\Log;
class QualityManualDocumentationsController extends Controller
{

    public function __construct(){
        //Checks if user is authenticated
        $this->middleware('auth');
        $this->middleware('account_status');

        //Form validation messages
        $this->custom_messages = [
            'required'                  => 'This field is required.',
            'numeric'                   => 'This field requires numeric input.',
            'subject.max'               => 'The input must not be greater than 255 characters.',
            'quality_manual_doc.max'    => 'The pdf file size must not be greater than 5MB.',
            'quality_manual_doc.mimes'  => 'The file input must be a file type:pdf.'
        ];

        //Permission indeces
        $this->view_qmsd = 9;
        $this->add_qmsd = 10;
        $this->edit_qmsd = 11;
    }

    //Checnk permission
    private function check_permission(&$isPermitted, $user_id, $permission){
        $user = User::find(Auth::id());
        $user->permission = explode(',', $user->permission);
        if($user->permission[$permission] === '1' || $user->role === 'admin'){
            $isPermitted = true;
        }
    }

    // save file 
    private function save_file(Request $request, &$fileNameToStore){

        // Handle File Upload
        if($request->hasFile('quality_manual_doc')){
            if($fileNameToStore !== ''){
                Storage::delete('public/quality_manual_documentations/'.$fileNameToStore);
            }
            // Get filename with the extension
            $filenameWithExt = $request->file('quality_manual_doc')->getClientOriginalName();
            // Get just filename
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            // Get just ext
            $extension = $request->file('quality_manual_doc')->getClientOriginalExtension();
            // Filename to store
            $fileNameToStore = $filename.'.'.$extension;
            // Upload file
            $path = $request->file('quality_manual_doc')->storeAs('public/quality_manual_documentations/', $fileNameToStore);
        } 

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
            'QM' => 'Quality Manual',
            'PM' => 'Procedures Manual',
            'WI' => 'Work Instruction',
            'FM' => 'Forms Manual',
        ]; 
        $manual_docs = QualityManualDocumentation::where('document_code', 'LIKE', 'QM%')
                                                   ->orderBy('document_code')
                                                   ->orderBy('revision_number', 'DESC')
                                                   ->orderBy('page_number')->paginate(10);
        return view('quality_manual_documentations.index')->with(['manual_docs' => $manual_docs, 'data' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $isPermitted = false;
        $this->check_permission($isPermitted, Auth::id(), $this->add_qmsd);
        if(!$isPermitted){
            return view('pages.unauthorized');
        }

        //get data for section dropdown
        $sections = QualityDocumentSection::orderBy('section_name')->get();
        $data = [];
        foreach($sections as $section){
            $data[$section->section_name] = $section->section_name;
        }
        

        return view('quality_manual_documentations.create')->with('data', $data);
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
        $this->validate($request, [
            'document_code'         => 'required',
            'section'               => 'required|max:255',
            'effectivity_date'      => 'required',
            'subject'               => 'required',
            'revision_number'       => 'required|numeric',
            'page_number'           => 'required|numeric',
            'quality_manual_doc'    => 'required|mimes:pdf|max:5000'
        ], $this->custom_messages);
        
        $quality_manual_doc = '';
        $this->save_file($request, $quality_manual_doc);

        $manual                     = new QualityManualDocumentation;
        $manual->document_code      = strtoupper($request->document_code);
        $manual->effectivity_date   = $request->effectivity_date;
        $manual->subject            = $request->subject;
        $manual->revision_number    = $request->revision_number;
        $manual->section            = $request->section;
        $manual->page_number        = $request->page_number;
        $manual->quality_manual_doc = $quality_manual_doc;
        $manual->save();

        $log                = new Log;
        $log->name          = Auth::user()->name;
        $log->action        = 'ADD';
        $log->module        = 'QMSD';
        $log->description   = 'Added QMSD Document Code: ' . $request->document_code . ' Section: ' . $request->section. ' Subject: ' . $request->subject;
        $log->save();
        
        return redirect('/qmsd');
        

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
        $this->check_permission($isPermitted, Auth::id(), $this->view_qmsd);
        if(!$isPermitted){
            return view('pages.unauthorized');
        }
        $manual_doc = QualityManualDocumentation::find($id);
        return view('quality_manual_documentations.show')->with('manual_doc', $manual_doc);
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
        $this->check_permission($isPermitted, Auth::id(), $this->edit_qmsd);
        if(!$isPermitted){
            return view('pages.unauthorized');
        }
        $manual_doc = QualityManualDocumentation::find($id);

        $sections = QualityDocumentSection::orderBy('section_name')->get();
        $data = [];
        foreach($sections as $section){
            $data[$section->section_name] = $section->section_name;
        }

        return view('quality_manual_documentations.edit')->with(['manual_doc' => $manual_doc, 'data' => $data]);
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
            'document_code'         => 'required',
            'section'               => 'required|max:255',
            'effectivity_date'      => 'required',
            'subject'               => 'required',
            'revision_number'       => 'required|numeric',
            'page_number'           => 'required|numeric',
            'quality_manual_doc'    => 'nullable|mimes:pdf|max:5000'
        ], $this->custom_messages);
        
        $old_doc = QualityManualDocumentation::find($id);
        $log                = new Log;
        $log->name          = Auth::user()->name;
        $log->action        = 'EDIT';
        $log->module        = 'QMSD';
        $log->description   = 'Updated QMSD Document Code: ' . $old_doc->document_code . ' Section: ' . $old_doc->section. ' Subject: ' . $old_doc->subject;
        $log->save();

        $quality_manual_doc = $old_doc->quality_manual_doc;
        $this->save_file($request, $quality_manual_doc);

        QualityManualDocumentation::where('id',$id)->update(array(
            'document_code'      => $request->document_code,
            'effectivity_date'   => $request->effectivity_date,
            'subject'            => $request->subject,
            'revision_number'    => $request->revision_number,
            'page_number'        => $request->page_number,
            'section'            => $request->section,
            'quality_manual_doc' => $quality_manual_doc
            
        ));

        

        return redirect('/qmsd');
    }

    //download document
    public function manual_doc($id){
        $manual_doc = QualityManualDocumentation::find($id);
        $path = 'public/quality_manual_documentations/'.$manual_doc->quality_manual_doc;

        $log                = new Log;
        $log->name          = Auth::user()->name;
        $log->action        = 'DOWNLOAD';
        $log->module        = 'QMSD';
        $log->description   = 'Downloaded Manual';
        $log->save();

        return Storage::download($path);
    }


    //search qmsd
    public function search(Request $request){
        $data = [
            'QM' => 'Quality Manual',
            'PM' => 'Procedures Manual',
            'WI' => 'Work Instruction',
            'FM' => 'Forms Manual',
        ]; 
        $manual_docs = QualityManualDocumentation::where('document_code', 'LIKE', $request->type.'%')
                                                   ->where('subject', 'LIKE', '%'.$request->search_term.'%')
                                                   ->orderBy('document_code')
                                                   ->orderBy('revision_number', 'DESC')
                                                   ->orderBy('page_number')
                                                   ->paginate(10);
        return view('quality_manual_documentations.index')->with(['manual_docs' => $manual_docs, 'data' => $data]);
    }

    //
    //  Section Functions
    //

    public function get_sections(){

        $sections = QualityDocumentSection::orderBy('section_name')->paginate(10);
        return view('quality_manual_documentations.section_index')->with('sections', $sections);
    }

    public function search_section(Request $request){
        $sections = QualityDocumentSection::where('section_name', 'like', '%'.$request->search_term.'%')->orderBy('section_name')->paginate(10);
        return view('quality_manual_documentations.section_index')->with('sections', $sections);
    }



    public function add_section()
    {
        return view('quality_manual_documentations.section_add');
    }

    public function store_section(Request $request){
        $this->validate($request, [
            'section_name' => 'required||unique:quality_document_sections'
        ], $this->custom_messages);

        $section                = new QualityDocumentSection;
        $section->section_name  = $request->section_name;
        $section->save();

        $log                = new Log;
        $log->name          = Auth::user()->name;
        $log->action        = 'ADD';
        $log->module        = 'QMSD-SECTION';
        $log->description   = 'Added new section: ' . $request->section_name;
        $log->save();

        return redirect('/qmsd/sections/idx');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit_section($id)
    {
        $section = QualityDocumentSection::find($id);
        return view('quality_manual_documentations.section_edit')->with('section', $section);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update_section(Request $request, $id)
    {
        $this->validate($request, [
            'section_name' => 'required||unique:quality_document_sections',
        ], $this->custom_messages);
        
        $old_section = QualityDocumentSection::find($id);
        QualityManualDocumentation::where('section',$old_section->section_name)->update(array(
            'section'      => $request->section_name,
        ));

        $log                = new Log;
        $log->name          = Auth::user()->name;
        $log->action        = 'EDIT';
        $log->module        = 'QMSD-SECTION';
        $log->description   = 'Updated section from ' . $old_section->name . 'to ' .$request->name;
        $log->save();

        QualityDocumentSection::where('id',$id)->update(array(
            'section_name'      => $request->section_name,
        ));

        

        return redirect('/qmsd/sections/idx');
    }
}
