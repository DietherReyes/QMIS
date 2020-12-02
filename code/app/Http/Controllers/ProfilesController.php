<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;
use App\User;
use App\Log;
use Illuminate\Support\Facades\Storage;
use Auth;
use Validator;

class ProfilesController extends Controller
{
    public function __construct(){

        //Checks if user is authenticated
        $this->middleware('auth');
        $this->middleware('account_status');
        
        //Form validation messages
        $this->custom_messages = [
            'required'          => 'This field is required.',
            'image'             => 'The input must be an image file.',
            'name.max'          => 'The input must not be greater than 255 characters.',
            'position.max'      => 'The input must not be greater than 255 characters.',
            'username.max'      => 'The input must not be greater than 255 characters.',
            'profile_photo.max' => 'The image file size must not be greater than 5MB.'
        ];
    }
    

    //View own profile
    public function show($id){
        $user = User::find($id);
        if(Auth::id() !== $user->id ){    
            return redirect('/unauthorized');
        }
        $user->permission = explode(',', $user->permission); //splitting the permission array
        return view('profiles.show')->with('user', $user);
    }

    //Edit own profile
    public function edit($id){
        $user = User::find($id);
        if(Auth::id() !== $user->id ){    
            return redirect('/unauthorized');
        }
        return view('profiles.edit')->with('user', $user);
    }

    //Update own profile
    public function update(Request $request, $id){

        $validator = Validator::make($request->all(), [
            'profile_photo'     => 'image|nullable|max:5000',
            'name'              => 'required|max:255',
            'position'          => 'required|max:255',
        ], $this->custom_messages);

        //Checks if username already exists
        $old_user = User::find($id);
        if($request->username === $old_user->username){
            $validator->validate();
        }else{
            $username = User::where('username', $request->username)->pluck('username');
            if(count($username) === 0){
                $validator->validate();
            }else{
    
                $validator->after(function ($validator) {
                    $validator->errors()->add('username', 'The username has already been taken.');
                });
                $validator->validate();
                
            }
        }


        $user               = User::find($id);
        $log                = new Log;
        $log->name          = Auth::user()->name;
        $log->action        = 'EDIT';
        $log->module        = 'PROFILE';
        $log->description   = 'Updated  user: ' . $user->name;
        $log->save();


        // Handle File Upload
        $fileNameToStore = $user->profile_photo;
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

    //Change own password
    public function change_password($id){
        $user = User::find($id);
        if(Auth::id() !== $user->id ){    
            return redirect('/unauthorized');
        }
        return view('profiles.change_pass')->with(['user' => $user, 'old_password_error' => 0]);
    }

    //Update own password
    public function update_password(Request $request, $id){
        $this->validate($request, [
            'old_password'  => 'required',
            'password'      => 'required|confirmed'
        ], $this->custom_messages);

        $user = User::find($id);

        $log                = new Log;
        $log->name          = Auth::user()->name;
        $log->action        = 'EDIT';
        $log->module        = 'PROFILE-PASSWORD';
        $log->description   = 'Updated password of  user: ' . $user->name;
        $log->save();

        //Hash new password
        if (Hash::check($request->old_password, $user->password)) {
            User::where('id',$id)->update(array(
                'password' => Hash::make($request->password)
            ));
            return redirect('/profiles/'.$id);
        }
        
        return view('profiles.change_pass')->with(['user' => $user, 'old_password_error' => 1]);
        
    }
}
