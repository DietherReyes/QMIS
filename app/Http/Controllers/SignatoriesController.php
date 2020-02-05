<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Signatory;
use App\Log;
use Illuminate\Support\Facades\Storage;
use Auth;


class SignatoriesController extends Controller
{

    public function __construct(){
        $this->middleware('auth');
        $this->middleware('admin');
        
        $this->custom_messages = [
            'required'              => 'This field is required.',
            'image'                 => 'The input must be an image file.',
            'name.max'              => 'The input must not be greater than 255 characters.',
            'position.max'          => 'The input must not be greater than 255 characters.',
            'signature_photo.max'   => 'The image file size must not be greater than 5MB.'
        ];
        
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $signatories = Signatory::orderBy('id')->paginate(10);
        return view('signatories.index')->with('signatories', $signatories);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('signatories.create');
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
            'name'              => 'required|max:255',
            'position'          => 'required|max:255',
            'signature_photo'   => 'required|image|max:5000'
        ], $this->custom_messages);

       
        
        $filenameWithExt = $request->file('signature_photo')->getClientOriginalName();
        // Get just filename
        $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
        // Get just ext
        $extension = $request->file('signature_photo')->getClientOriginalExtension();
        // Filename to store
        $fileNameToStore= $filename.'_'.time().'.'.$extension;
        // Upload Image
        $path = $request->file('signature_photo')->storeAs('public/signature_photos', $fileNameToStore);
        
            
        // Create Post
        $signatory = new Signatory;
        $signatory->name = $request->name;
        $signatory->position = $request->position;
        $signatory->signature_photo = $fileNameToStore;
        $signatory->save();

        $log = new Log;
        $log->name = Auth::user()->name;
        $log->action = 'ADD';
        $log->module = 'SIGNATORIES';
        $log->description = 'Added  new signatory Name: ' . $request->name. ' Position: ' . $request->position;
        $log->save();

        return redirect('/sysmg/signatories');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $signatory = Signatory::find($id);
        return view('signatories.show')->with('signatory', $signatory);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $signatory = Signatory::find($id);
        return view('signatories.edit')->with('signatory', $signatory);
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
            'name'              => 'required|max:255',
            'position'          => 'required|max:255',
            'signature_photo'   => 'nullable|image|max:5000'
        ], $this->custom_messages);


        $signatory = Signatory::find($id);
        $log = new Log;
        $log->name = Auth::user()->name;
        $log->action = 'EDIT';
        $log->module = 'SIGNATORIES';
        $log->description = 'Updated signatory Name: ' . $signatory->name. ' Position: ' . $signatory->position;
        $log->save();

        $fileNameToStore = $signatory->signature_photo;
        // Handle File Upload
        if($request->hasFile('signature_photo')){
            //delete previeous photo
            Storage::delete('public/signature_photos/'.$signatory->signature_photo);
            // Get filename with the extension
            $filenameWithExt = $request->file('signature_photo')->getClientOriginalName();
            // Get just filename
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            // Get just ext
            $extension = $request->file('signature_photo')->getClientOriginalExtension();
            // Filename to store
            $fileNameToStore= $filename.'_'.time().'.'.$extension;
            // Upload Image
            $path = $request->file('signature_photo')->storeAs('public/signature_photos', $fileNameToStore);
        }

        $signatory->name = $request->name;
        $signatory->position = $request->position;
        $signatory->signature_photo = $fileNameToStore;
        $signatory->save();


        

        return redirect('/sysmg/signatories');

    
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

    //search user
    public function search(Request $request){
        $signatories = Signatory::where('name', 'like', '%'.$request->search_term.'%')->paginate(10);
        return view('signatories.index')->with('signatories', $signatories);
    }
}
