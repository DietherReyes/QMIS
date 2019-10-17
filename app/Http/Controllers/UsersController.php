<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class UsersController extends Controller
{
    //Get all users
    public function getUsers(){
        $users = User::all();
        return view('SYSMG_accounts')->with('users',$users);
    }

}
