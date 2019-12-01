<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\QualityManualDocumentation;
use Auth;
use App\User;
use App\QualityDocumentSection;
class QualityManualDocumentationsController extends Controller
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
        $data = [
            'QM' => 'Quality Manual',
            'PM' => 'Procedures Manual',
            'WI' => 'Work Instruction',
            'FM' => 'Forms Manual',
        ]; 
        $manual_docs = QualityManualDocumentation::where('document_code', 'LIKE', 'QM%')->orderBy('document_code')->orderBy('revision_number')->orderBy('page_number')->paginate(10);
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
        $this->check_permission($isPermitted, Auth::id(), 13);
        if(!$isPermitted){
            return view('pages.unauthorized');
        }

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
        
        $this->validate($request, [
            'document_code' => 'required',
            'section' => 'required',
            'effectivity_date' => 'required',
            'subject' => 'required',
            'revision_number' => 'required',
            'page_number' => 'required',
            'quality_manual_doc' => 'required|mimes:pdf'
        ]);
        
        $quality_manual_doc = '';
        $this->save_file($request, $quality_manual_doc);

        $manual = new QualityManualDocumentation;
        $manual->document_code = $request->document_code;
        $manual->effectivity_date = $request->effectivity_date;
        $manual->subject = $request->subject;
        $manual->revision_number = $request->revision_number;
        $manual->section = $request->section;
        $manual->page_number = $request->page_number;
        $manual->quality_manual_doc = $quality_manual_doc;
        $manual->save();
        
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
        $this->check_permission($isPermitted, Auth::id(), 12);
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
        $this->check_permission($isPermitted, Auth::id(), 14);
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
            'document_code' => 'required',
            'section' => 'required',
            'effectivity_date' => 'required',
            'subject' => 'required',
            'revision_number' => 'required',
            'page_number' => 'required',
            'quality_manual_doc' => 'nullable|mimes:pdf'
        ]);
        
        $old_doc = QualityManualDocumentation::find($id);

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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        
    }


    public function manual_doc($id){
        $isPermitted = false;
        $this->check_permission($isPermitted, Auth::id(), 15);
        if(!$isPermitted){
            return view('pages.unauthorized');
        }
        $manual_doc = QualityManualDocumentation::find($id);
        $path = 'public/quality_manual_documentations/'.$manual_doc->quality_manual_doc;
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
                                                   ->orderBy('revision_number')
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

    public function add_section(Request $request){
        $this->validate($request, [
            'section_name' => 'required'
        ]);

        $section = new QualityDocumentSection;
        $section->section_name = $request->section_name;
        $section->save();

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
            'section_name' => 'required',
        ]);
        
       

        QualityDocumentSection::where('id',$id)->update(array(
            'section_name'      => $request->section_name,
        ));

        return redirect('/qmsd/sections/idx');
    }
}
