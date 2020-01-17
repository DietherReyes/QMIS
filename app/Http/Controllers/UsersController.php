<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;


use App\User;
use App\FunctionalUnit;
use Illuminate\Support\Facades\Storage;

class UsersController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
        $this->middleware('admin');
        
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::orderBy('id')->paginate(10);
        return view('accounts.index')->with('users', $users);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
    }
    
    //gets dropdown data
    private function get_data(&$data, &$functional_units){
        $functional_units = FunctionalUnit::all();
        $data = [];
        foreach($functional_units as $functional_unit){
            $functional_unit->permission = explode(',', $functional_unit->permission); //splitting the permission array
            $data[$functional_unit->name] = $functional_unit->name;
        }
    }

    //save profile photo to the designated storage and get its filename
    private function get_photo(Request $request, &$fileNameToStore, $photo){

        // Handle File Upload
        if($request->hasFile('profile_photo')){
            //checks if previeous photo is not the default image
            if($photo !== 'default.jpg'){
                //delete previeous photo
                Storage::delete('public/profile_photos/'.$photo);
            }
            // Get filename with the extension
            $filenameWithExt = $request->file('profile_photo')->getClientOriginalName();
            // Get just filename
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            // Get just ext
            $extension = $request->file('profile_photo')->getClientOriginalExtension();
            // Filename to store
            $fileNameToStore= $filename.'_'.time().'.'.$extension;
            // Upload Image
            $path = $request->file('profile_photo')->storeAs('public/profile_photos', $fileNameToStore);
        } else {
            $fileNameToStore = $photo;
        }

    }

    //handles deletion of profile photo if
    private function update_photo(Request $request, &$fileNameToStore, $photo){

        // Handle File Upload
        if($request->hasFile('profile_photo')){
            
            // Get filename with the extension
            $filenameWithExt = $request->file('profile_photo')->getClientOriginalName();
            // Get just filename
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            // Get just ext
            $extension = $request->file('profile_photo')->getClientOriginalExtension();
            // Filename to store
            $fileNameToStore= $filename.'_'.time().'.'.$extension;
            // Upload Image
            $path = $request->file('profile_photo')->storeAs('public/profile_photos', $fileNameToStore);
        } else {
            $fileNameToStore = $photo;
        }

    }



    // process permission array into a single string
    private function process_permission_array(Request $request, &$temp_permission){
        if($request->permission !== null){
            $checkbox_values = array_map('intval', $request->permission);
            foreach($checkbox_values as $value){
                $checkbox_values = array_map('intval', $request->permission);

                $temp_permission[$value] = "1";
    
            }
        }
        $temp_permission = implode(',',$temp_permission);
    }

    // save new user
    private function save_user(Request $request, $role, $temp_permission ,$fileNameToStore){
        $user = new User;
        $user->name = $request->name;
        $user->position = $request->position;
        $user->functional_unit = $request->functional_unit;
        $user->role = $role;
        $user->permission = $temp_permission;
        $user->username = $request->username;
        $user->password = Hash::make($request->password);
        $user->profile_photo = $fileNameToStore;
        $user->save();
    }


    private function update_user(Request $request, $role, $temp_permission ,$fileNameToStore, $id){
        User::where('id',$id)->update(array(
            'isActivated' => $request->isActivated,
            'name' => $request->name,
            'position' => $request->position,
            'functional_unit' => $request->functional_unit,
            'role' => $role,
            'permission' => $temp_permission,
            'username' => $request->username,
            'profile_photo' => $fileNameToStore
        ));
    }



    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create_employee()
    {
        $functional_units = [];
        $data = [];
        $this->get_data($data, $functional_units);
        return view('accounts.create_employee')->with(['functional_units' => $functional_units, 'data' => $data]);
    }

     /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create_manager()
    {
        $functional_units = [];
        $data = [];
        $this->get_data($data, $functional_units);

        return view('accounts.create_manager')->with(['functional_units' => $functional_units, 'data' => $data]);
    }

     /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create_admin()
    {
        $functional_units = [];
        $data = [];
        $this->get_data($data, $functional_units);

        return view('accounts.create_admin')->with(['functional_units' => $functional_units, 'data' => $data]);
    }

    

    /**
     * @param  \Illuminate\Http\Request  $request
     * @param  string $role
     * @return \Illuminate\Http\Response
     */
    public function store_employee(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'position' => 'required',
            'functional_unit' => 'required',
            'username' => 'required',
            'password' => 'required|confirmed',
            'profile_photo' => 'image|nullable'
        ]);

        $fileNameToStore = '';
        $this->get_photo($request, $fileNameToStore, 'default.jpg');
    
        $temp_permission = ["0", "0", "0", "0", "0", "0", "0", "0", "0", "0", "0", "0"];
        $this->process_permission_array($request, $temp_permission);
        
        $this->save_user($request, 'employee', $temp_permission, $fileNameToStore);
        
        return redirect('sysmg/accounts/');
    }


    public function store_manager(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'position' => 'required',
            'functional_unit' => 'required',
            'username' => 'required',
            'password' => 'required|confirmed',
            'profile_photo' => 'image|nullable'
        ]);

        $fileNameToStore = '';
        $this->get_photo($request, $fileNameToStore, 'default.jpg');
    
        $temp_permission = ["0", "0", "0", "0", "0", "0", "0", "0", "0", "0", "0", "0"];
        $this->process_permission_array($request, $temp_permission);
        
        $this->save_user($request, 'manager', $temp_permission, $fileNameToStore);
        
        return redirect('sysmg/accounts/');
    }


    public function store_admin(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'position' => 'required',
            'functional_unit' => 'required',
            'username' => 'required',
            'password' => 'required|confirmed',
            'profile_photo' => 'image|nullable'
        ]);

        $fileNameToStore = '';
        $this->get_photo($request, $fileNameToStore, 'default.jpg');
            
        $this->save_user($request, 'admin', '1,1,1,1,1,1,1,1,1,1,1,1', $fileNameToStore);
        
        return redirect('sysmg/accounts/');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::find($id);
        $user->permission = explode(',', $user->permission); //splitting the permission array
        return view('accounts.show')->with('user', $user);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::find($id);
        $user->permission = explode(',', $user->permission);

        $functional_units = [];
        $data = [];
        $this->get_data($data, $functional_units);

        switch ($user->role) {
            case 'employee':
                return view('accounts.edit_employee')->with([ 'user' => $user, 'functional_units' => $functional_units, 'data' => $data]);
                break;

            case 'manager':
                return view('accounts.edit_manager')->with([ 'user' => $user, 'functional_units' => $functional_units, 'data' => $data]);
                break;

            case 'admin':
                return view('accounts.edit_admin')->with([ 'user' => $user, 'functional_units' => $functional_units, 'data' => $data]);
                break;
            
        }
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
            'isActivated' => 'required',
            'name' => 'required',
            'position' => 'required',
            'functional_unit' => 'required',
            'username' => 'required',
            'role' => 'required',
            'profile_photo' => 'image|nullable'
        ]);

        

        $user = User::find($id);

        $fileNameToStore = '';
        $this->get_photo($request, $fileNameToStore, $user->profile_photo);

        
        //if the user account is uograded to admin account
        if($request->role === 'admin'){
            $this->update_user($request, $request->role, '1,1,1,1,1,1,1,1,1,1,1,1', $fileNameToStore, $id);
            return redirect('/sysmg/accounts');

        }

        //if admin account is downgraded to employee
        if($request->role === 'employee' && $user->role === 'admin'){
            $this->update_user($request, $request->role, '1,0,0,1,0,0,1,0,0,1,0,0', $fileNameToStore, $id);
            return redirect('/sysmg/accounts');

        }

        //if admin account is downgraded to manager
        if($request->role === 'manager' && $user->role === 'admin'){
            $this->update_user($request, $request->role, '1,1,1,1,1,1,1,1,1,1,1,1', $fileNameToStore, $id);
            return redirect('/sysmg/accounts');

        }

        //if manager account is downgraded to employee
        if($request->role === 'employee' && $user->role === 'manager'){
            $this->update_user($request, $request->role, '1,0,0,1,0,0,1,0,0,1,0,0', $fileNameToStore, $id);
            return redirect('/sysmg/accounts');

        }

        //if employee account is upgraded to manager
        if($request->role === 'manager' && $user->role === 'employee'){
            $this->update_user($request, $request->role, '1,0,0,1,0,0,1,0,0,1,0,0', $fileNameToStore, $id);
            return redirect('/sysmg/accounts');
        }



        $temp_permission = ["0", "0", "0", "0", "0", "0", "0", "0", "0", "0", "0", "0"];
        $this->process_permission_array($request, $temp_permission);
       
        $this->update_user($request, $request->role, $temp_permission, $fileNameToStore, $id);
        return redirect('/sysmg/accounts');

        
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
        $users = User::where('name', 'like', '%'.$request->search_term.'%')->paginate(10);
        return view('accounts.index')->with('users', $users);
    }

    public function change_password($id){
        $user = User::find($id);
        return view('accounts.change_pass')->with('user', $user);
    }

    public function update_password(Request $request, $id){
        $this->validate($request, [
            'password' => 'required|confirmed'
        ]);

        $user = User::find($id);
        User::where('id',$id)->update(array(
            'password' => Hash::make($request->password)
        ));
        return redirect('/sysmg/accounts/'.$id);
        
        
    }
}
