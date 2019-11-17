<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\QualityManualDocumentation;
use Auth;
use App\User;
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
        $manual_docs = QualityManualDocumentation::orderBy('id')->paginate(10);
        return view('quality_manual_documentations.index')->with('manual_docs', $manual_docs);
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
        return view('quality_manual_documentations.create');
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
            'date' => 'required',
            'subject' => 'required',
            'revision_no' => 'required',
            'division' => 'required',
            'quality_manual_doc' => 'required|mimes:pdf'
        ]);
        
        $quality_manual_doc = '';
        $this->save_file($request, $quality_manual_doc);

        $manual = new QualityManualDocumentation;
        $manual->document_code = $request->document_code;
        $manual->date = $request->date;
        $manual->subject = $request->subject;
        $manual->revision_no = $request->revision_no;
        $manual->division = $request->division;
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
        return view('quality_manual_documentations.edit')->with('manual_doc', $manual_doc);
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
            'date' => 'required',
            'subject' => 'required',
            'revision_no' => 'required',
            'division' => 'required',
            'quality_manual_doc' => 'nullable|mimes:pdf'
        ]);
        
        $old_doc = QualityManualDocumentation::find($id);

        $quality_manual_doc = $old_doc->quality_manual_doc;
        $this->save_file($request, $quality_manual_doc);

        QualityManualDocumentation::where('id',$id)->update(array(
            'document_code'      => $request->document_code,
            'date'               => $request->date,
            'subject'            => $request->subject,
            'revision_no'        => $request->revision_no,
            'division'           => $request->division,
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


    //search user
    public function search(Request $request){
        $manual_docs = QualityManualDocumentation::where('subject', 'like', '%'.$request->search_term.'%')->paginate(10);
        return view('quality_manual_documentations.index')->with('manual_docs', $manual_docs);
    }
}
