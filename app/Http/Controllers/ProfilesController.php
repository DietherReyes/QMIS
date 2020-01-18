<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;
use App\User;
use Illuminate\Support\Facades\Storage;
use Auth;

class ProfilesController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
        
        $this->custom_messages = [
            'required'          => 'This field is required.',
            'image'             => 'The input must be an image file.',
            'name.max'          => 'The input must not be greater than 255 characters.',
            'position.max'      => 'The input must not be greater than 255 characters.',
            'username.max'      => 'The input must not be greater than 255 characters.',
            'profile_photo.max' => 'The image file size must not be greater than 5MB.'
        ];
    }
    


    public function show($id){
        
        $user = User::find($id);
        if(Auth::id() !== $user->id ){    
            return redirect('/unauthorized');
        }
        $user->permission = explode(',', $user->permission); //splitting the permission array
        return view('profiles.show')->with('user', $user);
    }

    public function edit($id){
        $user = User::find($id);
        if(Auth::id() !== $user->id ){    
            return redirect('/unauthorized');
        }
        return view('profiles.edit')->with('user', $user);
    }

    public function update(Request $request, $id){

        $this->validate($request, [
            'profile_photo'     => 'image|nullable|max:5000',
            'name'              => 'required|max:255',
            'position'          => 'required|max:255',
            'username'          => 'required|unique:users|max:255',
        ], $this->custom_messages);


        $user = User::find($id);
        $fileNameToStore = $user->profile_photo;
        // Handle File Upload
        if($request->hasFile('profile_photo')){
            
            if($user->profile_photo !== 'default.jpg'){
                //delete previeous photo
                Storage::delete('public/profile_photos/'.$user->profile_photo);
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
        }

        User::where('id',$id)->update(array(
            'name'          => $request->name,
            'position'      => $request->position,
            'username'      => $request->username,
            'profile_photo' => $fileNameToStore
        ));

        return redirect('/profiles/'.$user->id);
    }

    public function change_password($id){
        $user = User::find($id);
        if(Auth::id() !== $user->id ){    
            return redirect('/unauthorized');
        }
        return view('profiles.change_pass')->with(['user' => $user, 'old_password_error' => 0]);
    }

    public function update_password(Request $request, $id){
        $this->validate($request, [
            'old_password'  => 'required',
            'password'      => 'required|confirmed'
        ], $this->custom_messages);

        $user = User::find($id);

        if (Hash::check($request->old_password, $user->password)) {
           
            User::where('id',$id)->update(array(
                'password' => Hash::make($request->password)
            ));
            return redirect('/profiles/'.$id);
        }
        
        return view('profiles.change_pass')->with(['user' => $user, 'old_password_error' => 1]);
        
    }
}
